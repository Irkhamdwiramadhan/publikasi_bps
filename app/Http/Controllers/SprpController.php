<?php

namespace App\Http\Controllers;

// ▼▼▼ PASTIKAN SEMUA IMPORT INI ADA ▼▼▼
use App\Models\Sprp;
use App\Models\Catalog;
use App\Models\SubmissionPublication; // Ini adalah "jantung" data
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Wajib untuk Transaksi Database

class SprpController extends Controller
{
    /**
     * Menampilkan daftar (halaman index) dari SPRP.
     * (Anda mungkin perlu merevisi eager load di sini nanti)
     */
    public function index()
    {
        // REVISI: Eager load relasi baru
        $sprps = Sprp::with(['submissionPublication.catalog', 'user'])
            ->latest()
            ->paginate(10);

        return view('sprp.index', compact('sprps'));
    }

    /**
     * Menampilkan form untuk membuat SPRP baru.
     */
    public function create()
    {
        // [REVISI] Ambil data katalog untuk dropdown
        $catalogs = Catalog::orderBy('nomor_katalog')->get();

        return view('sprp.create', [
            'catalogs' => $catalogs,
        ]);
    }

    /**
     * Menyimpan data SPRP baru ke database.
     * [REVISI TOTAL] - Logika "Satu Form, Dua Tabel"
     */
    /**
     * Menyimpan data SPRP baru ke database.
     * [REVISI KOREKSI] - Mengembalikan logika 'nomor_publikasi_final'
     */
    public function store(Request $request)
    {
        // 1. Validasi
        $validatedData = $request->validate([
            'type_publikasi'    => 'required|string|max:50',
            'judul_publikasi'   => 'required|string|max:255',
            'judul_eng'         => 'nullable|string|max:255',
            'estimasi_rilis'    => 'required|date',
            'bahasa'            => 'required|string|max:50',
            'catalog_id'        => 'required|exists:catalogs,id',
            'issn'              => 'nullable|string|max:50',
            'isbn'              => 'nullable|string|max:50',
            'fungsi_pengusul'   => 'required|string|max:255',
            'link_publikasi'    => 'nullable|url|max:255',
            'jumlah_romawi'     => 'nullable|string|max:10',
            'jumlah_arab'       => 'nullable|string|max:10',
            'diterbitkan_untuk' => 'required|string',
            'pembuat_cover'     => 'required|string',
            'orientasi'         => 'required|string',
            'ukuran_kertas'     => 'required|string',
            'kategori'          => 'required|string', // <-- 1. TAMBAHKAN VALIDASI KATEGORI
        ]);
        
        $userId = Auth::id();

        try {
            DB::beginTransaction();

            // 2. Simpan ke 'submission_publications'
            $submission = SubmissionPublication::create([
                'user_id'           => $userId,
                'judul_publikasi'   => $validatedData['judul_publikasi'], 
                'type_publikasi'    => $validatedData['type_publikasi'],
                'judul_eng'         => $validatedData['judul_eng'],
                'estimasi_rilis'    => $validatedData['estimasi_rilis'],
                'bahasa'            => $validatedData['bahasa'],
                'catalog_id'        => $validatedData['catalog_id'],
                'issn'              => $validatedData['issn'],
                'isbn'              => $validatedData['isbn'],
                'fungsi_pengusul'   => $validatedData['fungsi_pengusul'],
                'tautan_publikasi'  => $validatedData['link_publikasi'], 
            ]);

            // 3. Logika nomor publikasi final (Sudah Benar)
            $prefix = '33280';
            $tahun = date('y');
            $tahunSekarang = date('Y');
            $urutanTahunIni = \App\Models\Sprp::whereYear('created_at', $tahunSekarang)->count();
            $nomorUrut = $urutanTahunIni + 1;
            $nomorUrutPadded = str_pad($nomorUrut, 3, '0', STR_PAD_LEFT);
            $nomorPublikasiFinal = $prefix . '.' . $tahun . $nomorUrutPadded;

            // 4. Simpan ke tabel 'sprps'
            Sprp::create([
                'user_id'                   => $userId,
                'submission_publication_id' => $submission->id,
                'kategori'                  => $validatedData['kategori'], // <-- 2. TAMBAHKAN PENYIMPANAN KATEGORI
                'jumlah_romawi'             => $validatedData['jumlah_romawi'],
                'jumlah_arab'               => $validatedData['jumlah_arab'],
                'diterbitkan_untuk'         => $validatedData['diterbitkan_untuk'],
                'pembuat_cover'             => $validatedData['pembuat_cover'],
                'orientasi'                 => $validatedData['orientasi'],
                'ukuran_kertas'             => $validatedData['ukuran_kertas'],
                'status'                    => 'Sedang diperiksa',
                'nomor_publikasi_final'     => $nomorPublikasiFinal,
            ]);

            DB::commit();
            return redirect()->route('pengajuan_publikasi.index')->with('success', 'Pengajuan SPRP berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();


            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage())->withInput();
        }
    }

    // ... (Method show, edit, update, destroy perlu direvisi nanti) ...
}
