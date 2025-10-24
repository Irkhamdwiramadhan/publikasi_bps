<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Opsional: contoh user dummy dari factory (boleh dihapus kalau tidak perlu)
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Panggil seeder utama untuk roles & users
        $this->call(UserRoleSeeder::class);
    }
}
