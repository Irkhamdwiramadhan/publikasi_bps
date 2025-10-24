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
        Schema::table('publications', function (Blueprint $table) {
            // Ubah kolom ini agar boleh kosong (NULL)
            $table->string('region_code')->nullable()->change();
            $table->string('language')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('publications', function (Blueprint $table) {
            // Jika di-rollback, kembalikan seperti semula (tidak boleh kosong)
            $table->string('region_code')->nullable(false)->change();
            $table->string('language')->nullable(false)->change();
        });
    }
};