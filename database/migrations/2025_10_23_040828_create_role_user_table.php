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
        // Ini adalah tabel "Jembatan" (Pivot Table)
        // Namanya 'role_user' (konvensi Laravel)
        Schema::create('role_user', function (Blueprint $table) {
            $table->id();

            // 1. Kolom untuk 'user_id'
            //    constrained() akan otomatis mencari tabel 'users'
            //    onDelete('cascade') berarti jika user dihapus, data di sini juga terhapus
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // 2. Kolom untuk 'role_id'
            //    constrained() akan otomatis mencari tabel 'roles'
            //    onDelete('cascade') berarti jika role dihapus, data di sini juga terhapus
            $table->foreignId('role_id')->constrained()->onDelete('cascade');

            // 3. (Opsional tapi direkomendasikan)
            //    Mencegah 1 user punya 1 role yang sama lebih dari sekali
            $table->unique(['user_id', 'role_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_user');
    }
};

