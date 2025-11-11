<?php

namespace App\Http\Controllers;

// Import model yang dibutuhkan
use App\Models\SpnsrSubmission;
use App\Models\SubmissionPublication; // 👈 PENTING: Model "jantung" data
use App\Models\User; 
use App\Models\Publication; // (Boleh dihapus jika sudah tidak dipakai)

// Import Facades dan Class lain
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class SpnsrController extends Controller
{
    /**
     * Menampilkan daftar pengajuan SPNSR.
     */
    public function index()
    {
        $user = Auth::user();
        
        // ▼▼▼ REVISI QUERY ▼▼▼
        // Kita tidak perlu relasi '...publication' lagi.
        // Kita ambil data langsung dari 'submissionPublication' dan relasi 'catalog' nya.
        $submissionsQuery = SpnsrSubmission::with([
                                'user', // User yang membuat SPNSR
                                'submissionPublication.catalog', // Data katalog dari submission
                                'submissionPublication.user' // User (penyusun) dari submission
                            ]) 
                            ->orderBy('created_at', 'desc');
        // ▲▲▲ AKHIR REVISI ▲▲▲

        $submissions = $submissionsQuery->paginate(15); 

        return view('spnsr.index', compact('submissions'));
    }

    /**
     * Menampilkan form untuk membuat pengajuan SPNSR baru.
     * (Logika ini sudah benar dan tidak perlu diubah)
     */
    public function create()
    {
        $pendingSubmissions = SubmissionPublication::where('user_id', Auth::id())
                                ->whereDoesntHave('spnsrSubmission') // Cek yang belum punya SPNSR
                                ->with('catalog') // (Muat relasi catalog untuk jaga-jaga)
                                ->orderBy('created_at', 'desc')
                                ->get();

        return view('spnsr.create_simple', compact('pendingSubmissions')); 
    }

    /**
     * Menyimpan pengajuan SPNSR baru ke database.
     * (Logika ini sudah benar dan tidak perlu diubah)
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nomor_surat'    => 'required|string|max:100|unique:spnsr_submissions,nomor_surat',
            'tanggal_prosa'  => 'required|string|max:255',
            'submission_publication_id' => 'required|exists:submission_publications,id|unique:spnsr_submissions,submission_publication_id',
            'keterangan'     => 'nullable|string|max:255',
            'tanggal_rilis'  => 'required|date', // (Ini sudah benar dari revisi kita)
        ]);

        SpnsrSubmission::create([
             'user_id' => Auth::id(), 
             'submission_publication_id' => $validatedData['submission_publication_id'], 
             'nomor_surat' => $validatedData['nomor_surat'],
             'tanggal_prosa' => $validatedData['tanggal_prosa'],
             'tanggal_rilis' => $validatedData['tanggal_rilis'], // (Ini sudah benar)
             'keterangan' => $validatedData['keterangan'],
             'status' => 'Draft', 
        ]);

        return redirect()->route('spnsr.index')->with('success', 'Pengajuan SPNSR berhasil disimpan sebagai Draft.');
    }

    /**
     * Menghasilkan PDF DRAFT (tanpa TTD) untuk diunduh.
     */
    public function generatePdfDraft(SpnsrSubmission $submission) 
    {
         // ▼▼▼ REVISI LOGIKA PENGAMBILAN DATA ▼▼▼
         
         // 1. Muat relasi ke 'submissionPublication' (jantung data)
         $submission->load('submissionPublication');
         
         // 2. Ambil data submission
         $submissionData = $submission->submissionPublication;
         
         if(!$submissionData) {
             return redirect()->back()->with('error', 'Data publikasi terkait (submission) tidak ditemukan.');
         }

         // 3. Format tanggal rilis (diambil dari SPNSR, sesuai logika Anda)
         $tanggal_rilis_formatted = $submission->tanggal_rilis 
                                    ? Carbon::parse($submission->tanggal_rilis)->isoFormat('D MMMM YYYY') 
                                    : 'N/A';
         // ▲▲▲ AKHIR REVISI ▲▲▲

         // Menyiapkan data untuk dikirim ke view PDF
         $data = [
            'nomor'         => $submission->nomor_surat,
            'tanggal_prosa' => $submission->tanggal_prosa,
            
            // ▼▼▼ REVISI SUMBER DATA ▼▼▼
            'judul'         => $submissionData->judul_publikasi, // Diambil dari submission
            'tipe_arc'      => $submissionData->type_publikasi,  // Diambil dari submission
            'tanggal_rilis' => $tanggal_rilis_formatted,     // Diambil dari $submission (SPNSR)
            // ▲▲▲ AKHIR REVISI ▲▲▲

            'keterangan'    => $submission->keterangan ?? '',
            'tanggal_surat_dibuat' => $submission->created_at->isoFormat('D MMMM YYYY'), 
            'submission'    => $submission,
             'penanda_tangan' => [ 
                'nama' => 'Bambang Wahyu Ponco Aji, SST, M.Si.',
                'jabatan' => 'Kepala',
                'unit_kerja'=> 'BPS Kabupaten Tegal',
                'nip' => ''
            ],
        ];

        // Gunakan template PDF yang TIDAK menampilkan TTD
        $pdf = PDF::loadView('spnsr.template_pdf_draft', $data); 

        return $pdf->download('SPNSR_DRAFT_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $data['nomor']) . '.pdf');
    }

    /**
     * Mengunggah file PDF yang sudah ditandatangani oleh Pemimpin.
     * (Logika ini sudah benar dan tidak perlu diubah)
     */
    public function uploadSignedPdf(Request $request, SpnsrSubmission $submission) 
    {
        $request->validate([
            'signed_spnsr_file' => 'required|file|mimes:pdf|max:5120',
        ]);

        if ($submission->signed_spnsr_path && Storage::disk('public')->exists($submission->signed_spnsr_path)) {
            Storage::disk('public')->delete($submission->signed_spnsr_path);
        }
        
        $path = $request->file('signed_spnsr_file')->store('spnsr_signed', 'public');

        $submission->update([
            'signed_spnsr_path' => $path,      
            'status' => 'Disetujui', 
        ]);

        return redirect()->route('spnsr.index')->with('success', 'File SPNSR bertanda tangan berhasil diunggah.');
    }

     /**
     * Mengunduh file PDF SPNSR yang sudah ditandatangani.
     * (Logika ini sudah benar dan tidak perlu diubah)
     */
    public function downloadSignedPdf(SpnsrSubmission $submission) 
    {
        if ($submission->signed_spnsr_path && Storage::disk('public')->exists($submission->signed_spnsr_path) && $submission->status == 'Disetujui') {
            
            $fileName = 'SPNSR_SIGNED_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $submission->nomor_surat) . '.pdf';

            return Storage::disk('public')->download($submission->signed_spnsr_path, $fileName);
        }

        return redirect()->back()->with('error', 'File SPNSR bertanda tangan tidak ditemukan atau belum disetujui.');
    }
}