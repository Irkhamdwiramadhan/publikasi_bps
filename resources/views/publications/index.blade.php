<x-app-layout>
    {{-- CSS Kustom untuk Tampilan Premium --}}
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
                    {{ __('Master Publikasi') }}
                </h2>
                <div class="text-sm breadcrumbs text-base-content/70">
                    <ul>
                        <li><a href="{{ route('dashboard') }}" class="hover:text-primary">Dashboard</a></li> 
                        <li>Master Publikasi</li>
                    </ul>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- Konten Utama --}}
    <div class="py-12 fade-in">
        <div class="px-4 sm:px-6 lg:px-8">

            {{-- Alert Notifikasi --}}
            @if (session('success')) <div role="alert" class="alert alert-success mb-5 shadow-lg"><span>{{ session('success') }}</span></div> @endif
            @if (session('error')) <div role="alert" class="alert alert-error mb-5 shadow-lg"><span>{{ session('error') }}</span></div> @endif

            <div class="card bg-base-100 shadow-xl">
                <div class="card-body p-6 md:p-8">
                    
                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                        <h3 class="text-xl font-bold text-base-content/90">Daftar Publikasi</h3>
                        <div class="flex gap-2 w-full md:w-auto">
                            <a href="{{ route('publications.exportTemplate') }}" class="btn btn-success text-white btn-premium flex-grow md:flex-none border-premium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                Unduh
                            </a>
                            <button onclick="upload_modal.showModal()" class="btn btn-info text-black btn-premium flex-grow md:flex-none ">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                                Unggah
                            </button>
                            <a href="{{ route('publications.create') }}" class="btn btn-primary btn-premium flex-grow md:flex-none text-white border-premium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                                Tambah Publikasi
                            </a>
                        </div>
                    </div>

                    {{-- 
                        REVISI BESAR DI BAWAH INI
                        1. Menghapus semua class '...-sm' (select-sm, btn-sm) agar semua elemen filter memiliki tinggi yang SAMA.
                        2. Menambah 'md:items-center' untuk perataan vertikal yang sempurna.
                        3. Menambah 'mr-3' pada label untuk "ruang bernapas".
                        4. Mengganti 'md:w-48' menjadi 'md:min-w-48' (12rem) agar "Semua Tahun" tidak terpotong.
                    --}}
                    <form action="{{ route('publications.index') }}" method="GET" class="mb-4">
                        <div class="flex flex-col md:flex-row gap-4 md:gap-3 md:items-center">
                            
                            <div class="flex items-center">
                                <label for="per_page" class="text-sm font-medium text-base-content/70 mr-3 whitespace-nowrap">Tampil per</label>
                                {{-- REVISI: Hapus 'select-sm' --}}
                                <select id="per_page" name="per_page" class="select select-bordered w-full md:w-auto border-premium" onchange="this.form.submit()">
                                    <option value="10" {{ ($filters['per_page'] ?? 10) == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ ($filters['per_page'] ?? null) == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ ($filters['per_page'] ?? null) == 50 ? 'selected' : '' }}>50</option>
                                </select>
                            </div>

                            <div class="flex items-center">
                                <label for="year" class="text-sm font-medium text-base-content/70 mr-3 whitespace-nowrap ">Tahun</label>
                                {{-- REVISI: Hapus 'select-sm' dan ganti 'w-full md:w-48' menjadi 'w-full md:min-w-48' --}}
                                <select id="year" name="year" class="select select-bordered w-full md:min-w-48 border-premium" onchange="this.form.submit()">
                                    <option value="">Semua Tahun</option>
                                    @foreach($years as $year)
                                        <option value="{{ $year }}" {{ ($filters['year'] ?? null) == $year ? 'selected' : '' }}>{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>

                    

                            <div class="flex gap-2">
                                
                                {{-- REVISI: Hapus 'btn-sm' agar tingginya sama --}}
                                <a href="{{ route('publications.index') }}" class="btn btn-ghost btn-premium" title="Reset Filter">Reset</a>
                            </div>
                        </div>
                    </form>
                    
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr class="text-sm">
                                    <th>#</th><th>Jenis</th><th>Katalog</th><th>Tahun</th><th>Judul Publikasi</th><th>Frekuensi</th><th>No ISSN</th><th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($publications as $pub)
                                    <tr style="--delay: {{ $loop->index * 0.05 }}s;" class="hover">
                                        <th>{{ $publications->firstItem() + $loop->index }}</th>
                                        <td><span class="badge {{ $pub->publication_type == 'ARC' ? 'badge-success' : 'badge-info' }} badge-sm text-white font-semibold">{{ $pub->publication_type }}</span></td>
                                        <td>{{ $pub->catalog_number }}</td>
                                        <td>{{ $pub->year }}</td>
                                        <td class="font-bold text-base-content whitespace-normal">{{ $pub->title_ind }}</td>
                                        <td>{{ $pub->frequency }}</td>
                                        <td>{{ $pub->issn_number ?? '-' }}</td>
                                        <td class="flex gap-1 justify-center">
                                            <a href="{{ route('publications.edit', $pub->id) }}" class="btn btn-ghost btn-xs btn-circle" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-warning" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg></a>
                                            <form action="{{ route('publications.destroy', $pub->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-ghost btn-xs btn-circle" title="Hapus"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-error" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8" class="text-center py-16"><p class="text-xl font-semibold">Data Tidak Ditemukan</p><p class="text-base-content/70">Coba ubah filter pencarian Anda.</p></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($publications->hasPages())
                        <div class="mt-6 flex justify-between items-center">
                            <p class="text-sm text-base-content/70">Menampilkan {{ $publications->firstItem() }} - {{ $publications->lastItem() }} dari {{ $publications->total() }} hasil</p>
                            {{ $publications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    {{-- Modal untuk Unggah File (Tidak diubah) --}}
    <dialog id="upload_modal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="font-bold text-lg">Unggah Master Publikasi</h3>
            <p class="py-4 text-sm">Pilih file Excel (.xlsx, .xls) yang sudah diisi sesuai template untuk mengimpor data secara massal. Pastikan format file sudah benar.</p>
            
            <form method="POST" action="{{ route('publications.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-control w-full">
                    <label class="label"><span class="label-text">Pilih File Excel</span></label>
                    <input type="file" name="file" class="file-input file-input-bordered file-input-info w-full" required accept=".xlsx, .xls" />
                </div>
                
                <div class="modal-action mt-6">
                    <form method="dialog">
                        <button class="btn">Batal</button>
                    </form>
                    <button type="submit" class="btn btn-info text-black">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                        Impor Data
                    </button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
</x-app-layout>