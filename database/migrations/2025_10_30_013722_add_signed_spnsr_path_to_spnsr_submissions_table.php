<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('spnsr_submissions', function (Blueprint $table) {
            // Kolom untuk menyimpan path file PDF SPNSR yang sudah ditandatangani
            $table->string('signed_spnsr_path')->nullable()->after('status'); 
        });
    }

    public function down(): void
    {
        Schema::table('spnsr_submissions', function (Blueprint $table) {
            $table->dropColumn('signed_spnsr_path');
        });
    }
};
