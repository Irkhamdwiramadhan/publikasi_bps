<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submission_publications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('publication_id')->constrained('publications')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('fungsi_pengusul');
            $table->string('tautan_publikasi')->nullable();
            $table->string('spnrs_ketua_tim')->nullable();
            $table->enum('status', [
                'draft',
                'butuh_perbaikan',
                'sedang_diperiksa',
                'disetujui',
                'ditolak'
            ])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submission_publications');
    }
};
