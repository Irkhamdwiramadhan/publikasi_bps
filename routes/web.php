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

// --- RUTE UTAMA LOGIN ---
Route::get('/', function () {
    return view('auth.login');
});

// --- DASHBOARD ---
// Admin, Pimpinan, Pemeriksa diarahkan ke sini setelah login
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'role:Admin|Pimpinan|Pemeriksa'])
    ->name('dashboard');

// --- PENYUSUN DIARAHKAN KE HALAMAN PENGAJUAN PUBLIKASI ---
Route::get('/home', function () {
    return redirect()->route('pengajuan_publikasi.index');
})->middleware(['auth', 'role:Penyusun'])->name('home');

// --- SEMUA ROUTE YANG BUTUH LOGIN ---
Route::middleware('auth')->group(function () {

    // PROFIL USER
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | ADMIN AREA (MASTER DATA)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:Admin'])->group(function () {
        Route::resource('publications', PublicationController::class);
        Route::resource('users', UserController::class);
        Route::patch('/users/{user}/status', [UserController::class, 'updateStatus'])->name('users.updateStatus');
        Route::get('publications-export-template', [PublicationController::class, 'exportTemplate'])->name('publications.exportTemplate');
        Route::post('publications-import', [PublicationController::class, 'import'])->name('publications.import');
    });

    /*
    |--------------------------------------------------------------------------
    | PENYUSUN, PEMERIKSA, PIMPINAN - SPRP
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:Penyusun|Pemeriksa|Pimpinan'])->group(function () {
        Route::resource('sprp', SprpController::class)->only(['index', 'create', 'store', 'show']);
        Route::patch('/sprp/{sprp}/update-nomor', [SprpController::class, 'updateNomor'])->name('sprp.updateNomor');
    });

    /*
    |--------------------------------------------------------------------------
    | PENGAJUAN PUBLIKASI (PENYUSUN & PEMERIKSA)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:Penyusun|Pemeriksa'])->group(function () {

        // CRUD utama
        Route::resource('pengajuan_publikasi', SubmissionPublicationController::class)
            ->parameters(['pengajuan_publikasi' => 'submission'])
            ->names('pengajuan_publikasi');

        // Komentar (keduanya bisa balas)
        Route::get('/pengajuan_publikasi/{submission}/comment', [SubmissionPublicationController::class, 'comment'])
            ->name('pengajuan_publikasi.comment');
        Route::post('/pengajuan_publikasi/{submission}/comment', [SubmissionPublicationController::class, 'storeComment'])
            ->name('pengajuan_publikasi.storeComment');
    });

    // Pemeriksa (dan Admin) hanya boleh update status publikasi
    Route::patch('/pengajuan_publikasi/{submission}/update-status', [SubmissionPublicationController::class, 'updateStatus'])
        ->middleware(['role:Pemeriksa|Admin'])
        ->name('pengajuan_publikasi.updateStatus');

    /*
    |--------------------------------------------------------------------------
    | PANDUAN PUBLIKASI
    |--------------------------------------------------------------------------
    */
    Route::get('/panduan_publikasi', function () {
        return view('panduan_publikasi.index');
    })->name('panduan.index');

    /*
    |--------------------------------------------------------------------------
    | SPNSR (SEMUA ROLE, DENGAN PEMBATASAN DI DALAM CONTROLLER)
    |--------------------------------------------------------------------------
    */
    Route::prefix('spnsr')->name('spnsr.')->group(function () {

        // 1. Halaman index
        Route::get('/', [SpnsrController::class, 'index'])->name('index');

        // 2. Form tambah (non-Pemimpin)
        Route::get('/create', [SpnsrController::class, 'create'])
            ->middleware('role:Admin|Penyusun|Pemeriksa')
            ->name('create');

        // 3. Simpan pengajuan (non-Pemimpin)
        Route::post('/', [SpnsrController::class, 'store'])
            ->middleware('role:Admin|Penyusun|Pemeriksa')
            ->name('store');

        // 4. Unduh draft PDF (bisa diakses semua role)
        Route::get('/{submission}/pdf-draft', [SpnsrController::class, 'generatePdfDraft'])->name('pdf.draft');

        // 5. Upload PDF bertanda tangan (hanya Pemimpin)
        Route::post('/{submission}/upload-signed', [SpnsrController::class, 'uploadSignedPdf'])
            ->middleware('role:Pimpinan')
            ->name('upload.signed');

        // 6. Unduh PDF bertanda tangan (semua yang berhak)
        Route::get('/{submission}/download-signed', [SpnsrController::class, 'downloadSignedPdf'])->name('download.signed');
    });
});

// --- ROUTE AUTH DEFAULT (LOGIN, RESET PASSWORD DLL) ---
require __DIR__ . '/auth.php';
