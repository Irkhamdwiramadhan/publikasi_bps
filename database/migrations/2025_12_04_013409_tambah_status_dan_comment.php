<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Tambah kolom status di tabel 'brs'
        Schema::table('brs', function (Blueprint $table) {
            // Default status 'draft'
            $table->enum('status', ['draft', 'sedang_diperiksa', 'disetujui', 'butuh_perbaikan', 'ditolak'])
                  ->default('draft')
                  ->after('bulan'); 
        });

        // 2. Buat tabel komentar khusus BRS
        Schema::create('brs_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brs_id')->constrained('brs')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('body');
            $table->string('role')->nullable(); // Penyusun / Pemeriksa
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('brs_comments');
        Schema::table('brs', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};