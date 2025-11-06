<x-app-layout>
    {{-- CSS Kustom (Konsisten) --}}
    <style>
        body { font-family: 'Inter', sans-serif; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .fade-in { animation: fadeIn 0.8s ease-out forwards; }
        .rounded-\[15px\] { border-radius: 15px; }

        /* Mencegah icon di dalam dropdown berputar saat dropdown dibuka */
        .dropdown .btn-square svg {
            transition: none;
        }
    </style>

    {{-- Header Halaman (Hanya Judul & Breadcrumbs) --}}
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center fade-in">
            <div>
                <h2 class="font-semibold text-xl text-base-content leading-tight">
                    📊 {{ __('Berita Resmi Statistik (BRS)') }}
                </h2>
                <div class="text-sm breadcrumbs text-base-content/70">
                     <ul>
                        <li><a href="{{ route('dashboard') }}" class="hover:text-primary">Dashboard</a></li>
                        <li>Data BRS</li>
                    </ul>
                </div>
            </div>
            
            {{-- Tombol Aksi (Tambah Baru) dipindahkan ke body --}}
        </div>
    </x-slot>

    {{-- Konten Utama --}}
    <div class="py-12 fade-in">
        <div class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">

            {{-- Alert Notifikasi (Tetap ada) --}}
            @if (session('success'))
                <div role="alert" class="alert alert-success mb-5 shadow-lg rounded-[15px]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
             @if (session('error'))
                 <div role="alert" class="alert alert-error mb-5 shadow-lg rounded-[15px]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- REVISI: Tombol Tambah Baru (di atas tabel) --}}
            <div class="flex justify-end mb-4">
                <a href="{{ route('brs.create') }}" class="btn btn-primary btn-sm rounded-[15px] text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Tambah BRS Baru
                </a>
            </div>

            {{-- Card untuk Tabel --}}
            <div class="card bg-base-100 shadow-xl rounded-[15px]">
                <div class="card-body p-0">
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th class="w-10">No.</th>
                                    <th>Judul</th>
                                    <th>Bulan</th>
                                    <th>Pengelola</th>
                                    <th>File</th>
                                    <th class="w-20 text-center">Aksi</th> {{-- REVISI --}}
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($brs_list as $brs)
                                    <tr class="hover">
                                        <th>{{ $loop->iteration + ($brs_list->currentPage() - 1) * $brs_list->perPage() }}</th>
                                        <td class="whitespace-normal font-medium">{{ $brs->judul }}</td>
                                        <td>{{ $brs->bulan->isoFormat('MMMM YYYY') }}</td>
                                        <td>{{ $brs->user->name ?? 'N/A' }}</td>

                                        {{-- REVISI: Kolom File (Icons) --}}
                                        <td class="flex gap-1 items-center">
                                            
                                            {{-- 1. PDF --}}
                                            @if ($brs->pdf_path)
                                                <div class="tooltip" data-tip="Unduh PDF">
                                                    <a href="{{ Storage::url($brs->pdf_path) }}" target="_blank" class="btn btn-ghost btn-sm btn-square">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-error" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            @endif
                                            
                                            {{-- 2. ZIP --}}
                                            @if ($brs->zip_path)
                                                <div class="tooltip" data-tip="Unduh ZIP">
                                                    <a href="{{ Storage::url($brs->zip_path) }}" target="_blank" class="btn btn-ghost btn-sm btn-square">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-secondary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                          <path stroke-linecap="round" stroke-linejoin="round" d="M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                                          <path stroke-linecap="round" stroke-linejoin="round" d="M9 3v5a2 2 0 01-2 2H5a2 2 0 01-2-2V3a2 2 0 012-2h2a2 2 0 012 2zm10 0v5a2 2 0 01-2 2h-2a2 2 0 01-2-2V3a2 2 0 012-2h2a2 2 0 012 2z" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            @endif
                                            
                                            {{-- 3. Excel (Opsional) --}}
                                            @if ($brs->excel_path)
                                                <div class="tooltip" data-tip="Unduh Excel">
                                                    <a href="{{ Storage::url($brs->excel_path) }}" target="_blank" class="btn btn-ghost btn-sm btn-square">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            @endif
                                            
                                            {{-- 4. Infografis (Dropdown) --}}
                                            @if ($brs->infografis_paths && count($brs->infografis_paths) > 0)
                                                <div class="tooltip" data-tip="Lihat Infografis ({{ count($brs->infografis_paths) }})">
                                                    <div class="dropdown dropdown-end">
                                                        <label tabindex="0" class="btn btn-ghost btn-sm btn-square relative">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-info" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                              <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                            {{-- Badge Hitungan --}}
                                                            <span class="badge badge-info badge-xs absolute -top-1 -right-1">{{ count($brs->infografis_paths) }}</span>
                                                        </label>
                                                        {{-- Dropdown Menu --}}
                                                        <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52 z-10">
                                                            @foreach ($brs->infografis_paths as $index => $path)
                                                                <li>
                                                                    <a href="{{ Storage::url($path) }}" target="_blank">
                                                                        Infografis #{{ $index + 1 }}
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                        
                                        {{-- REVISI: Kolom Aksi (View only) --}}
                                        <td class="text-center">
                                            <div class="tooltip" data-tip="Lihat Detail">
                                                {{-- Anda perlu membuat route dan method 'brs.show' agar ini berfungsi --}}
                                                <a href="{{ route('brs.show', $brs->id) }}" class="btn btn-ghost btn-sm btn-square">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                      <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-10 text-base-content/50">
                                            Tidak ada data BRS yang ditemukan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Link Paginasi --}}
            <div class="mt-6">
                {{ $brs_list->links() }}
            </div>
            
        </div>
    </div>

    {{-- Script untuk DaisyUI Tooltip agar berfungsi --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Inisialisasi ulang tooltip jika diperlukan (meskipun biasanya otomatis)
            });
        </script>
    @endpush
</x-app-layout>