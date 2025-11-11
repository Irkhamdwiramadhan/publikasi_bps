<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('submission_publications', function (Blueprint $table) {
            
            // Cek apakah kolomnya ada sebelum dihapus
            if (Schema::hasColumn('submission_publications', 'publication_id')) {
                
                // 1. Coba hapus foreign key (jika ada)
                try {
                    // Asumsi nama constraint default: submission_publications_publication_id_foreign
                    $table->dropForeign(['publication_id']);
                } catch (\Exception $e) {
                    // Abaikan jika constraint tidak ada atau nama salah
                }
                
                // 2. Hapus kolomnya
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