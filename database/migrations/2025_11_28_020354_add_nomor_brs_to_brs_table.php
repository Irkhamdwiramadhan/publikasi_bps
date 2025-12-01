<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 public function up()
{
    Schema::table('brs', function (Blueprint $table) {
        // Menambah kolom nomor_brs setelah id (atau di mana saja)
        $table->string('nomor_brs')->nullable()->after('id'); 
    });
}

public function down()
{
    Schema::table('brs', function (Blueprint $table) {
        $table->dropColumn('nomor_brs');
    });
}
};
