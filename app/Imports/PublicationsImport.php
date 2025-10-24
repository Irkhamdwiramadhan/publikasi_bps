<?php

namespace App\Imports;

use App\Models\Publication;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class PublicationsImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        

        // Kode di bawah ini tidak akan berjalan untuk sementara
        return new Publication([
            'publication_type' => $row['jenis_publikasi'],
            'catalog_number'   => $row['nomor_katalog'],
            'year'             => $row['tahun'],
            'title_ind'        => $row['judul'],
            'frequency'        => $row['frekuensi'],
            'issn_number'      => $row['nomor_issn'],
        ]);
    }

    public function rules(): array
    {
        return [
            'jenis_publikasi' => 'required|string',
            'nomor_katalog'   => 'required|string',
            'tahun'           => 'required|integer',
            'judul'           => 'required|string',
            'frekuensi'       => 'nullable|string',
            'nomor_issn'      => 'nullable|string',
        ];
    }
    
    public function prepareForValidation($data, $index)
    {
        foreach ($data as $key => $value) {
            $data[$key] = trim($value);
        }
        return $data;
    }
}

