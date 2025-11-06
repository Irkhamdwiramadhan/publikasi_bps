<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Brs;

class BrsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil data BRS, muat relasi 'user' (Pengelola)
        // Urutkan berdasarkan data terbaru (bulan rilis)
        $brs_list = Brs::with('user')
            ->latest('bulan') // Mengurutkan dari bulan terbaru
            ->paginate(10); // Paginasi 10 data per halaman

        return view('brs.index', compact('brs_list'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil semua user untuk dropdown
        $users = User::orderBy('name')->get();
        return view('brs.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $validatedData = $request->validate([
            'judul'     => 'required|string|max:255',
            'bulan'     => 'required|date_format:Y-m', // Validasi format 'YYYY-MM' dari input type="month"
            'user_id'   => 'required|exists:users,id',
            'pdf'       => 'required|file|mimes:pdf|max:5120', // Maks 5MB
            'zip'       => 'required|file|mimes:zip|max:10240', // Maks 10MB
            'excel'     => 'nullable|file|mimes:xlsx,xls|max:5120', // Opsional

            // Validasi untuk multiple file (infografis)
            'infografis'   => 'required|array', // Pastikan 'infografis' adalah array
            'infografis.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048' // Validasi setiap file di dalam array
        ]);

        // 2. Siapkan data untuk disimpan
        $dataToStore = [
            'judul'   => $validatedData['judul'],
            'user_id' => $validatedData['user_id'],
            // Simpan sebagai tanggal 1 di bulan tersebut
            'bulan'   => $validatedData['bulan'] . '-01',
        ];

        // 3. Proses Upload File (Satu per satu)
        // Gunakan folder 'public' agar bisa diakses

        if ($request->hasFile('pdf')) {
            $dataToStore['pdf_path'] = $request->file('pdf')->store('brs/pdf', 'public');
        }

        if ($request->hasFile('zip')) {
            $dataToStore['zip_path'] = $request->file('zip')->store('brs/zip', 'public');
        }

        if ($request->hasFile('excel')) {
            $dataToStore['excel_path'] = $request->file('excel')->store('brs/excel', 'public');
        }

        // 4. Proses Upload Multiple File (Infografis)
        $infografisPaths = [];
        if ($request->hasFile('infografis')) {
            foreach ($request->file('infografis') as $file) {
                $path = $file->store('brs/infografis', 'public');
                $infografisPaths[] = $path;
            }
        }
        $dataToStore['infografis_paths'] = $infografisPaths; // Model akan auto-encode ke JSON

        // 5. Simpan ke Database
        Brs::create($dataToStore);

        return redirect()->route('brs.index')->with('success', 'Data BRS berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // 2. TAMBAHKAN BARIS INI UNTUK MENCARI DATA SECARA MANUAL
        $brs = Brs::findOrFail($id);

        // 3. SEKARANG KODE ANDA YANG LAIN AKAN BERFUNGSI
        $brs->load('user');
        
        // Kirim data $brs yang lengkap ke view 'brs.show'
        return view('brs.show', compact('brs'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
