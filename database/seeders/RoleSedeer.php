<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// Gunakan Model Role dari Spatie, BUKAN App\Models\Role kustom
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Baris-baris ini penting untuk me-reset cache Spatie
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Kosongkan tabel (truncate) untuk menghindari duplikat jika seeder dijalankan ulang
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Role::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Buat roles berdasarkan screenshot Anda.
        // Kita WAJIB menyertakan 'guard_name' => 'web'
        Role::create(['name' => 'Admin', 'guard_name' => 'web']);
        Role::create(['name' => 'Pegawai', 'guard_name' => 'web']);
        Role::create(['name' => 'Penyusun', 'guard_name' => 'web']);
        Role::create(['name' => 'Pemeriksa', 'guard_name' => 'web']); // <-- Ini yang dicari oleh Gate
        Role::create(['name' => 'Pimpinan', 'guard_name' => 'web']);
    }
}

