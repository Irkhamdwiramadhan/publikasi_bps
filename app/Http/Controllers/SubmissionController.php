<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\Publication;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubmissionController extends Controller
{
    /**
     * Menampilkan halaman daftar pengajuan (untuk nanti).
     */
public function index()
{
    $submissions = Submission::with(['publication', 'user'])->latest()->get();
    return view('submissions.index', compact('submissions'));
}



    /**
     * Menampilkan formulir SPRP.
     */
    public function create()
    {
        // Ambil daftar publikasi yang BELUM pernah diajukan
        $availablePublications = Publication::whereDoesntHave('submission')->get();

        // Ambil daftar pegawai untuk dropdown 'pembuat cover'
        $pegawai = User::whereHas('roles', fn($q) => $q->where('name', 'Pegawai'))->get();

        return view('submissions.create', compact('availablePublications', 'pegawai'));
    }

    /**
     * Menyimpan data dari formulir SPRP.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'publication_id' => 'required|exists:publications,id',
            'jumlah_romawi' => 'nullable|string|max:10',
            'jumlah_arab' => 'nullable|string|max:10',
            'kategori' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:50',
            'issn' => 'nullable|string|max:50',
            'pembuat_cover_id' => 'nullable|exists:users,id',
            'orientasi' => 'required|string',
            'diterbitkan_untuk' => 'required|string',
            'ukuran_kertas' => 'required|string',
        ]);
        
        // Tambahkan user_id dari pengguna yang sedang login
        $validatedData['user_id'] = Auth::id();
        // Set status awal
        $validatedData['status'] = 'submitted';

        Submission::create($validatedData);

        return redirect()->route('submissions.index')->with('success', 'SPRP berhasil diajukan.');
    }
    
    // -- Fungsi edit, update, destroy, dll. akan kita isi nanti --

    public function show(Submission $submission)
    {
        //
    }
    
    public function edit(Submission $submission)
    {
        //
    }

    public function update(Request $request, Submission $submission)
    {
        //
    }

    public function destroy(Submission $submission)
    {
        //
    }
}

