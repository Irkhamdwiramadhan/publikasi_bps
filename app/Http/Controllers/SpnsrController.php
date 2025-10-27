<?php

namespace App\Http\Controllers;

use App\Models\SpnsrSubmission;
use App\Models\User; // Import User model if needed for Penanda Tangan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Publication;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse; // 👈 Tambahkan ini// Kita tetap pakai Carbon untuk tanggal surat hari ini

class SpnsrController extends Controller
{
    /**
     * Menampilkan daftar pengajuan SPNSR.
     */
    public function index()
    {
        $user = Auth::user();
        $submissionsQuery = SpnsrSubmission::with('user')->orderBy('created_at', 'desc');

        // Jika bukan Pemimpin, hanya tampilkan pengajuan milik sendiri
  

        $submissions = $submissionsQuery->paginate(15); // Ambil data dengan paginasi

        return view('spnsr.index', compact('submissions'));
    }

    /**
     * Menampilkan form untuk membuat pengajuan baru.
     */
    public function create()
    {
        // Ambil data publikasi (ID dan Judul IND) untuk dropdown
        // Urutkan berdasarkan judul agar mudah dicari
        $publications = Publication::orderBy('title_ind', 'asc')
                            ->select('id', 'title_ind', 'publication_type') // Ambil kolom yang perlu saja
                            ->get();

        // Kirim data publications ke view
        return view('spnsr.create_simple', compact('publications'));
    }
    /**
     * Menyimpan pengajuan SPNSR baru ke database.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nomor_surat'    => 'required|string|max:100|unique:spnsr_submissions,nomor_surat', // Pastikan nomor unik
            'tanggal_prosa'  => 'required|string|max:255',
            'judul_publikasi'=> 'required|string|max:255',
            'tipe_arc'       => 'required|string|max:50',
            'keterangan'     => 'nullable|string|max:255',
        ]);

        // Tambahkan user_id dan status default
        $validatedData['user_id'] = Auth::id();
        $validatedData['status'] = 'Draft'; // Status awal

        SpnsrSubmission::create($validatedData);

        return redirect()->route('spnsr.index')->with('success', 'Pengajuan SPNSR berhasil disimpan sebagai Draft.');
    }

    /**
     * Menampilkan form edit (untuk Pemimpin mengubah status).
     */
    public function edit(SpnsrSubmission $submission) // Route model binding
    {
         // Hanya Pemimpin yang bisa akses (sudah dilindungi middleware)
        return view('spnsr.edit', compact('submission'));
    }

    /**
     * Memperbarui status pengajuan SPNSR (oleh Pemimpin).
     */
    public function update(Request $request, SpnsrSubmission $submission) // Route model binding
    {
        // Hanya Pemimpin yang bisa akses (sudah dilindungi middleware)
        $validatedData = $request->validate([
            'status' => 'required|string|in:Draft,Disetujui,Ditolak', // Pastikan status valid
        ]);

        $submission->update(['status' => $validatedData['status']]);

        return redirect()->route('spnsr.index')->with('success', 'Status pengajuan SPNSR berhasil diperbarui.');
    }


    /**
     * Menghasilkan PDF berdasarkan data pengajuan.
     * Tanda tangan & stempel muncul kondisional.
     */
    public function generatePDF(SpnsrSubmission $submission) // Route model binding
    {
        // Siapkan data dasar dari submission
        $data = [
            'nomor'         => $submission->nomor_surat,
            'tanggal_prosa' => $submission->tanggal_prosa,
            'judul'         => $submission->judul_publikasi,
            'tipe_arc'      => $submission->tipe_arc,
            'keterangan'    => $submission->keterangan ?? '',
            'tanggal_surat_dibuat' => Carbon::now()->isoFormat('D MMMM YYYY'),
            'submission'    => $submission // Kirim objek submission untuk cek status di view PDF
        ];

        // Ambil data penanda tangan (Kepala BPS)
        // Idealnya ini diambil dari user dengan role 'Pemimpin' atau konfigurasi
        $data['penanda_tangan'] = [
            'nama'      => 'Bambang Wahyu Ponco Aji, SST, M.Si.', // Ganti jika dinamis
            'jabatan'   => 'Kepala',
            'unit_kerja'=> 'BPS Kabupaten Tegal',
            'nip'       => '(NIP Penanda Tangan)'
        ];

        // Load view PDF
        $pdf = PDF::loadView('spnsr.template_pdf_simple', $data);

        // Download file PDF
        return $pdf->download('SPNSR_' . $data['nomor'] . '.pdf');
    }
    public function updateStatusAjax(Request $request, SpnsrSubmission $submission): JsonResponse // 👈 Tipe return JsonResponse
    {
        // Hanya Pemimpin yang bisa akses (sudah dilindungi middleware route)
        $validatedData = $request->validate([
            'status' => 'required|string|in:Draft,Disetujui,Ditolak',
        ]);

        try {
            $submission->update(['status' => $validatedData['status']]);
            // Kirim respons JSON sukses
            return response()->json(['success' => true, 'message' => 'Status berhasil diperbarui.']);
        } catch (\Exception $e) {
            // Kirim respons JSON error jika gagal
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui status.'], 500);
        }
    }
}