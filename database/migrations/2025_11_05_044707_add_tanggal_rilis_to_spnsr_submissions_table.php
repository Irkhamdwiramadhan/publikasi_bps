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
    Schema::table('spnsr_submissions', function (Blueprint $table) {
        // Tambahkan kolom tanggal rilis. 
        // Dibuat nullable() agar data lama tidak error.
        $table->date('tanggal_rilis')->nullable()->after('keterangan'); 
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spnsr_submissions', function (Blueprint $table) {
            //
        });
    }
};
