<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use App\Http\Controllers\SprpController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubmissionPublicationController;
use App\Http\Controllers\SpnsrController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- [TAMBAHAN] RUTE TES DEBUGGING ---
// Tes ini untuk memastikan server Anda membaca file web.php
Route::get('/test-route', function () {
    return 'Halo! Rute tes ini berfungsi!';
});


Route::get('/', function () {
    return view('welcome');
});

// Route untuk Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified']) // Pastikan hanya user terautentikasi
    ->name('dashboard'); // Nama route standar dari Breeze/Jetstream

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware(['role:Admin'])->group(function () {
        Route::resource('publications', PublicationController::class);
        // [FIX] Hapus duplikat Route::resource('users', ...)
        Route::resource('users', UserController::class);
        Route::patch('/users/{user}/status', [UserController::class, 'updateStatus'])->name('users.updateStatus');
        Route::get('publications-export-template', [PublicationController::class, 'exportTemplate'])->name('publications.exportTemplate');
        Route::post('publications-import', [PublicationController::class, 'import'])->name('publications.import');
    });

    Route::middleware(['role:Penyusun|Pemeriksa|Pimpinan'])->group(function () {
        Route::resource('sprp', SprpController::class)->only(['index', 'create', 'store', 'show']);
        Route::patch('/sprp/{sprp}/update-nomor', [SprpController::class, 'updateNomor'])->name('sprp.updateNomor');
    });

    // --- GRUP PENGAJUAN PUBLIKASI (DIRAPIKAN) ---

    // CRUD
    Route::resource('pengajuan_publikasi', SubmissionPublicationController::class)
        ->parameters(['pengajuan_publikasi' => 'submission']) // <-- Parameter adalah {submission}
        ->names('pengajuan_publikasi');

    // Update Status
    // [FIX] Ganti ke 'patch' dan gunakan parameter '{submission}' agar konsisten
    Route::patch('/pengajuan_publikasi/{submission}/update-status', [SubmissionPublicationController::class, 'updateStatus'])
        ->middleware(['role:Pemeriksa|Admin'])
        ->name('pengajuan_publikasi.updateStatus');

    // Komentar
    // [FIX] Gunakan parameter '{submission}' agar konsisten
    Route::get('/pengajuan_publikasi/{submission}/comment', [SubmissionPublicationController::class, 'comment'])->name('pengajuan_publikasi.comment');
    Route::post('/pengajuan_publikasi/{submission}/comment', [SubmissionPublicationController::class, 'storeComment'])->name('pengajuan_publikasi.storeComment');

    // Panduan
    Route::get('/panduan_publikasi', function () { // <-- Rute 'panduan' sudah benar
        return view('panduan_publikasi.index');
    })->name('panduan.index');

    // Route::middleware('auth')->group(function () {
    //     Route::get('/spnsr', [SpnsrController::class, 'index'])->name('spnsr.index');
    //     Route::post('/spnsr/generate', [SpnsrController::class, 'generate'])->name('spnsr.generate');
    // });
    Route::get('/cetak-spnsr', [SpnsrController::class, 'create'])->name('spnsr.create');

    // PERBAIKI BAGIAN INI: Pastikan ini adalah 'generatePDF'
    Route::post('/cetak-spnsr', [SpnsrController::class, 'generatePDF'])->name('spnsr.generate');
});

require __DIR__ . '/auth.php';
