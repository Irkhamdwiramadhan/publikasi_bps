<?php

namespace App\Http\Controllers;

use App\Models\SubmissionPublication;
use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SubmissionPublicationController extends Controller
{
    /**
     * Tampilkan daftar pengajuan publikasi.
     */
    public function index()
    {
        // REVISI: Tambahkan eager load 'publication.spnsrSubmission'
        // Ini akan mengambil pengajuan, lalu master publikasinya, 
        // lalu SPNSR yang terkait dengan master publikasi tsb.
        $query = SubmissionPublication::with([
            'user', 
            'publication', 
            'publication.spnsrSubmission'
        ]);

        // Logika "Pintar" Anda untuk memfilter data (sudah benar)
        if (Auth::user()->hasRole('Penyusun') && !Auth::user()->hasAnyRole(['Pemeriksa', 'Admin'])) {
            $query->where('user_id', Auth::id());
        }

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
    
    // Method updateStatus
    public function updateStatus(Request $request, $id)
    {
        $submission = SubmissionPublication::findOrFail($id);
        $submission->status = $request->status;
        $submission->save();

        return response()->json(['success' => true]);
    }

    // Method comment
    public function comment(SubmissionPublication $submission)
    {
        $submission->load('comments.user', 'publication'); // Eager load relasi
        return view('pengajuan_publikasi.comment', ['submission' => $submission]);
    }

    // Method storeComment
    public function storeComment(Request $request, SubmissionPublication $submission)
    {
        $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        $submission->comments()->create([
            'body' => $request->body,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('pengajuan_publikasi.comment', $submission->id)
            ->with('success', 'Komentar berhasil dikirim.');
    }

    // Method edit
    public function edit(SubmissionPublication $submission)
    {
        $submission->load(['publication', 'user']);

        $submittedPublicationIds = SubmissionPublication::where('id', '!=', $submission->id)
            ->pluck('publication_id');

        $publications = Publication::whereNotIn('id', $submittedPublicationIds)
            ->select('id', 'title_ind', 'publication_type')
            ->orderBy('title_ind')
            ->get();

        return view('pengajuan_publikasi.edit', [
            'submission' => $submission,
            'publications' => $publications
        ]);
    }

    // Method update
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
