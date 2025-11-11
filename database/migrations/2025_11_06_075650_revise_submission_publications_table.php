<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Daftar kolom yang kita inginkan ada di tabel.
     */
    private $kolomBaru = [
        // Nama kolom => [Tipe, Posisi 'after']
        'catalog_id'       => ['type' => 'foreignId', 'after' => 'user_id', 'constrained' => 'catalogs'],
        'judul_publikasi'  => ['type' => 'string', 'after' => 'user_id'], // Ini adalah 'title_ind'
        'type_publikasi'   => ['type' => 'string', 'after' => 'judul_publikasi'],
        'judul_eng'        => ['type' => 'string', 'after' => 'type_publikasi'],
        'estimasi_rilis'   => ['type' => 'date', 'after' => 'judul_eng'],
        'bahasa'           => ['type' => 'string', 'after' => 'estimasi_rilis', 'length' => 50],
        'issn'             => ['type' => 'string', 'after' => 'bahasa'],
        'isbn'             => ['type' => 'string', 'after' => 'issn'],
        'fungsi_pengusul'  => ['type' => 'string', 'after' => 'isbn'],
        'tautan_publikasi' => ['type' => 'string', 'after' => 'fungsi_pengusul'], // Ini adalah 'link_publikasi'
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('submission_publications', function (Blueprint $table) {
            
            foreach ($this->kolomBaru as $namaKolom => $detail) {
                
                // Cek HANYA JIKA KOLOM BELUM ADA
                if (!Schema::hasColumn('submission_publications', $namaKolom)) {
                    
                    $column = $table->{$detail['type']}($namaKolom); // $table->string('judul_publikasi')

                    if (isset($detail['length'])) {
                        $column->length($detail['length']);
                    }
                    
                    // Cek apakah kolom 'after' ada sebelum menambahkannya
                    if (isset($detail['after']) && Schema::hasColumn('submission_publications', $detail['after'])) {
                        $column->after($detail['after']);
                    }

                    $column->nullable();

                    if ($detail['type'] === 'foreignId') {
                        $column->constrained($detail['constrained']);
                    }
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submission_publications', function (Blueprint $table) {
            
            // Cek foreign key 'catalog_id' sebelum dihapus
            if (Schema::hasColumn('submission_publications', 'catalog_id')) {
                try {
                    $table->dropForeign(['catalog_id']);
                } catch (\Exception $e) { /* Abaikan jika error */ }
            }

            // Loop dan hapus kolom HANYA JIKA ADA
            foreach (array_keys($this->kolomBaru) as $namaKolom) {
                if (Schema::hasColumn('submission_publications', $namaKolom)) {
                    $table->dropColumn($namaKolom);
                }
            }
        });
    }
};