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
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel 'users' (siapa yang mengajukan)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Relasi ke tabel 'publications' (publikasi master mana yang diajukan)
            $table->foreignId('publication_id')->constrained()->onDelete('cascade');
            
            // Kolom-kolom dari formulir SPRP
            $table->string('jumlah_romawi')->nullable();
            $table->string('jumlah_arab')->nullable();
            $table->string('kategori');
            $table->string('isbn')->nullable();
            $table->string('issn')->nullable();
            
            // Relasi ke tabel 'users' lagi (siapa pembuat cover)
            $table->foreignId('pembuat_cover_id')->nullable()->constrained('users')->onDelete('set null');
            
            $table->string('orientasi');
            $table->string('diterbitkan_untuk');
            $table->string('ukuran_kertas');

            // Status alur kerja
            $table->string('status')->default('draft'); // Contoh: draft, submitted, in_review, approved, rejected

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
