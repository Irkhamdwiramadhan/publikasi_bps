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
        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->string('publication_type'); // Tipe: "ARC" atau "Non ARC" [cite: 76, 92]
            $table->string('region_code');      // Kode Wilayah [cite: 83]
            $table->string('catalog_number');   // Nomor Katalog [cite: 84, 109]
            $table->year('year');               // Tahun [cite: 85]
            $table->string('title_ind');        // Judul (IND) [cite: 86]
            $table->string('title_eng')->nullable(); // Judul (ENG) [cite: 90]
            $table->string('frequency');        // Frekuensi (Tahunan, dll) [cite: 103]
            $table->string('language');         // Bahasa [cite: 104]
            $table->string('issn_number')->nullable(); // No ISSN [cite: 105]
            $table->timestamps();               // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};
