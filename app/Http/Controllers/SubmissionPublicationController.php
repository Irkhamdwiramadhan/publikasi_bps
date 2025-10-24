<?php

namespace App\Http\Controllers;

use App\Models\SubmissionPublication;
use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;

class SubmissionPublicationController extends Controller
{
    /**
     * Tampilkan daftar pengajuan publikasi.
     */
  public function index()
{
    $query = SubmissionPublication::with(['publication', 'user'])->latest();

    // Jika user adalah Penyusun, batasi hanya data miliknya
    if (Auth::user()->role === 'Penyusun') {
        $query->where('user_id', Auth::id());
    }

    // Jika user adalah Pemeriksa atau Pimpinan, tampilkan semua data
    $submissions = $query->get();

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


    public function comment(SubmissionPublication $submissionPublication)
    {
        $submissionPublication->load('comments.user', 'publication'); // Eager load relasi
        return view('pengajuan_publikasi.comment', ['submission' => $submissionPublication]);
    }

    /**
     * [REVISI TOTAL] Menggunakan Route Model Binding dan Relasi
     */
    public function storeComment(Request $request, SubmissionPublication $submissionPublication)
    {
        // [REVISI] Validasi 'body' (sesuai nama field di comment.blade.php)
        $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        // [REVISI] Menggunakan relasi 'comments()' yang sudah kita buat
        // Ini jauh lebih bersih dan otomatis mengisi foreign key.
        $submissionPublication->comments()->create([
            'body' => $request->body,       // dari <textarea name="body">
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('pengajuan_publikasi.comment', $submissionPublication->id)
            ->with('success', 'Komentar berhasil dikirim.');
    }
}
