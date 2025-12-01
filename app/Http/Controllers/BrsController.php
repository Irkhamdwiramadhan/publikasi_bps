<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Brs;
use Carbon\Carbon;

class BrsController extends Controller
{
    public function index()
    {
        $brs_list = Brs::with('user')->latest('bulan')->paginate(10);
        return view('brs.index', compact('brs_list'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('brs.create', compact('users'));
    }

    /**
     * API Method: Generate Nomor BRS secara otomatis
     * Dipanggil via AJAX dari create.blade.php
     */
    public function generateNumber(Request $request)
    {
        // Validasi input tanggal saja
        $request->validate([
            'bulan' => 'required|date'
        ]);

        $date = Carbon::parse($request->bulan);

        // --- LOGIKA GENERATE NOMOR ---
        
        // 1. Hitung Urutan (Reset tiap tahun)
        $jumlahDataTahunIni = Brs::whereYear('bulan', $date->year)->count();
        $urutan = str_pad($jumlahDataTahunIni + 1, 2, '0', STR_PAD_LEFT);

        // 2. Format Bulan (Angka)
        $bulanAngka = $date->format('m');

        // 3. Kode BPS
        $kodeBps = '3328';

        // 4. Hitung Tahun Romawi (Start 2024 = I)
        $tahunDasar = 2023; 
        $selisihTahun = $date->year - $tahunDasar;
        $angkaRomawi = $selisihTahun > 0 ? $selisihTahun : 1; 
        $thRomawi = $this->numberToRoman($angkaRomawi);

        // 5. Format Tanggal Rilis Indonesia
        $tanggalRilis = $date->locale('id')->isoFormat('D MMMM Y');

        // Gabungkan
        $nomorBrs = "No. {$urutan}/{$bulanAngka}/{$kodeBps}/Th. {$thRomawi}, {$tanggalRilis}";

        // Kembalikan sebagai JSON untuk dibaca JavaScript
        return response()->json([
            'status' => 'success',
            'nomor' => $nomorBrs
        ]);
    }

    public function store(Request $request)
    {
        // Validasi
        $validatedData = $request->validate([
            'judul'     => 'required|string|max:255',
            'bulan'     => 'required|date', 
            'nomor_brs' => 'required|string', // Pastikan nomor brs terisi
            'user_id'   => 'required|exists:users,id',
            'pdf'       => 'required|file|mimes:pdf|max:50120',
            'zip'       => 'required|file|mimes:zip|max:10240',
            'excel'     => 'nullable|file|mimes:xlsx,xls|max:50120',
            'infografis'   => 'required|array',
            'infografis.*' => 'image|mimes:jpeg,png,jpg,gif|max:20048'
        ]);

        // Siapkan data dasar
        $dataToStore = [
            'judul'     => $validatedData['judul'],
            'nomor_brs' => $validatedData['nomor_brs'], // Simpan nomor dari form
            'user_id'   => $validatedData['user_id'],
            'bulan'     => $validatedData['bulan'], // Format Y-m-d
        ];

        // Upload File Logic (Sama seperti sebelumnya)
        if ($request->hasFile('pdf')) {
            $dataToStore['pdf_path'] = $request->file('pdf')->store('brs/pdf', 'public');
        }
        if ($request->hasFile('zip')) {
            $dataToStore['zip_path'] = $request->file('zip')->store('brs/zip', 'public');
        }
        if ($request->hasFile('excel')) {
            $dataToStore['excel_path'] = $request->file('excel')->store('brs/excel', 'public');
        }
        
        $infografisPaths = [];
        if ($request->hasFile('infografis')) {
            foreach ($request->file('infografis') as $file) {
                $infografisPaths[] = $file->store('brs/infografis', 'public');
            }
        }
        $dataToStore['infografis_paths'] = $infografisPaths;

        Brs::create($dataToStore);

        return redirect()->route('brs.index')->with('success', 'Data BRS berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $brs = Brs::findOrFail($id);
        $brs->load('user');
        return view('brs.show', compact('brs'));
    }

    // Helper Romawi
    private function numberToRoman($number) {
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if ($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }
    
    // Stub methods
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}
}