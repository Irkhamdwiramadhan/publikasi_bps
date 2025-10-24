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
            // 1. HAPUS kolom 'category' yang bermasalah
            if (Schema::hasColumn('publications', 'category')) {
                $table->dropColumn('category');
            }

            // 2. PASTIKAN ULANG kolom ini boleh null
            if (Schema::hasColumn('publications', 'region_code')) {
                $table->string('region_code')->nullable()->change();
            }
            if (Schema::hasColumn('publications', 'language')) {
                $table->string('language')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('publications', function (Blueprint $table) {
            // Jika di-rollback, buat ulang kolomnya (untuk keamanan)
            $table->string('category')->nullable(); 
            $table->string('region_code')->nullable(false)->change();
            $table->string('language')->nullable(false)->change();
        });
    }
};