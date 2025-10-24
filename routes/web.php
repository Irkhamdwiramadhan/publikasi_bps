<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use App\Http\Controllers\SprpController; // <-- 1. Import Controller baru
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubmissionPublicationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware(['role:Admin'])->group(function () {
        Route::resource('publications', PublicationController::class);
        Route::resource('users', UserController::class);
        Route::patch('/users/{user}/status', [App\Http\Controllers\UserController::class, 'updateStatus'])->name('users.updateStatus');

        // Rute untuk Impor & Ekspor Excel
        Route::get('publications-export-template', [PublicationController::class, 'exportTemplate'])->name('publications.exportTemplate');
        Route::post('publications-import', [PublicationController::class, 'import'])->name('publications.import');


        Route::resource('users', App\Http\Controllers\UserController::class);
    });

    // --- GRUP BARU UNTUK PEGAWAI (PENYUSUN, PEMERIKSA, PIMPINAN) ---
    // Hanya pengguna dengan salah satu dari peran ini yang bisa mengaksesnya.

    // [REVISI DI SINI]
    // Kita gunakan nama Role 'Pemeriksa' langsung, bukan nama Gate 'is_pemeriksa'
    Route::middleware(['role:Penyusun|Pemeriksa|Pimpinan'])->group(function () {
        // Rute untuk fitur Pengajuan.
        Route::resource('sprp', SprpController::class)->only(['index', 'create', 'store', 'show']);

        // Tambahkan rute untuk 'updateNomor' yang kita buat di index
        Route::patch('/sprp/{sprp}/update-nomor', [SprpController::class, 'updateNomor'])->name('sprp.updateNomor');
    });


    Route::middleware(['auth'])->group(function () {
        Route::resource('pengajuan_publikasi', SubmissionPublicationController::class)
            ->names('pengajuan_publikasi');
    });
    // web.php
    Route::put('/pengajuan_publikasi/{id}/update-status', [SubmissionPublicationController::class, 'updateStatus'])
        ->name('pengajuan_publikasi.updateStatus');

    // Rute kustom untuk Halaman Komentar
    Route::get('/pengajuan_publikasi/{submissionPublication}/comment', [SubmissionPublicationController::class, 'comment'])->name('pengajuan_publikasi.comment');
    Route::post('/pengajuan_publikasi/{submissionPublication}/comment', [SubmissionPublicationController::class, 'storeComment'])->name('pengajuan_publikasi.storeComment');
});


// [DIHAPUS] Kode "pencegat" (dd) untuk login telah dihapus.
// ------------------------------------

require __DIR__ . '/auth.php';
