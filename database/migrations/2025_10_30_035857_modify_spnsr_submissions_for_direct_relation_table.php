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
        Schema::table('spnsr_submissions', function (Blueprint $table) {
            // 1. Tambahkan kolom foreign key baru yang menunjuk LANGSUNG ke pengajuan publikasi
            $table->foreignId('submission_publication_id')
                  ->nullable()
                  ->after('user_id') // Posisikan setelah user_id
                  ->constrained('submission_publications') // Menunjuk ke tabel 'submission_publications'
                  ->onDelete('cascade'); // Jika pengajuan publikasi dihapus, SPNSR-nya ikut terhapus

            // 2. Hapus kolom-kolom lama yang tidak perlu lagi
            // (karena kita bisa dapat info ini dari relasi)
            $table->dropColumn('judul_publikasi');
            $table->dropColumn('tipe_arc');

            // 3. Hapus foreign key dan kolom publication_id (jika sudah ada)
            // Pastikan Anda sudah rollback/fix migrasi sebelumnya jika kolom ini belum ada
            if (Schema::hasColumn('spnsr_submissions', 'publication_id')) {
                 // Hapus constraint dulu (nama constraint mungkin beda)
                 // Coba cari nama constraint di DB Anda jika error
                // $table->dropForeign(['publication_id']); 
                $table->dropColumn('publication_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spnsr_submissions', function (Blueprint $table) {
            // Tambahkan kembali kolom yang dihapus
            $table->string('judul_publikasi')->after('tanggal_prosa');
            $table->string('tipe_arc')->after('judul_publikasi');
            $table->foreignId('publication_id')->nullable()->constrained('publications');

            // Hapus kolom baru
            $table->dropForeign(['submission_publication_id']);
            $table->dropColumn('submission_publication_id');
        });
    }
};
