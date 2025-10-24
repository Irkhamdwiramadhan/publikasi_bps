{{-- 
    File ini adalah versi final dan lengkap dari halaman Master Publikasi.
    Termasuk implementasi penuh dari modal untuk fitur unggah file.
--}}

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
    </style>

    {{-- Header Halaman --}}
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-black dark:black leading-tight">
                    {{ __('Master Publikasi') }}
                </h2>
                <div class="text-sm breadcrumbs text-black">
                    <ul>
                        <li><a href="{{ route('dashboard') }}">Dashboard ></a></li> 
                        <li>Master Publikasi</li>
                    </ul>
                </div>
            </div>
            <!-- <div class="flex items-center gap-2 mt-3 md:mt-0">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Filter Tahun:</span>
                <div class="join">
                    @php $currentYear = now()->year; @endphp
                    <a href="{{ route('publications.index', array_merge(request()->except('page'), ['year' => $currentYear])) }}" class="join-item btn btn-sm {{ request('year', $currentYear) == $currentYear ? 'btn-primary' : '' }}">{{ $currentYear }}</a>
                    <a href="{{ route('publications.index', array_merge(request()->except('page'), ['year' => $currentYear - 1])) }}" class="join-item btn btn-sm {{ request('year') == $currentYear - 1 ? 'btn-primary' : '' }}">{{ $currentYear - 1 }}</a>
                </div>
            </div> -->
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
                            <a href="{{ route('publications.exportTemplate') }}" class="btn btn-success text-white btn-premium flex-grow md:flex-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                Unduh
                            </a>
                            <button onclick="upload_modal.showModal()" class="btn btn-info text-black btn-premium flex-grow md:flex-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                                Unggah
                            </button>
                            <a href="{{ route('publications.create') }}" class="btn btn-primary btn-premium flex-grow md:flex-none text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                                Tambah Publikasi
                            </a>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mb-4">
                        <div class="form-control">
                           <!-- <label class="label">
                               <span class="label-text">Tampilkan</span>
                               <select class="select select-sm select-bordered ml-2" onchange="window.location = this.value">
                                   <option value="{{ route('publications.index', array_merge(request()->except('page'), ['per_page' => 10])) }}" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                                   <option value="{{ route('publications.index', array_merge(request()->except('page'), ['per_page' => 25])) }}" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                   <option value="{{ route('publications.index', array_merge(request()->except('page'), ['per_page' => 50])) }}" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                               </select>
                               <span class="label-text ml-2">entri</span>
                           </label> -->
                        </div>
                        <form action="{{ route('publications.index') }}" method="GET" class="w-full md:w-1/3 relative">
                            @if(request('year')) <input type="hidden" name="year" value="{{ request('year') }}"> @endif
                            @if(request('per_page')) <input type="hidden" name="per_page" value="{{ request('per_page') }}"> @endif
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3"><svg class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none"><path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></span>
                            <input name="search" type="text" placeholder="Cari publikasi..." class="input input-bordered w-full pl-10" value="{{ request('search') }}" />
                        </form>
                    </div>

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
                                    <tr><td colspan="8" class="text-center py-16"><p class="text-xl font-semibold">Data Tidak Ditemukan</p></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($publications->hasPages())
                        <div class="mt-6 flex justify-between items-center">
                            <p class="text-sm text-base-content/70">Menampilkan {{ $publications->firstItem() }} - {{ $publications->lastItem() }} dari {{ $publications->total() }} hasil</p>
                            {{ $publications->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    {{-- [KONTEN LENGKAP] Modal untuk Unggah File --}}
    <dialog id="upload_modal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="font-bold text-lg">Unggah Master Publikasi</h3>
            <p class="py-4 text-sm">Pilih file Excel (.xlsx, .xls) yang sudah diisi sesuai template untuk mengimpor data secara massal. Pastikan format file sudah benar.</p>
            
            <form method="POST" action="{{ route('publications.import') }}" enctype="multipart/form-data"> {{-- Ganti # dengan route yang benar --}}
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

