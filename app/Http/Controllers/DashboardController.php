<?php

namespace App\Http\Controllers;

use App\Models\SubmissionPublication;
use App\Models\Sprp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama dengan data visualisasi (chart + kalender).
     *
     * Pastikan view 'dashboard' (blade) menerima:
     *  - kpi_arc, kpi_non_arc, kpi_rilis
     *  - barChartMonths (array labels), barChartSeries (array values)
     *  - donutStatusLabels, donutStatusSeries
     *  - donutKategoriLabels, donutKategoriSeries
     *  - currentYear, availableYears, selectedYear
     *  - calendarEvents (array of events untuk FullCalendar)
     *
     * Anda bisa menyesuaikan nama view / route sesuai proyek Anda.
     */
    public function index(Request $request)
    {
        // Ambil tahun yang dipilih lewat query string, default tahun sekarang
        $currentYear = (int) $request->input('year', Carbon::now()->year);

        // Ambil list tahun dari SPRP (atau bisa diganti ambil dari SubmissionPublication jika relevan)
        $availableYears = Sprp::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        if ($availableYears->isEmpty()) {
            $availableYears = collect([$currentYear]);
        }

        // Ambil submissions yang punya estimasi_rilis di tahun terpilih
        // Pastikan kolom 'estimasi_rilis' adalah tipe tanggal/datetime di DB
        $submissions = SubmissionPublication::query()
            ->whereNotNull('estimasi_rilis')
            ->whereYear('estimasi_rilis', $currentYear)
            ->get();

        // Ambil SPRP (untuk donut charts) berdasarkan tahun registrasi created_at
        $sprps = Sprp::whereYear('created_at', $currentYear)->get();

        // --- KPI ---
        // Hitung dari $submissions (yang punya estimasi_rilis di tahun ini)
        $kpi_arc = $submissions->where('type_publikasi', 'ARC')->count();
        $kpi_non_arc = $submissions->where('type_publikasi', 'Non ARC')->count();
        // Asumsi 'disetujui' menandakan rilis
        $kpi_rilis = $submissions->where('status', 'disetujui')->count();

        // --- Bar Chart: publikasi per bulan (berdasarkan estimasi_rilis) ---
        // Kita buat grouped counts berdasarkan nomor bulan (1..12) supaya bisa urut
        $countsByMonth = $submissions
            ->filter(function ($s) {
                return !empty($s->estimasi_rilis);
            })
            ->groupBy(function ($s) {
                // Pastikan ini Carbon instance
                $dt = $s->estimasi_rilis instanceof Carbon ? $s->estimasi_rilis : Carbon::parse($s->estimasi_rilis);
                return (int) $dt->format('n'); // 1..12
            })
            ->map(function ($group) {
                return $group->count();
            });

        // Label bulan yang sesuai (Indonesia / singkatan)
        $months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];

        $barChartSeries = [];
        foreach (range(1, 12) as $m) {
            $barChartSeries[] = $countsByMonth->get($m, 0);
        }

        // --- Donut 1: Status (dari SPRP) ---
        $donutStatusData = $submissions
            ->groupBy('status')
            ->map(function ($g) { return $g->count(); });

        $donutStatusLabels = $donutStatusData->keys()->all();
        $donutStatusSeries = $donutStatusData->values()->all();

        // --- Donut 2: Kategori (dari SPRP) ---
        $donutKategoriData = $submissions
            ->groupBy('type_publikasi')
            ->map(function ($g) { return $g->count(); });

        $donutKategoriLabels = $donutKategoriData->keys()->all();
        $donutKategoriSeries = $donutKategoriData->values()->all();

        // --- Calendar events untuk FullCalendar ---
        // Format event FullCalendar minimal: [ 'title' => '...', 'start' => 'YYYY-MM-DD', ... ]
        $calendarEvents = $submissions->map(function ($s) {
            // pastikan estimasi_rilis adalah Carbon / string yang parseable
            $dt = $s->estimasi_rilis instanceof Carbon ? $s->estimasi_rilis : Carbon::parse($s->estimasi_rilis);

            // Judul event: bisa disesuaikan (misal nama publikasi / type / status)
            // Anda mungkin punya kolom 'judul' atau 'nama' di SubmissionPublication -> sesuaikan di sini.
            $titleParts = [];

            // contoh: gunakan kode / title jika ada
            if (!empty($s->judul ?? null)) {
                $titleParts[] = substr($s->judul, 0, 40); // potong panjang judul
            } else {
                // fallback: type + id
                $titleParts[] = ($s->judul_publikasi ?? 'Publikasi') . " (#" . ($s->id ?? '') . ")";
            }

            // tambahkan status singkat
            if (!empty($s->status)) {
                $titleParts[] = "[" . ucfirst($s->status) . "]";
            }

            $title = implode(' ', $titleParts);

            // Tentukan warna event berdasarkan status atau type
            $color = '#3b82f6'; // default biru
            $status = strtolower($s->status ?? '');
            $type = strtolower($s->judul_publikasi ?? '');

            if (strpos($status, 'disetujui') !== false || $status === 'disetujui') {
                $color = '#10b981'; // hijau
            } elseif (strpos($status, 'sedang') !== false || strpos($status, 'proses') !== false) {
                $color = '#f59e0b'; // kuning/oranye
            } elseif ($status === 'ditolak' || strpos($status, 'tolak') !== false) {
                $color = '#ef4444'; // merah
            } else {
                // berdasarkan type
                if (strpos($type, 'arc') !== false) $color = '#6366f1';
                if (strpos($type, 'non') !== false) $color = '#06b6d4';
            }

            // optional: url ke halaman detail publikasi (sesuaikan route di aplikasi Anda)
            // Jika Anda punya route named e.g. 'submissions.show', ganti url() berikut dengan route('submissions.show', $s->id)
            $url = url("/submissions/{$s->id}");

            return [
                'id' => $s->id,
                'title' => $title,
                'start' => $dt->toDateString(),
                'allDay' => true,
                'color' => $color,
                'extendedProps' => [
                    'type_publikasi' => $s->type_publikasi,
                    'status' => $s->status,
                    // tambahkan field lain yang berguna
                ],
                // 'url' => $url, // aktifkan kalau mau klik event buka detail
            ];
        })->values()->all();

        // Jika ingin juga menampilkan submissions tanpa estimasi_rilis di timeline mingguan, bisa ditambahkan
        // tetapi saat ini kita hanya menampilkan yang punya estimasi_rilis di tahun terpilih.

        // --- Kirim semua ke view ---
        return view('dashboard', [
            'kpi_arc' => $kpi_arc,
            'kpi_non_arc' => $kpi_non_arc,
            'kpi_rilis' => $kpi_rilis,
            'barChartMonths' => $months,
            'barChartSeries' => $barChartSeries,
            'donutStatusLabels' => $donutStatusLabels,
            'donutStatusSeries' => $donutStatusSeries,
            'donutKategoriLabels' => $donutKategoriLabels,
            'donutKategoriSeries' => $donutKategoriSeries,
            'currentYear' => $currentYear,
            'availableYears' => $availableYears,
            'selectedYear' => $currentYear,
            'calendarEvents' => $calendarEvents,
        ]);
    }

    /**
     * (Opsional) method tambahan untuk API event jika Anda ingin memanggil via AJAX:
     *
     * public function events(Request $request) {
     *     $year = (int) $request->input('year', Carbon::now()->year);
     *     $subs = SubmissionPublication::whereNotNull('estimasi_rilis')->whereYear('estimasi_rilis', $year)->get();
     *     // Build same structure seperti $calendarEvents di atas lalu return response()->json($events);
     * }
     */
}
