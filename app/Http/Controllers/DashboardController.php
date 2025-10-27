<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubmissionPublication; // 👈 Impor model Anda
use Illuminate\Support\Facades\DB;     // 👈 Impor DB Facade
use Carbon\Carbon;                   // 👈 Impor Carbon

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Ambil tahun yang dipilih dari request, default ke tahun ini
        $selectedYear = $request->input('year', Carbon::now()->year);

        // Ambil semua tahun unik dari data pengajuan untuk filter
        // Diurutkan dari terbaru ke terlama
        $availableYears = SubmissionPublication::selectRaw('YEAR(created_at) as year')
                            ->distinct()
                            ->orderBy('year', 'desc')
                            ->pluck('year');
        
        // Jika tidak ada data sama sekali, setidaknya tampilkan tahun ini
        if ($availableYears->isEmpty()) {
            $availableYears = collect([Carbon::now()->year]);
        }

        // Fungsi helper untuk mengambil jumlah berdasarkan status dan tipe publikasi
        $getCounts = function($publicationType) use ($selectedYear) {
            return SubmissionPublication::whereYear('created_at', $selectedYear)
                ->whereHas('publication', function ($query) use ($publicationType) {
                    $query->where('publication_type', $publicationType);
                })
                ->select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status'); // Hasilnya: ['draft' => 5, 'disetujui' => 10, ...]
        };

        // Ambil counts untuk ARC dan Non-ARC
        $arcStatusCounts = $getCounts('ARC');
        $nonArcStatusCounts = $getCounts('Non ARC');

        // Siapkan data ringkasan untuk view
        $arcSummary = [
            'total' => $arcStatusCounts->sum(), // Jumlah total ARC
            'sedang_diperiksa' => $arcStatusCounts->get('sedang_diperiksa', 0),
            'butuh_perbaikan' => $arcStatusCounts->get('butuh_perbaikan', 0),
            'disetujui' => $arcStatusCounts->get('disetujui', 0),
            'ditolak' => $arcStatusCounts->get('ditolak', 0),
            // Tambahkan status lain jika ada (misal: 'draft')
             'publikasi_masuk' => $arcStatusCounts->get('draft', 0) + $arcStatusCounts->get('sedang_diperiksa', 0) + $arcStatusCounts->get('butuh_perbaikan', 0), // Contoh kalkulasi "Publikasi Masuk"
        ];

        $nonArcSummary = [
            'total' => $nonArcStatusCounts->sum(), // Jumlah total Non-ARC
            'sedang_diperiksa' => $nonArcStatusCounts->get('sedang_diperiksa', 0),
            'butuh_perbaikan' => $nonArcStatusCounts->get('butuh_perbaikan', 0),
            'disetujui' => $nonArcStatusCounts->get('disetujui', 0),
            'ditolak' => $nonArcStatusCounts->get('ditolak', 0),
             'publikasi_masuk' => $nonArcStatusCounts->get('draft', 0) + $nonArcStatusCounts->get('sedang_diperiksa', 0) + $nonArcStatusCounts->get('butuh_perbaikan', 0), // Contoh kalkulasi "Publikasi Masuk"
        ];

        // ... (kode untuk $availableYears, $getCounts, $arcStatusCounts, $nonArcStatusCounts) ...

    // AMBIL SEMUA STATUS UNIK DARI DATABASE UNTUK TAHUN TERPILIH
    $actualStatuses = SubmissionPublication::whereYear('created_at', $selectedYear)
                        ->select('status')
                        ->distinct()
                        ->pluck('status');

    // ... (return view) ...


        // Kirim data ke view dashboard
        return view('dashboard', compact(
            'selectedYear', 
            'availableYears', 
            'arcSummary', 
            'nonArcSummary'
        ));
    }
}