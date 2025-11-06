<x-app-layout>
    {{-- CSS Kustom (Konsisten) --}}
    <style>
        body { font-family: 'Inter', sans-serif; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .fade-in { animation: fadeIn 0.8s ease-out forwards; }
        .rounded-\[15px\] { border-radius: 15px; }

        /* REVISI: Hapus semua style .carousel-image 
         karena carousel sudah dihapus.
        */
    </style>

    {{-- Header Halaman (Tetap sama) --}}
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center fade-in">
            <div>
                <h2 class="font-semibold text-xl text-base-content leading-tight">
                    Detail Berita Resmi Statistik
                </h2>
                <div class="text-sm breadcrumbs text-base-content/70">
                     <ul>
                        <li><a href="{{ route('dashboard') }}" class="hover:text-primary">Dashboard</a></li>
                        <li><a href="{{ route('brs.index') }}" class="hover:text-primary">Data BRS</a></li>
                        <li class="truncate max-w-xs">{{ $brs->judul ?? 'Detail' }}</li>
                    </ul>
                </div>
            </div>
            
            <div>
                <a href="{{ route('brs.index') }}" class="btn btn-ghost btn-sm rounded-[15px]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    {{-- Konten Utama --}}
    <div class="py-12 fade-in">
        <div class="px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto">

            <div class="card bg-base-100 shadow-xl rounded-[15px]">
                <div class="card-body p-6 md:p-8">

                    {{-- 1. Judul Utama --}}
                    <h2 class="card-title text-3xl font-bold text-base-content mb-4">
                        {{ $brs->judul ?? 'Judul Tidak Ditemukan' }}
                    </h2>

                    {{-- 2. Info Meta (Bulan & Pengelola) --}}
                    <div class="flex flex-col md:flex-row gap-x-8 gap-y-2 mb-6 text-base-content/80">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Bulan: <strong>{{ $brs->bulan?->isoFormat('MMMM YYYY') ?? 'Tanggal Tidak Diatur' }}</strong></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Pengelola: <strong>{{ $brs->user->name ?? 'N/A' }}</strong></span>
                        </div>
                    </div>

                    {{-- 
                      =====================================
                      3. GALERI INFOGRAFIS (REVISI: TAMPIL SEBAGAI GRID)
                      =====================================
                    --}}
                    @if ($brs->infografis_paths && count($brs->infografis_paths) > 0)
                        <div class="mt-4 mb-6">
                            <h3 class="text-lg font-semibold text-base-content mb-3">Galeri Infografis</h3>
                            
                            {{-- Tampilkan sebagai grid 2 atau 3 kolom --}}
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach ($brs->infografis_paths as $index => $path)
                                    {{-- Setiap gambar bisa diklik untuk dibuka di tab baru --}}
                                    <a href="{{ Storage::url($path) }}" target="_blank" 
                                       class="block rounded-[15px] overflow-hidden shadow-lg 
                                              transition-transform duration-300 hover:scale-105"
                                       title="Lihat Infografis {{ $index + 1 }}">
                                        
                                        <img src="{{ Storage::url($path) }}" 
                                             class="w-full h-48 object-cover" {{-- h-48 = tinggi 12rem, object-cover = penuhi frame --}}
                                             alt="Infografis {{ $index + 1 }}" />
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    {{-- Batas Blok Galeri --}}


                    <div class="divider"></div>

                    {{-- 4. Tombol Download --}}
                    <h3 class="text-lg font-semibold text-base-content mb-3">File Terkait</h3>
                    <div class="flex flex-wrap gap-3">
                        {{-- PDF --}}
                        @if ($brs->pdf_path)
                            <a href="{{ Storage::url($brs->pdf_path) }}" target="_blank" class="btn btn-outline btn-error gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Unduh PDF
                            </a>
                        @endif
                        
                        {{-- ZIP --}}
                        @if ($brs->zip_path)
                            <a href="{{ Storage::url($brs->zip_path) }}" target="_blank" class="btn btn-outline btn-secondary gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 3v5a2 2 0 01-2 2H5a2 2 0 01-2-2V3a2 2 0 012-2h2a2 2 0 012 2zm10 0v5a2 2 0 01-2 2h-2a2 2 0 01-2-2V3a2 2 0 012-2h2a2 2 0 012 2z" />
                                </svg>
                                Unduh ZIP
                            </a>
                        @endif
                        
                        {{-- Excel --}}
                        @if ($brs->excel_path)
                            <a href="{{ Storage::url($brs->excel_path) }}" target="_blank" class="btn btn-outline btn-success gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                Unduh Excel
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>