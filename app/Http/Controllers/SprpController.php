<?php

namespace App\Http\Controllers;

use App\Models\Sprp;
use App\Models\Publication;
use App\Models\User; // Masih dibutuhkan untuk relasi 'user_id' (penyusun)
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Dibutuhkan untuk mengisi user_id

class SprpController extends Controller
{
    /**
     * Menampilkan daftar (halaman index) dari SPRP.
     * Ini adalah halaman alur kerja (workflow) utama.
     */
    public function index()
    {
        // Ambil data SPRP dengan relasi ke Publikasi
        // Halaman index (workflow) akan membutuhkan data publikasi
        $sprps = Sprp::with(['publication'])->latest()->paginate(10);
        
        return view('sprp.index', compact('sprps'));
    }

    /**
     * Menampilkan form untuk membuat SPRP baru.
     */
    public function create()
    {
        // Ambil data untuk dropdown 'publication_id'
        $publications = Publication::orderBy('title_ind')->get();
        
        // [REVISI] Data $users tidak lagi diperlukan untuk form
        
        return view('sprp.create', [
            'publications' => $publications,
            // [REVISI] 'users' => $users telah dihapus
        ]);
    }

    /**
     * Menyimpan data SPRP baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi data form
        $validatedData = $request->validate([
            'publication_id' => 'required|exists:publications,id',
            'jumlah_romawi' => 'nullable|string|max:10',
            'jumlah_arab' => 'nullable|string|max:10',
            'kategori' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:50',
            'issn' => 'nullable|string|max:50',
            
            // [REVISI] Mengubah validasi dari 'pembuat_cover_id' ke 'pembuat_cover'
            'pembuat_cover' => 'required|string|max:255', 
            
            'orientasi' => 'required|string',
            'diterbitkan_untuk' => 'required|string',
            'ukuran_kertas' => 'required|string',
        ]);

        // Tambahkan ID user yang sedang login (Penyusun)
        $validatedData['user_id'] = Auth::id();
        
        // Tambahkan status default (sesuai migrasi)
        $validatedData['status'] = 'Sedang diperiksa'; 

        // Buat data SPRP baru
        Sprp::create($validatedData);

        return redirect()->route('sprp.index')->with('success', 'Pengajuan SPRP berhasil disimpan.');
    }

    /**
     * Menampilkan detail dari satu SPRP (belum diimplementasikan).
     */
   public function show(Sprp $sprp)
    {
        // [INI YANG BENAR]
        // Tampilkan view 'sprp.show' dan kirim data $sprp
        
        // Kita Eager Load relasi 'user' dan 'publication'
        // agar tidak error saat dipanggil di view
        $sprp->load(['user', 'publication']);

        return view('sprp.show', compact('sprp'));
    }

    /**
     * Menampilkan form untuk mengedit SPRP.
     */
    public function edit(Sprp $sprp)
    {
        // Ambil data untuk dropdown 'publication_id'
        $publications = Publication::orderBy('title_ind')->get();

        // [REVISI] Data $users tidak lagi diperlukan untuk form

        return view('sprp.edit', [
            'sprp' => $sprp,
            'publications' => $publications,
            // [REVISI] 'users' => $users telah dihapus
        ]);
    }

    /**
     * Memperbarui data SPRP di database.
     */
    public function update(Request $request, Sprp $sprp)
    {
        // Validasi data form
        $validatedData = $request->validate([
            'publication_id' => 'required|exists:publications,id',
            'jumlah_romawi' => 'nullable|string|max:10',
            'jumlah_arab' => 'nullable|string|max:10',
            'kategori' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:50',
            'issn' => 'nullable|string|max:50',
            
            // [REVISI] Mengubah validasi dari 'pembuat_cover_id' ke 'pembuat_cover'
            'pembuat_cover' => 'required|string|max:255',
            
            'orientasi' => 'required|string',
            'diterbitkan_untuk' => 'required|string',
            'ukuran_kertas' => 'required|string',
        ]);

        // Update data SPRP
        $sprp->update($validatedData);

        return redirect()->route('sprp.index')->with('success', 'Data SPRP berhasil diperbarui.');
    }

    /**
     * Menghapus data SPRP dari database.
     */
    public function destroy(Sprp $sprp)
    {
        try {
            $sprp->delete();
            return redirect()->route('sprp.index')->with('success', 'Data SPRP berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('sprp.index')->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Fungsi khusus untuk (Pemeriksa) mengupdate No. Publikasi Final.
     * Ini dipanggil dari modal di halaman index.
     */
    public function updateNomor(Request $request, Sprp $sprp)
    {
        // Pastikan hanya role tertentu (misal: pemeriksa) yang bisa
        // Anda bisa tambahkan Gate/Policy di sini nanti
        // if (Auth::user()->cannot('updateNomor', $sprp)) {
        //     abort(403);
        // }

        $request->validate(['nomor_publikasi' => 'required|string|max:50']);
        
        $sprp->nomor_publikasi_final = $request->nomor_publikasi;
        
        // Mungkin Anda juga ingin mengubah status di sini?
        // $sprp->status = 'Disetujui'; 
        
        $sprp->save();

        return redirect()->route('sprp.index')->with('success', 'Nomor publikasi berhasil diperbarui.');
    }
}

