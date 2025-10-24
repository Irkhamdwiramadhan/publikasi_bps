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
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom NIP BPS setelah 'email'
            $table->string('nip_bps')->nullable()->unique()->after('email');
            
            // Tambahkan kolom status setelah 'password'
            $table->boolean('status')->default(true)->after('password'); // true = Aktif, false = Tidak Aktif
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nip_bps', 'status']);
        });
    }
};

