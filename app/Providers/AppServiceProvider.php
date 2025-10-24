<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // ===== LOGIKA GATE (VERSI SPATIE YANG BENAR) =====

        // Kita akan menggunakan fungsi 'hasRole()' bawaan dari Trait Spatie
        // yang sudah ada di Model User Anda.

        /**
         * Gate untuk 'is_pemeriksa'
         * Pastikan nama 'Pemeriksa' SAMA PERSIS dengan di tabel 'roles'.
         */
        Gate::define('is_pemeriksa', function (User $user) {
            // Ini adalah sintaks Spatie yang benar
            return $user->hasRole('Pemeriksa');
        });

        /**
         * Gate untuk 'is_admin'
         */
        Gate::define('is_admin', function (User $user) {
            return $user->hasRole('Admin');
        });

        /**
         * Gate untuk 'is_penyusun'
         */
        Gate::define('is_penyusun', function (User $user) {
            return $user->hasRole('Penyusun');
        });

        /**
         * Gate untuk 'is_pimpinan' (Pemantau/Pimpinan)
         * Sesuaikan 'Pimpinan' dengan nama di tabel 'roles' Anda
         */
        Gate::define('is_pimpinan', function (User $user) {
            return $user->hasRole('Pimpinan');
        });
    }
}

