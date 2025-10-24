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
        Schema::create('submission_comments', function (Blueprint $table) {
            $table->id();
            
            // [REVISI]
            // Foreign key diubah agar sesuai dengan tabel 'submission_publications'
            $table->foreignId('submission_publication_id') 
                  ->constrained('submission_publications') // Mengacu ke tabel Anda
                  ->onDelete('cascade');

            // Kolom ini menghubungkan komentar ke pengguna (siapa yang menulis)
            $table->foreignId('user_id')
                  ->constrained('users') // Mengacu ke tabel 'users'
                  ->onDelete('cascade');

            // Kolom untuk isi komentar
            $table->text('body');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submission_comments');
    }
};

