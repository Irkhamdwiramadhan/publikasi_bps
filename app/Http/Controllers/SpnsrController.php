<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // Import PDF Facade
use Carbon\Carbon; // Kita tetap pakai Carbon untuk tanggal surat hari ini

class SpnsrController extends Controller
{
    /**
     * Menampilkan halaman form input.
     */
    public function create()
    {
        return view('spnsr.create_simple'); // Kita akan buat view baru
    }

    /**
     * Menghasilkan PDF berdasarkan input dari form.
     */
    public function generatePDF(Request $request)
    {
        // 1. Validasi input (sekarang jauh lebih sederhana)
        $validatedData = $request->validate([
            'nomor_surat'    => 'required|string|max:100',
            'tanggal_prosa'  => 'required|string|max:255', // Ini adalah input teks manual
            'judul_publikasi'=> 'required|string|max:255',
            'tipe_arc'       => 'required|string|max:50',
            'keterangan'     => 'nullable|string|max:255',
        ]);

        // 2. Siapkan data untuk dikirim ke view PDF
        // Tidak perlu konversi tanggal! Langsung pakai.
        $data = [
            'nomor'         => $validatedData['nomor_surat'],
            'tanggal_prosa' => $validatedData['tanggal_prosa'],
            'judul'         => $validatedData['judul_publikasi'],
            'tipe_arc'      => $validatedData['tipe_arc'],
            'keterangan'    => $validatedData['keterangan'] ?? '', // Handle jika kosong
            'tanggal_surat_dibuat' => Carbon::now()->isoFormat('D MMMM YYYY') // Cth: 26 Oktober 2025
        ];
        
        // 3. Ambil data penanda tangan (Sesuai PDF)
        // Nanti ini harusnya diambil dari Auth::user()
        $data['penanda_tangan'] = [
            'nama'      => 'Bambang Wahyu Ponco Aji, SST, M.Si.',
            'jabatan'   => 'Kepala',
            'unit_kerja'=> 'BPS Kabupaten Tegal',
            'nip'       => '(NIP Penanda Tangan)' // Tambahkan NIP jika perlu
        ];

        // 4. Load view PDF dengan data
        $pdf = PDF::loadView('spnsr.template_pdf_simple', $data);

        // 5. Download file PDF
        return $pdf->download('SPNSR_' . $data['nomor'] . '.pdf');
    }
}