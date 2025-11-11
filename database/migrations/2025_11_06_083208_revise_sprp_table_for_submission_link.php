<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Daftar kolom lama yang AKAN DIHAPUS dari 'sprps'.
     * Kolom penting seperti 'nomor_publikasi_final' TIDAK ADA di daftar ini.
     */
    private $kolomLama = [
        'publication_id', // Link lama      
        'issn',           // Pindah ke submission_publications
        'isbn',           // Pindah ke submission_publications
        
        // Kolom "sampah" dari migrasi gagal (jika ada)
        'catalog_id',     
        'judul_manual',
        'tipe_publikasi_manual',
    ];

    /**
     * Run the migrations.
     */
   public function up(): void
    {
        Schema::table('submission_publications', function (Blueprint $table) {
            
            // Cek apakah kolomnya ada sebelum dihapus
            if (Schema::hasColumn('submission_publications', 'publication_id')) {
                
                // ▼▼▼ BLOK try...catch DIHAPUS ▼▼▼
                // (Kita tidak perlu drop foreign key jika tidak ada)
                
                // 2. Langsung hapus kolomnya
                $table->dropColumn('publication_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submission_publications', function (Blueprint $table) {
            
            // (Opsional) Kode untuk mengembalikan kolom jika di-rollback
            if (!Schema::hasColumn('submission_publications', 'publication_id')) {
                $table->foreignId('publication_id')->nullable()->after('id');
            }
        });
    }
};