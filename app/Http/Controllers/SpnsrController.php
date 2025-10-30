<?php

namespace App\Http\Controllers;

// Import model yang dibutuhkan
use App\Models\SpnsrSubmission;
use App\Models\Publication; 
use App\Models\SubmissionPublication; // 👈 PENTING: Model pengajuan publikasi
use App\Models\User; 

// Import Facades dan Class lain
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // 👈 Penting untuk file upload/download
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class SpnsrController extends Controller
{
    /**
     * Menampilkan daftar pengajuan SPNSR.
     * Pemimpin melihat semua, user lain hanya miliknya.
     */
    public function index()
    {
        $user = Auth::user();
        
        // REVISI: Eager load relasi baru 'submissionPublication.publication' untuk ambil judul
        $submissionsQuery = SpnsrSubmission::with(['user', 'submissionPublication.publication']) 
                            ->orderBy('created_at', 'desc');

        // REVISI: Filter diaktifkan kembali
       

        $submissions = $submissionsQuery->paginate(15); // Ambil data dengan paginasi

        return view('spnsr.index', compact('submissions'));
    }

    /**
     * Menampilkan form untuk membuat pengajuan SPNSR baru.
     */
    public function create()
    {
        // REVISI: Ambil PENGIRIMAN PUBLIKASI (bukan master) yang dibuat user ini
        // dan yang BELUM punya SPNSR
        $pendingSubmissions = SubmissionPublication::where('user_id', Auth::id())
                                ->whereDoesntHave('spnsrSubmission') // Cek yang belum punya SPNSR
                                ->with('publication') // Ambil data masternya untuk judul
                                ->orderBy('created_at', 'desc')
                                ->get();

        // REVISI: Mengirim data $pendingSubmissions ke view 'spnsr.create'
        return view('spnsr.create_simple', compact('pendingSubmissions')); 
    }

    /**
     * Menyimpan pengajuan SPNSR baru ke database.
     */
    public function store(Request $request)
    {
        // REVISI: Validasi disesuaikan dengan arsitektur baru
        $validatedData = $request->validate([
            'nomor_surat'    => 'required|string|max:100|unique:spnsr_submissions,nomor_surat',
            'tanggal_prosa'  => 'required|string|max:255',
            'submission_publication_id' => 'required|exists:submission_publications,id|unique:spnsr_submissions,submission_publication_id', // 👈 REVISI KUNCI
            'keterangan'     => 'nullable|string|max:255',
             // Hapus validasi 'publication_id', 'judul_publikasi', 'tipe_arc'
        ]);

        // Buat record baru di database
        SpnsrSubmission::create([
             'user_id' => Auth::id(), // ID user yang login (penyusun)
             
             // ---------------------------------------------------
             // REVISI KRITIS: Simpan foreign key yang benar
             'submission_publication_id' => $validatedData['submission_publication_id'], 
             // ---------------------------------------------------

             'nomor_surat' => $validatedData['nomor_surat'],
             'tanggal_prosa' => $validatedData['tanggal_prosa'],
             'keterangan' => $validatedData['keterangan'],
             'status' => 'Draft', // Status awal selalu 'Draft'
             
             // Hapus 'publication_id', 'judul_publikasi', 'tipe_arc'
        ]);

        return redirect()->route('spnsr.index')->with('success', 'Pengajuan SPNSR berhasil disimpan sebagai Draft.');
    }

    /**
     * Menghasilkan PDF DRAFT (tanpa TTD) untuk diunduh.
     */
    public function generatePdfDraft(SpnsrSubmission $submission) // Menggunakan Route Model Binding
    {
         // REVISI: Load relasi bertingkat untuk ambil judul & tipe
         $submission->load('submissionPublication.publication');
         
         // Cek jika relasi ada (jaga-jaga)
         $publication = $submission->submissionPublication?->publication;
         if(!$publication) {
             return redirect()->back()->with('error', 'Data publikasi terkait tidak ditemukan.');
         }

         // Menyiapkan data untuk dikirim ke view PDF
         $data = [
            'nomor'         => $submission->nomor_surat,
            'tanggal_prosa' => $submission->tanggal_prosa,
            'judul'         => $publication->title_ind, // 👈 REVISI: Ambil dari relasi
            'tipe_arc'      => $publication->publication_type, // 👈 REVISI: Ambil dari relasi
            'keterangan'    => $submission->keterangan ?? '',
            'tanggal_surat_dibuat' => $submission->created_at->isoFormat('D MMMM YYYY'), 
            'submission'    => $submission,
             'penanda_tangan' => [ 
                'nama' => 'Bambang Wahyu Ponco Aji, SST, M.Si.',
                'jabatan' => 'Kepala',
                'unit_kerja'=> 'BPS Kabupaten Tegal',
                'nip' => '(NIP Penanda Tangan)'
            ],
        ];

        // Gunakan template PDF yang TIDAK menampilkan TTD
        $pdf = PDF::loadView('spnsr.template_pdf_draft', $data); // Pastikan view ini ada

        return $pdf->download('SPNSR_DRAFT_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $data['nomor']) . '.pdf');
    }

    /**
     * Mengunggah file PDF yang sudah ditandatangani oleh Pemimpin.
     */
    public function uploadSignedPdf(Request $request, SpnsrSubmission $submission) // Route Model Binding
    {
        // Validasi file yang diunggah
        $request->validate([
            'signed_spnsr_file' => 'required|file|mimes:pdf|max:5120', // Hanya PDF, maks 5MB
        ]);

        // Cek apakah user adalah Pemimpin (double check, selain middleware)
         

        // Hapus file lama jika ada (opsional)
        if ($submission->signed_spnsr_path && Storage::disk('public')->exists($submission->signed_spnsr_path)) {
            Storage::disk('public')->delete($submission->signed_spnsr_path);
        }
        
        // Simpan file baru di 'storage/app/public/spnsr_signed'
        $path = $request->file('signed_spnsr_file')->store('spnsr_signed', 'public');

        // Update record di database
        $submission->update([
            'signed_spnsr_path' => $path,      // Simpan path file baru
            'status' => 'Disetujui', // Otomatis set status 'Disetujui'
        ]);

        return redirect()->route('spnsr.index')->with('success', 'File SPNSR bertanda tangan berhasil diunggah.');
    }

     /**
     * Mengunduh file PDF SPNSR yang sudah ditandatangani.
     */
    public function downloadSignedPdf(SpnsrSubmission $submission) // Route Model Binding
    {
        // Validasi
        if ($submission->signed_spnsr_path && Storage::disk('public')->exists($submission->signed_spnsr_path) && $submission->status == 'Disetujui') {
            
            $fileName = 'SPNSR_SIGNED_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $submission->nomor_surat) . '.pdf';

            return Storage::disk('public')->download($submission->signed_spnsr_path, $fileName);
        }

        return redirect()->back()->with('error', 'File SPNSR bertanda tangan tidak ditemukan atau belum disetujui.');
    }
    
    // REVISI: Hapus method edit(), update(), dan updateStatusAjax() yang sudah tidak relevan
}

