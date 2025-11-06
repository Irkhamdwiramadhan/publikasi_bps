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
    Schema::create('brs', function (Blueprint $table) {
        $table->id();
        $table->string('judul');
        
        // 'bulan' paling baik disimpan sebagai 'date'. 
        // Kita simpan sebagai tanggal 1 setiap bulan, misal: 2025-11-01
        $table->date('bulan'); 

        // 'pengelola' (Relasi ke tabel users)
        $table->foreignId('user_id')->constrained('users');
        
        // Kolom untuk menyimpan path file
        $table->string('pdf_path')->nullable();
        
        // PENTING: Untuk 'infografis' (multiple), kita gunakan tipe JSON
        $table->json('infografis_paths')->nullable(); 
        
        $table->string('zip_path')->nullable();
        $table->string('excel_path')->nullable(); // Opsional, jadi nullable
        
        $table->timestamps();
    });
}
};
