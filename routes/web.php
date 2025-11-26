<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use App\Http\Controllers\SprpController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubmissionPublicationController;
use App\Http\Controllers\SpnsrController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BrsController;
use Google\Client;


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
    ->middleware(['auth', 'verified', 'role:Admin|Pimpinan|Pemeriksa|Tamu|Penyusun'])
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
        Route::resource('catalog', CatalogController::class);
        Route::resource('users', UserController::class);
        Route::patch('/users/{user}/status', [UserController::class, 'updateStatus'])->name('users.updateStatus');
        Route::get('catalog-export-template', [CatalogController::class, 'exportTemplate'])->name('catalog.exportTemplate');
        Route::post('catalog-import', [CatalogController::class, 'import'])->name('catalog.import');
    });
    // Rute untuk menyimpan publikasi baru via AJAX dari modal
    Route::post('/catalog/store-ajax', [App\Http\Controllers\CatalogController::class, 'storeAjax'])
        ->name('catalog.storeAjax')
        ->middleware('auth'); // Pastikan ini dilindungi

    /*
    |--------------------------------------------------------------------------
    | PENYUSUN, PEMERIKSA, PIMPINAN - SPRP
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:Penyusun|Pemeriksa|Pimpinan|Tamu'])->group(function () {
        Route::resource('sprp', SprpController::class)->only(['index', 'create', 'store', 'show']);
        Route::patch('/sprp/{sprp}/update-nomor', [SprpController::class, 'updateNomor'])->name('sprp.updateNomor');
    });

    /*
    |--------------------------------------------------------------------------
    | PENGAJUAN PUBLIKASI (PENYUSUN & PEMERIKSA)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:Penyusun|Pemeriksa|Pimpinan|Tamu'])->group(function () {

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
    Route::post('/pengajuan-publikasi/update-status/{id}', [SubmissionPublicationController::class, 'updateStatus'])
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
    Route::resource('brs', BrsController::class)->middleware('auth');
    // HARUS SEPERTI INI:
    Route::get('/brs/{brs}', [BrsController::class, 'show'])->name('brs.show');



});
Route::get('/connect-google', function () {
    $client = new Client();
    $client->setClientId(env('GOOGLE_CLIENT_ID'));
    $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
    $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));

    // PENTING: Meminta akses "offline" agar dapat Refresh Token
    $client->setAccessType('offline');
    // PENTING: Memaksa persetujuan agar Google memberi refresh token baru
    $client->setPrompt('select_account consent');

    // Izin yang diminta (Upload & Manage file)
    $client->addScope("https://www.googleapis.com/auth/drive");

    return redirect($client->createAuthUrl());
});

// 2. Rute Callback (Tempat kita terima Token)
Route::get('/google/callback', function (Request $request) {
    $client = new Client();
    $client->setClientId(env('GOOGLE_CLIENT_ID'));
    $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
    $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));

    if (!$request->has('code')) {
        return 'Gagal: Tidak ada kode otorisasi.';
    }

    try {
        // Tukar kode otorisasi dengan Token Asli
        $token = $client->fetchAccessTokenWithAuthCode($request->code);

        // Tampilkan hasilnya biar bisa dicopy
        return response()->json([
            'PESAN' => 'Sukses! Copy refresh_token di bawah ini ke file .env kamu.',
            'refresh_token' => $token['refresh_token'] ?? 'ERROR: Refresh token tidak muncul. Coba revoke akses di akun Google dulu.',
            'access_token' => $token['access_token'], // Ini cuma tahan 1 jam
            'expires_in' => $token['expires_in']
        ]);
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

// --- ROUTE AUTH DEFAULT (LOGIN, RESET PASSWORD DLL) ---
require __DIR__ . '/auth.php';
