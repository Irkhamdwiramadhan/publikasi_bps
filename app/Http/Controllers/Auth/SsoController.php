<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Auth\BpsSsoProvider; // Import Provider Custom Kita
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class SsoController extends Controller
{
    /**
     * Redirect ke SSO BPS.
     */
    public function redirect()
    {
        // Panggil Provider Custom BPS dengan scope yang dibutuhkan
        return Socialite::buildProvider(
            BpsSsoProvider::class,
            config('services.bps')
        )->scopes(['openid', 'profile', 'email'])
         ->redirect();
    }

    /**
     * Callback dari SSO BPS.
     */
    public function callback()
    {
        try {
            // 1. Ambil Data User dari SSO (lewat Provider Custom)
            $ssoUser = Socialite::buildProvider(
                BpsSsoProvider::class,
                config('services.bps')
            )->stateless()->user();

            // 2. LOGIKA PENTING: Cek Email di Database Lokal
            // Kita cari user di database GUCI yang emailnya SAMA dengan email SSO.
            $user = User::where('email', $ssoUser->email)->first();

            // Jika user TIDAK DITEMUKAN (Email belum terdaftar di GUCI) -> TOLAK
            if (!$user) {
                Log::warning("Percobaan login SSO gagal (Email tidak terdaftar): " . $ssoUser->email);
                
                return redirect()->route('login')
                    ->with('error', 'Akun SSO Anda (' . $ssoUser->email . ') belum terdaftar di sistem GUCI. Silakan hubungi Tim IT BPS Kab. Tegal untuk registrasi manual.');
            }

            // 3. Jika User Ditemukan -> LOGIN
            // (Kita TIDAK melakukan update data seperti nama/NIP, sesuai permintaan)
            
            Auth::login($user);
            request()->session()->regenerate();

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Login Berhasil! Selamat datang kembali, ' . $user->name);

        } catch (Exception $e) {
            Log::error("Error Callback SSO: " . $e->getMessage());
            
            return redirect()->route('login')
                ->with('error', 'Gagal login via SSO. Terjadi kesalahan teknis.');
        }
    }
}