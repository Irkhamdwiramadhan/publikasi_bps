<?php

namespace App\Http\Controllers;

use App\Models\SubmissionPublication;
use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // [REVISI 1] Import Trait

class SubmissionPublicationController extends Controller
{
    /**
     * Tampilkan daftar pengajuan publikasi.
     */
    public function index()
    {
        $query = SubmissionPublication::with(['publication', 'user']);

        // Logika "Pintar":
        // [REVISI] Ejaan yang benar adalah hasAnyRole (dengan R)
        if (Auth::user()->hasRole('Penyusun') && !Auth::user()->hasAnyRole(['Pemeriksa', 'Admin'])) {
            $query->where('user_id', Auth::id());
        }

        // Jika dia 'Pemeriksa' atau 'Admin', if di atas akan diabaikan
        // dan semua data akan ditampilkan.

        $submissions = $query->latest()->paginate(10);

        return view('pengajuan_publikasi.index', compact('submissions'));
    }




    /**
     * Tampilkan form tambah publikasi.
     */
    public function create()
    {
        $publications = Publication::select('id', 'title_ind', 'publication_type')->get();
        return view('pengajuan_publikasi.create', compact('publications'));
    }

    /**
     * Simpan data publikasi baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'publication_id' => 'required|exists:publications,id',
            'fungsi_pengusul' => 'required|string|max:255',
            'tautan_publikasi' => 'nullable|url',
            'spnrs_ketua_tim' => 'nullable|url',
        ]);

        SubmissionPublication::create([
            'publication_id' => $request->publication_id,
            'user_id' => Auth::id(),
            'fungsi_pengusul' => $request->fungsi_pengusul,
            'tautan_publikasi' => $request->tautan_publikasi,
            'spnrs_ketua_tim' => $request->spnrs_ketua_tim,
            'status' => 'draft',
        ]);

        return redirect()->route('pengajuan_publikasi.index')->with('success', 'Publikasi berhasil diajukan!');
    }
    // SubmissionPublicationController.php
    public function updateStatus(Request $request, $id)
    {
        $submission = SubmissionPublication::findOrFail($id);
        $submission->status = $request->status;
        $submission->save();

        return response()->json(['success' => true]);
    }


    public function comment(SubmissionPublication $submission)
    {
        $submission->load('comments.user', 'publication'); // Eager load relasi
        return view('pengajuan_publikasi.comment', ['submission' => $submission]);
    }

    /**
     * [REVISI TOTAL] Menggunakan Route Model Binding dan Relasi
     */
    public function storeComment(Request $request, SubmissionPublication $submission)
    {
        // [REVISI] Validasi 'body' (sesuai nama field di comment.blade.php)
        $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        // [REVISI] Menggunakan relasi 'comments()' yang sudah kita buat
        // Ini jauh lebih bersih dan otomatis mengisi foreign key.
        $submission->comments()->create([
            'body' => $request->body,       // dari <textarea name="body">
            'user_id' => Auth::id(),     // user yang sedang login
        ]);

        return redirect()->route('pengajuan_publikasi.comment', $submission->id)
            ->with('success', 'Komentar berhasil dikirim.');
    }

    public function edit(SubmissionPublication $submission)
    {
        // [FIX 1] Eager load relasi 'publication' dan 'user'
        // Ini untuk memperbaiki error 500 (property of non-object) di view Blade
        $submission->load(['publication', 'user']);

        // [FIX 2] Logika query yang disarankan sebelumnya
        // Ambil ID publikasi yang sudah diajukan, KECUALI yang sedang diedit ini.
        $submittedPublicationIds = SubmissionPublication::where('id', '!=', $submission->id)
            ->pluck('publication_id');

        // Ambil semua publikasi yang TIDAK ada di daftar itu.
        $publications = Publication::whereNotIn('id', $submittedPublicationIds)
            ->select('id', 'title_ind', 'publication_type')
            ->orderBy('title_ind')
            ->get();

        return view('pengajuan_publikasi.edit', [
            'submission' => $submission,
            'publications' => $publications
        ]);
    }

    public function update(Request $request, SubmissionPublication $submission)
    {
        $request->validate([
            'publication_id' => 'required|exists:publications,id',
            'fungsi_pengusul' => 'required|string|max:255',
            'tautan_publikasi' => 'nullable|url',
            'spnrs_ketua_tim' => 'nullable|url',
        ]);

        $submission->update([
            'publication_id' => $request->publication_id,
            'fungsi_pengusul' => $request->fungsi_pengusul,
            'tautan_publikasi' => $request->tautan_publikasi,
            'spnrs_ketua_tim' => $request->spnrs_ketua_tim,
        ]);

        return redirect()->route('pengajuan_publikasi.index')
            ->with('success', 'Pengajuan berhasil diperbarui.');
    }
}
