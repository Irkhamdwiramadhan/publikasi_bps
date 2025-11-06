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
        Schema::table('submission_comments', function (Blueprint $table) {
            $table->string('role')->nullable()->after('user_id'); // Role pengirim komentar
            $table->boolean('is_read')->default(false)->after('body'); // Status komentar sudah dibaca
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submission_comments', function (Blueprint $table) {
            $table->dropColumn(['role', 'is_read']);
        });
    }
};
