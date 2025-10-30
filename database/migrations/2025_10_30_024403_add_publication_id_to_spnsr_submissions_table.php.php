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
            $table->foreignId('publication_id')
                ->nullable()
                ->after('user_id') // Sesuaikan posisi jika perlu
                ->constrained('publications') // Relasi ke tabel publications (master)
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revisi: Menghapus kolom jika migrasi di-rollback
        Schema::table('publications', function (Blueprint $table) {
            $table->dropForeign(['spnsr_submission_id']);
            $table->dropColumn('spnsr_submission_id');
        });
    }
};
