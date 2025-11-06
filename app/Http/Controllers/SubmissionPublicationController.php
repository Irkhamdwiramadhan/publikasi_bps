<?php

namespace App\Http\Controllers;

use App\Models\SubmissionPublication;
use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\SubmissionComment;

class SubmissionPublicationController extends Controller
{
   public function index()
{
    $submissions = SubmissionPublication::with('user', 'publication', 'comments')
        ->latest()
        ->paginate(10);

    $user = Auth::user();
    $userRoles = $user->getRoleNames();
    $role = $userRoles->contains('Penyusun') ? 'Penyusun' : ($userRoles->contains('Pemeriksa') ? 'Pemeriksa' : $userRoles->first() ?? 'Penyusun');

    // Hitung jumlah komentar belum dibaca hanya dari pihak lain
    $submissions->getCollection()->transform(function ($item) use ($role) {
        $roleToCheck = $role === 'Penyusun' ? 'Pemeriksa' : 'Penyusun';

        $item->unread_count = $item->comments()
            ->where('role', $roleToCheck)
            ->where('is_read', false)
            ->count();

        return $item;
    });

    return view('pengajuan_publikasi.index', compact('submissions'));
}

    public function create()
    {
        $publications = Publication::select('id', 'title_ind', 'publication_type')->get();
        return view('pengajuan_publikasi.create', compact('publications'));
    }

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

    public function updateStatus(Request $request, $id)
    {
        $submission = SubmissionPublication::findOrFail($id);
        $submission->status = $request->status;
        $submission->save();

        return response()->json(['success' => true]);
    }

    public function comment(SubmissionPublication $submission)
{
    $user = auth()->user();

    // Ambil semua role user via Spatie
    $roles = $user->getRoleNames();

    // Pilih role utama untuk logika komentar (Penyusun > Pemeriksa)
    $role = $roles->contains('Penyusun') ? 'Penyusun' : ($roles->contains('Pemeriksa') ? 'Pemeriksa' : $roles->first() ?? 'Penyusun');

    // Load komentar dan publikasi
    $submission->load(['comments.user', 'publication']);

    // Tentukan role pihak lain
    $roleToCheck = $role === 'Penyusun' ? 'Pemeriksa' : 'Penyusun';

    // Tandai komentar dari pihak lain sebagai sudah dibaca
    $submission->comments()
        ->where('role', $roleToCheck)
        ->where('is_read', false)
        ->update(['is_read' => true]);

    // Hitung unread count
    $unreadCount = $submission->comments()
        ->where('role', $roleToCheck)
        ->where('is_read', false)
        ->count();

    return view('pengajuan_publikasi.comment', compact('submission', 'unreadCount'));
}

public function storeComment(Request $request, SubmissionPublication $submission)
{
    $request->validate([
        'body' => 'required|string|max:2000',
    ]);

    $user = auth()->user();

    // Ambil semua role user
    $roles = $user->getRoleNames();

    // Pilih role utama untuk komentar (Penyusun > Pemeriksa)
    $role = $roles->contains('Penyusun') ? 'Penyusun' : ($roles->contains('Pemeriksa') ? 'Pemeriksa' : $roles->first() ?? 'Penyusun');

    // Buat komentar baru tanpa mass assignment (lebih aman)
    $comment = new \App\Models\SubmissionComment();
    $comment->body = $request->body;
    $comment->user_id = $user->id;
    $comment->role = $role; // role pasti terisi
    $comment->is_read = false;

    // Simpan komentar ke submission
    $submission->comments()->save($comment);

    return redirect()->route('pengajuan_publikasi.comment', $submission->id)
                     ->with('success', 'Komentar berhasil dikirim.');
}






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
