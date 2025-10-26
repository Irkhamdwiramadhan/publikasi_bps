<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use Illuminate\Http\Request;
use App\Exports\PublicationsTemplateExport; // <-- 1. Import class Ekspor
use App\Imports\PublicationsImport;       // <-- 2. Import class Impor
use Maatwebsite\Excel\Facades\Excel;        // <-- 3. Import fasad Excel

class PublicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 1. Validasi input filter
        $request->validate([
            'per_page' => 'nullable|integer|in:10,25,50',
            'year'     => 'nullable|integer',
            'search'   => 'nullable|string|max:100',
        ]);

        // 2. Ambil semua tahun unik dari database untuk dropdown filter
        // Kita cache query ini agar lebih cepat
        $years = Publication::select('year')
                            ->distinct()
                            ->orderBy('year', 'desc')
                            ->pluck('year');

        // 3. Siapkan query dasar
        $query = Publication::query();

        // 4. Terapkan filter pencarian
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title_ind', 'like', '%' . $request->search . '%')
                  ->orWhere('catalog_number', 'like', '%' . $request->search . '%')
                  ->orWhere('issn_number', 'like', '%' . $request->search . '%');
            });
        }

        // 5. Terapkan filter tahun
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        // 6. Tentukan jumlah data per halaman
        $perPage = $request->input('per_page', 10);

        // 7. Ambil data dengan paginasi
        $publications = $query->orderBy('year', 'desc')
                              ->orderBy('title_ind', 'asc')
                              ->paginate($perPage)
                              ->withQueryString(); // <-- Penting agar filter tetap ada di link paginasi

        // 8. Kirim data ke view
        return view('publications.index', [ // Ganti 'publications.index' jika nama view Anda berbeda
            'publications' => $publications,
            'years' => $years,
            'filters' => $request->only(['search', 'year', 'per_page']), // Kirim filter kembali ke view
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('publications.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'title_ind' => 'required|string|max:255',
            'title_eng' => 'nullable|string|max:255', // Ditambahkan untuk konsistensi
            'publication_type' => 'required|string',
            'catalog_number' => 'required|string|max:50',
            'year' => 'required|integer|min:2000',
            'frequency' => 'required|string|max:100',
            'issn_number' => 'nullable|string|max:50',
        ]);

        // 2. Simpan data yang sudah valid ke database
        Publication::create($validatedData);

        // 3. Kembali ke halaman index dengan pesan sukses
        return redirect()->route('publications.index')
            ->with('success', 'Data publikasi berhasil ditambahkan.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Publication $publication)
    {
        // 1. ISI FUNGSI EDIT
        return view('publications.edit', compact('publication'));
    }

    /**
     * Update the specified resource in storage.
     */
    // Kita gunakan 'Publication $publication' lagi
    public function update(Request $request, Publication $publication)
    {
        // 2. ISI FUNGSI UPDATE

        // Validasi datanya (sama seperti 'store')
        $validatedData = $request->validate([
            'title_ind' => 'required|string|max:255',
            'title_eng' => 'nullable|string|max:255',
            'publication_type' => 'required|string',
            'catalog_number' => 'required|string|max:50',
            'year' => 'required|integer|min:2000',
            'frequency' => 'required|string|max:100',
            'issn_number' => 'nullable|string|max:50',
        ]);

        // Update data di database
        $publication->update($validatedData);

        // Kembali ke halaman index dengan pesan sukses
        return redirect()->route('publications.index')
            ->with('success', 'Data publikasi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Publication $publication)
    {
        // 1. Hapus data dari database
        $publication->delete();

        // 2. Kembali ke halaman index dengan pesan sukses
        return redirect()->route('publications.index')
            ->with('success', 'Data publikasi berhasil dihapus.');
    }

    /**
     * FUNGSI BARU: Untuk mengunduh template Excel.
     */
    public function exportTemplate()
    {
        return Excel::download(new PublicationsTemplateExport, 'template_master_publikasi.xlsx');
    }

    /**
     * FUNGSI BARU: Untuk mengimpor data dari Excel.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new PublicationsImport, $request->file('file'));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
             $failures = $e->failures();
             // Memberikan feedback error yang lebih spesifik
             return back()->with('error', 'Gagal mengimpor data. Pastikan semua kolom terisi dengan benar. Error pada baris: ' . $failures[0]->row());
        } catch (\Exception $e) {
            // Menangani error umum lainnya
            return back()->with('error', 'Terjadi kesalahan saat mengimpor file: ' . $e->getMessage());
        }
        
        return redirect()->route('publications.index')->with('success', 'Data master publikasi berhasil diimpor.');
    }
}

