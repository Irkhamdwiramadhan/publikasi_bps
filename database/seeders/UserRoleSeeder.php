<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $pegawaiRole = Role::firstOrCreate(['name' => 'Pegawai']);
        Role::firstOrCreate(['name' => 'Penyusun']);
        Role::firstOrCreate(['name' => 'Pemeriksa']);
        Role::firstOrCreate(['name' => 'Pimpinan']);

        // PASTIKAN BLOK INI MEMILIKI 'status' => true
        User::create([
            'name' => 'Admin BPS',
            'nip_bps' => 'ADMIN001',
            'email' => 'admin@bps.test',
            'password' => Hash::make('password'),
            'status' => true, // <-- INI YANG PALING PENTING
        ])->assignRole($adminRole);

        User::create([
            'name' => 'Irkham, S.Kom',
            'nip_bps' => '123456789',
            'email' => null,
            'password' => Hash::make('password'),
            'status' => true, // <-- Pastikan ini ada juga
        ])->assignRole($pegawaiRole, 'Penyusun');
    }
}