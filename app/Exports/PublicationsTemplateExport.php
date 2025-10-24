<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PublicationsTemplateExport implements WithHeadings, FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        // Header kolom untuk template Excel dalam Bahasa Indonesia
        return [
            'jenis_publikasi',
            'nomor_katalog',
            'tahun',
            'judul', // Menggantikan title_ind dan title_eng
            'frekuensi',
            'nomor_issn',
        ];
    }

    /**
    * Memberitahu Laravel Excel bahwa data kita kosong.
    */
    public function collection()
    {
        return collect([]);
    }
}

