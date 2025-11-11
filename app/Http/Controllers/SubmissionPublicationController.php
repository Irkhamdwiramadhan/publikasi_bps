<?php

namespace App\Http\Controllers;

use App\Models\SubmissionPublication;
use App\Models\Catalog; // [REVISI] Import Catalog
use App\Models\User;
use App\Models\SubmissionComment;
// use App\Models\Publication; // [REVISI] Sudah tidak dipakai
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sprp; // [REVISI] Import model Sprp

class SubmissionPublicationController extends Controller
{
    /**
     * [REVISI TOTAL]
     * Menampilkan halaman index (monitoring) dengan data gabungan.
     */
    public function index(Request $request)
{
    // 1. Validasi input
    $request->validate([
        'per_page' => 'nullable|integer|in:10,25,50',
        'search'   => 'nullable|string|max:100',
        'tahun'    => 'nullable|integer|min:1900|max:' . now()->year,
    ]);

    $perPage = $request->input('per_page', 10);
    $search = $request->input('search');
    $tahun = $request->input('tahun');

    $user = Auth::user();
    $userRoles = $user->getRoleNames();
    $role = $userRoles->contains('Penyusun') ? 'Penyusun' : ($userRoles->contains('Pemeriksa') ? 'Pemeriksa' : $userRoles->first() ?? 'Penyusun');

    // 2. Query utama dengan relasi
    $query = SubmissionPublication::with([
        'user',
        'catalog',
        'sprp',
        'comments'
    ]);

    // 3. Jika role Penyusun, tampilkan hanya publikasi miliknya
    if ($role === 'Penyusun') {
        $query->where('user_id', $user->id);
    }

    // 4. Filter pencarian
    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('judul_publikasi', 'like', '%' . $search . '%')
              ->orWhere('judul_eng', 'like', '%' . $search . '%');
        });
    }

    // 5. Filter tahun (hanya jika ada)
    if ($request->filled('tahun')) {
        $query->whereRaw('YEAR(COALESCE(estimasi_rilis, created_at)) = ?', [$tahun]);
    }

    // 6. Urutkan dan paginasi
    $submissions = $query->latest('created_at')
        ->paginate($perPage)
        ->withQueryString();

    // 7. Hitung komentar belum dibaca
    $submissions->getCollection()->transform(function ($item) use ($role) {
        $roleToCheck = $role === 'Penyusun' ? 'Pemeriksa' : 'Penyusun';
        $item->unread_count = $item->comments()
            ->where('role', $roleToCheck)
            ->where('is_read', false)
            ->count();
        return $item;
    });

    // 8. Kirim ke view
    return view('pengajuan_publikasi.index', [
        'submissions' => $submissions,
        'filters' => $request->only(['search', 'per_page', 'tahun']),
    ]);
}



    /**
     * [REVISI] Method ini tidak dipakai lagi.
     * Pengajuan baru dibuat melalui SprpController@store
     */
    public function create()
    {
        return redirect()->route('pengajuan_publikasi.index')
            ->with('info', 'Untuk membuat pengajuan baru, silakan isi dari menu "Tambah SPRP".');
    }

    /**
     * [REVISI] Method ini tidak dipakai lagi.
     * Pengajuan baru disimpan melalui SprpController@store
     */
    public function store(Request $request)
    {
        // Dibiarkan kosong atau di-redirect
        return redirect()->route('pengajuan_publikasi.index');
    }

    /**
     * [REVISI TOTAL]
     * Menampilkan form untuk mengedit data inti publikasi.
     * Ini akan dipakai oleh Pemeriksa/Admin.
     */
    public function edit(SubmissionPublication $submission)
    {
        // Load relasi yang ada
        $submission->load(['user', 'catalog']);

        // Ambil data katalog untuk dropdown
        $catalogs = Catalog::orderBy('nomor_katalog')->get();

        return view('pengajuan_publikasi.edit', [
            'submission' => $submission,
            'catalogs' => $catalogs
        ]);
    }

    /**
     * [REVISI TOTAL]
     * Memperbarui data inti publikasi.
     */
    public function update(Request $request, SubmissionPublication $submission)
    {
        // Validasi semua field baru
        $validatedData = $request->validate([
            'type_publikasi'  => 'required|string|max:50',
            'judul_publikasi' => 'required|string|max:255',
            'judul_eng'       => 'nullable|string|max:255',
            'estimasi_rilis'  => 'required|date',
            'bahasa'          => 'required|string|max:50',
            'catalog_id'      => 'required|exists:catalogs,id',
            'issn'            => 'nullable|string|max:50',
            'isbn'            => 'nullable|string|max:50',
            'fungsi_pengusul' => 'required|string|max:255',
            'tautan_publikasi'  => 'nullable|url|max:255',
            'link_publikasi_final' => 'nullable|url|max:255', // Kolom baru
            'spnrs_ketua_tim' => 'nullable|url', // Kolom lama Anda
        ]);

        // Update data
        $submission->update($validatedData);

        return redirect()->route('pengajuan_publikasi.index')
            ->with('success', 'Data pengajuan berhasil diperbarui.');
    }


    // ===============================================
    // FUNGSI LAMA (TETAP DIPERTAHANKAN)
    // ===============================================

    /**
     * (TIDAK BERUBAH)
     * Mengupdate status via AJAX.
     */
    public function updateStatus(Request $request, $id)
    {
        $submission = SubmissionPublication::findOrFail($id);
        $submission->status = $request->status;
        $submission->save();

        return response()->json(['success' => true]);
    }

    /**
     * (TIDAK BERUBAH)
     * Menampilkan halaman komentar.
     */
    public function comment(SubmissionPublication $submission)
    {
        $user = auth()->user();
        $roles = $user->getRoleNames();
        $role = $roles->contains('Penyusun') ? 'Penyusun' : ($roles->contains('Pemeriksa') ? 'Pemeriksa' : $roles->first() ?? 'Penyusun');

        // [REVISI KECIL] Hapus 'publication' dari load, ganti ke 'catalog' jika perlu
        $submission->load(['comments.user', 'catalog']);

        $roleToCheck = $role === 'Penyusun' ? 'Pemeriksa' : 'Penyusun';

        $submission->comments()
            ->where('role', $roleToCheck)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $unreadCount = $submission->comments()
            ->where('role', $roleToCheck)
            ->where('is_read', false)
            ->count();

        return view('pengajuan_publikasi.comment', compact('submission', 'unreadCount'));
    }

    /**
     * (TIDAK BERUBAH)
     * Menyimpan komentar baru.
     */
    public function storeComment(Request $request, SubmissionPublication $submission)
    {
        $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        $user = auth()->user();
        $roles = $user->getRoleNames();
        $role = $roles->contains('Penyusun') ? 'Penyusun' : ($roles->contains('Pemeriksa') ? 'Pemeriksa' : $roles->first() ?? 'Penyusun');

        $comment = new \App\Models\SubmissionComment();
        $comment->body = $request->body;
        $comment->user_id = $user->id;
        $comment->role = $role;
        $comment->is_read = false;

        $submission->comments()->save($comment);

        return redirect()->route('pengajuan_publikasi.comment', $submission->id)
            ->with('success', 'Komentar berhasil dikirim.');
    }
    public function show(SubmissionPublication $submission)
    {
        // Muat relasi ke sprp dan catalog
        $submission->load(['sprp', 'catalog']);

        // Ambil data sprp (jika ada)
        $sprp = $submission->sprp;

        // Kirim ke view
        return view('pengajuan_publikasi.show', compact('submission', 'sprp'));
    }
}
