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
        Schema::create('spnsr_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Relasi ke tabel users (Penyusun)
            $table->string('nomor_surat');
            $table->text('tanggal_prosa');
            $table->string('judul_publikasi');
            $table->string('tipe_arc');
            $table->string('keterangan')->nullable();
            $table->string('status')->default('Draft'); // Status awal: Draft, Disetujui, Ditolak
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spnsr_submissions');
    }
};
