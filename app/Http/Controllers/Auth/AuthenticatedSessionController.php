<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Tangani proses login.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Autentikasi user berdasarkan name & password
        $request->authenticate();

        // Regenerasi session untuk keamanan
        $request->session()->regenerate();

        // Ambil user yang sedang login
        $user = Auth::user();

        /**
         * Redirect berdasarkan role:
         * - Admin, Pemeriksa, Pimpinan → Dashboard
         * - Penyusun → Pengajuan Publikasi
         */
        if ($user->hasRole('Penyusun')) {
            return redirect()->route('pengajuan_publikasi.index');
        }

        if ($user->hasAnyRole(['Admin', 'Pemeriksa', 'Pimpinan'])) {
            return redirect()->route('dashboard');
        }

        // Default fallback jika role tidak cocok
        return redirect()->route('dashboard');
    }

    /**
     * Logout user dari sistem.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
