<x-app-layout>
    {{-- CSS Kustom (Diambil dari file Anda) --}}
    <style>
        body { font-family: 'Inter', sans-serif; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .fade-in { animation: fadeIn 0.8s ease-out forwards; }
        @keyframes slideUpIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
        .table tbody tr {
            opacity: 0;
            animation: slideUpIn 0.5s ease-out forwards;
            animation-delay: var(--delay, 0s);
        }
        .table tbody tr.hover:hover, .table-hover tbody tr:hover {
            background-color: hsl(var(--b2, 220 13% 91%) / 0.5);
        }
        .table thead th {
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: hsl(var(--bc) / 0.6);
            border-bottom-width: 2px;
        }
        .btn-premium {
            transition: all 0.2s ease-in-out;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        }
        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }
        .border-premium {
            border-width: 1px;
            border-color: hsl(var(--bc) / 0.2);
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            transition: all 0.2s ease-in-out;
            border-radius: 20px;
        }
    </style>

    {{-- Header Halaman --}}
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-base-content leading-tight">
                    {{ __('Master Katalog') }}
                </h2>
                <div class="text-sm breadcrumbs text-base-content/70">
                    <ul>
                        <li><a href="{{ route('dashboard') }}" class="hover:text-primary">Dashboard</a></li> 
                        <li>Master Katalog</li>
                    </ul>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- Konten Utama --}}
    <div class="py-12 fade-in">
        <div class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto"> {{-- Diubah ke max-w-7xl agar lebih lebar --}}

            {{-- Alert Notifikasi --}}
            @if (session('success')) <div role="alert" class="alert alert-success mb-5 shadow-lg rounded-[20px]"><span>{{ session('success') }}</span></div> @endif
            @if (session('error')) <div role="alert" class="alert alert-error mb-5 shadow-lg rounded-[20px]"><span>{{ session('error') }}</span></div> @endif

            <div class="card bg-base-100 shadow-xl rounded-[20px]"> {{-- Radius disamakan --}}
                <div class="card-body p-6 md:p-8">
                    
                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                        <h3 class="text-xl font-bold text-base-content/90">Daftar Katalog</h3>
                        <div class="flex gap-2 w-full md:w-auto">
                            {{-- Hapus Tombol Unduh & Unggah (tidak relevan untuk katalog sederhana) --}}
                            <a href="{{ route('catalog.create') }}" class="btn btn-primary btn-premium flex-grow md:flex-none text-white border-premium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                                Tambah Katalog Baru
                            </a>
                        </div>
                    </div>

                    {{-- Form Filter (Direvisi untuk 'search') --}}
                    <form action="{{ route('catalog.index') }}" method="GET" class="mb-4">
                        <div class="flex flex-col md:flex-row gap-4 md:gap-3 md:items-center">
                            
                            {{-- Filter Tampil per Halaman --}}
                            <div class="flex items-center">
                                <label for="per_page" class="text-sm font-medium text-base-content/70 mr-3 whitespace-nowrap">Tampil per</label>
                                <select id="per_page" name="per_page" class="select select-bordered w-full md:w-auto border-premium" onchange="this.form.submit()">
                                    <option value="10" {{ ($filters['per_page'] ?? 10) == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ ($filters['per_page'] ?? null) == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ ($filters['per_page'] ?? null) == 50 ? 'selected' : '' }}>50</option>
                                </select>
                            </div>

                            {{-- Filter Pencarian (Lebih Bermanfaat) --}}
                            <div class="flex items-center flex-grow">
                                <label for="search" class="text-sm font-medium text-base-content/70 mr-3 whitespace-nowrap">Cari</label>
                                <input type="text" id="search" name="search" class="input input-bordered w-full border-premium" 
                                       placeholder="Ketik nomor atau judul katalog..." value="{{ $filters['search'] ?? '' }}">
                            </div>

                            <div class="flex gap-2">
                                <button type="submit" class="btn btn-primary btn-premium text-white border-premium">Cari</button>
                                <a href="{{ route('catalog.index') }}" class="btn btn-ghost btn-premium" title="Reset Filter">Reset</a>
                            </div>
                        </div>
                    </form>
                    
                    {{-- Tabel Data --}}
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr class="text-sm">
                                    <th>#</th>
                                    <th>Nomor Katalog</th>
                                    <th>Judul Katalog</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($catalogs as $catalog)
                                    <tr style="--delay: {{ $loop->index * 0.05 }}s;" class="hover">
                                        <th>{{ $catalogs->firstItem() + $loop->index }}</th>
                                        <td class="font-semibold">{{ $catalog->nomor_katalog }}</td>
                                        <td class="font-bold text-base-content whitespace-normal">{{ $catalog->judul_katalog }}</td>
                                        <td class="flex gap-1 justify-center">
                                            <a href="{{ route('catalog.edit', $catalog->id) }}" class="btn btn-ghost btn-xs btn-circle" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-warning" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg></a>
                                            <form action="{{ route('catalog.destroy', $catalog->id) }}" method="POST" onsubmit="return confirm('Yakin hapus katalog ini?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-ghost btn-xs btn-circle" title="Hapus"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-error" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center py-16"><p class="text-xl font-semibold">Data Katalog Tidak Ditemukan</p><p class="text-base-content/70">Coba kata kunci lain atau tambahkan data baru.</p></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginasi --}}
                    @if($catalogs->hasPages())
                        <div class="mt-6 flex justify-between items-center">
                            <p class="text-sm text-base-content/70">Menampilkan {{ $catalogs->firstItem() }} - {{ $catalogs->lastItem() }} dari {{ $catalogs->total() }} hasil</p>
                            {{ $catalogs->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>