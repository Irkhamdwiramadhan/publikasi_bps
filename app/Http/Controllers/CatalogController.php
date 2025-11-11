<?php

namespace App\Http\Controllers;

use App\Models\Catalog; // Pastikan Model Anda di-import
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * Menampilkan daftar (index) katalog dengan filter pencarian dan paginasi.
     */
    public function index(Request $request)
    {
        // Validasi input filter
        $request->validate([
            'per_page' => 'nullable|integer|in:10,25,50',
            'search'   => 'nullable|string|max:100',
        ]);

        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');

        $query = Catalog::query();

        // Terapkan filter pencarian
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nomor_katalog', 'like', '%' . $search . '%')
                  ->orWhere('judul_katalog', 'like', '%' . $search . '%');
            });
        }

        $catalogs = $query->orderBy('nomor_katalog', 'asc')
                          ->paginate($perPage)
                          ->withQueryString(); // Agar filter tetap ada di link paginasi

        return view('catalog.index', [
            'catalogs' => $catalogs,
            'filters' => $request->only(['search', 'per_page']),
        ]);
    }

    /**
     * Menampilkan form untuk membuat katalog baru.
     */
    public function create()
    {
        return view('catalog.create');
    }

    /**
     * Menyimpan katalog baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_katalog' => 'required|string|max:255',
            'judul_katalog' => 'required|string|max:255',
        ]);

        Catalog::create($validated);

        return redirect()->route('catalog.index')->with('success', 'Data katalog berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit katalog.
     */
    public function edit(Catalog $catalog)
    {
        return view('catalog.edit', compact('catalog'));
    }

    /**
     * Memperbarui katalog di database.
     */
    public function update(Request $request, Catalog $catalog)
    {
        $validated = $request->validate([
            'nomor_katalog' => 'required|string|max:255',
            'judul_katalog' => 'required|string|max:255',
        ]);

        $catalog->update($validated);

        return redirect()->route('catalog.index')->with('success', 'Data katalog berhasil diperbarui.');
    }

    /**
     * Menghapus katalog dari database.
     */
    public function destroy(Catalog $catalog)
    {
        // TODO: Tambahkan cek relasi di sini jika katalog sudah dipakai di SPRP
        // if ($catalog->sprp()->count() > 0) {
        //     return back()->with('error', 'Katalog tidak bisa dihapus karena sedang digunakan di SPRP.');
        // }

        $catalog->delete();
        return redirect()->route('catalog.index')->with('success', 'Data katalog berhasil dihapus.');
    }
}