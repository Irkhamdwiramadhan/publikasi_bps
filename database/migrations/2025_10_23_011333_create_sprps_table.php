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
        Schema::create('sprps', function (Blueprint $table) {
            $table->id();

            // Relasi ke user (Penyusun/Pengaju)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Relasi ke publikasi yang diajukan
            $table->foreignId('publication_id')->constrained('publications')->onDelete('cascade');

            // Metadata dari Form (Halaman 7 PDF)
            $table->string('jumlah_romawi')->nullable();
            $table->string('jumlah_arab')->nullable();
            $table->string('kategori');
            $table->string('isbn')->nullable();
            $table->string('issn')->nullable();
            
            // --- [INI PERUBAHANNYA] ---
            // Mengubah dari foreignId ke string
            $table->string('pembuat_cover'); // 'Subdit Publikasi/IPDS' atau 'Subject Matter'
            
            $table->string('orientasi'); // 'Portrait' atau 'Landscape'
            $table->string('diterbitkan_untuk'); // 'Publik' atau 'Internal'
            $table->string('ukuran_kertas'); // 'A4', 'B5', dll.

            // Kolom untuk Alur Kerja (Workflow)
            // (Sesuai Halaman 12 PDF)
            $table->string('status')->default('Sedang diperiksa'); // Status awal
            
            // Kolom untuk Halaman 10 PDF
            $table->string('nomor_publikasi_final')->nullable(); // Diisi oleh Pemeriksa

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sprps');
    }
};

