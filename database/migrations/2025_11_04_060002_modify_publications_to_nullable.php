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
            // Ubah 3 kolom ini agar mengizinkan NULL
            $table->string('catalog_number', 50)->nullable()->change();
            $table->integer('year')->nullable()->change();
            $table->string('frequency', 100)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Ini adalah kebalikan (down) agar bisa di-rollback
        Schema::table('publications', function (Blueprint $table) {
            $table->string('catalog_number', 50)->nullable(false)->change();
            $table->integer('year')->nullable(false)->change();
            $table->string('frequency', 100)->nullable(false)->change();
        });
    }
};