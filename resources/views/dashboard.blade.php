<x-app-layout>
    {{-- CSS Kustom & Animasi --}}
    <style>
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .fade-in { animation: fadeIn 0.5s ease-out forwards; opacity: 0; }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 6px 15px rgba(0,0,0,0.07); transition: all 0.2s ease-out; }
        /* Style untuk tombol tahun aktif */
        .btn-year-active { background-color: hsl(var(--p)); color: hsl(var(--pc)); }
        .btn-year-inactive { background-color: hsl(var(--b2)); color: hsl(var(--bc)); }
        .btn-year-inactive:hover { background-color: hsl(var(--b3)); }
    </style>

    {{-- Header Halaman --}}
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center fade-in">
            {{-- Breadcrumbs & Title --}}
            <div>
                <h2 class="font-semibold text-xl text-base-content leading-tight">
                    📊 {{ __('Dasbor') }}
                </h2>
                {{-- Breadcrumbs (opsional, jika Anda punya struktur) --}}
                <div class="text-sm breadcrumbs text-base-content/70 mt-1">
                    <ul>
                        <li><a href="{{ route('dashboard') }}" class="hover:text-primary">Beranda</a></li> 
                        <li>Dasbor</li>
                    </ul>
                </div>
            </div>

            {{-- Filter Tahun --}}
            <div class="flex items-center gap-2 mt-3 md:mt-0">
                <span class="text-sm font-medium text-base-content/70">Pilih Tahun:</span>
                <div class="join border border-base-300 rounded-lg overflow-hidden">
                    {{-- Loop untuk tahun tersedia --}}
                    @foreach($availableYears->take(3) as $yearOption) {{-- Ambil 3 tahun terakhir --}}
                        <a href="{{ route('dashboard', ['year' => $yearOption]) }}" 
                           class="join-item btn btn-sm border-none {{ $selectedYear == $yearOption ? 'btn-year-active' : 'btn-year-inactive' }}">
                            {{ $yearOption }}
                        </a>
                    @endforeach
                     {{-- Tambahkan dropdown jika > 3 tahun --}}
                    @if($availableYears->count() > 3)
                        <select class="select select-sm join-item border-none rounded-none bg-base-200 focus:outline-none focus:border-none focus:ring-0" 
                                onchange="if (this.value) window.location.href='{{ route('dashboard') }}?year='+this.value;">
                            <option value="" disabled {{ !in_array($selectedYear, $availableYears->take(3)->toArray()) ? 'selected' : '' }}>Lainnya</option>
                            @foreach($availableYears->slice(3) as $yearOption)
                                <option value="{{ $yearOption }}" {{ $selectedYear == $yearOption ? 'selected' : '' }}>{{ $yearOption }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
            </div>
        </div>
    </x-slot>

    {{-- Konten Utama --}}
    <div class="py-12">
        <div class="px-4 sm:px-6 lg:px-8">
            
            <h3 class="text-lg font-bold text-base-content/80 mb-6 fade-in" style="animation-delay: 0.1s;">Ringkasan Publikasi Tahun {{ $selectedYear }}</h3>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Card Publikasi ARC --}}
                <div class="card bg-base-100 shadow-xl fade-in" style="animation-delay: 0.2s;">
                    <div class="card-body p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h2 class="card-title text-base-content/70 font-semibold">Publikasi ARC</h2>
                                <p class="text-4xl font-bold text-success mt-1">{{ $arcSummary['total'] }}</p>
                            </div>
                            <div class="bg-success/10 p-3 rounded-xl">
                                {{-- Ikon ARC --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>
                            </div>
                        </div>

                        {{-- Total Masuk (Sesuaikan jika perlu) --}}
                        <div class="bg-base-200/60 p-4 rounded-lg mb-4 flex justify-between items-center">
                            <span class="font-medium text-base-content/80">Publikasi Masuk</span>
                            <span class="font-semibold text-lg text-base-content">{{ $arcSummary['publikasi_masuk'] }}</span>
                        </div>

                        {{-- Grid Status Detail --}}
                        <div class="grid grid-cols-2 gap-4 text-center">
                            <div class="stat-card bg-base-200/40 p-4 rounded-lg transition-all duration-200">
                                <div class="text-2xl font-semibold text-info">{{ $arcSummary['sedang_diperiksa'] }}</div>
                                <div class="text-xs text-base-content/70 mt-1">Sedang Diperiksa</div>
                            </div>
                            <div class="stat-card bg-base-200/40 p-4 rounded-lg transition-all duration-200">
                                <div class="text-2xl font-semibold text-warning">{{ $arcSummary['butuh_perbaikan'] }}</div>
                                <div class="text-xs text-base-content/70 mt-1">Butuh Perbaikan</div>
                            </div>
                            <div class="stat-card bg-base-200/40 p-4 rounded-lg transition-all duration-200">
                                <div class="text-2xl font-semibold text-success">{{ $arcSummary['disetujui'] }}</div>
                                <div class="text-xs text-base-content/70 mt-1">Disetujui</div>
                            </div>
                            <div class="stat-card bg-base-200/40 p-4 rounded-lg transition-all duration-200">
                                <div class="text-2xl font-semibold text-error">{{ $arcSummary['ditolak'] }}</div>
                                <div class="text-xs text-base-content/70 mt-1">Ditolak</div>
                            </div>
                        </div>

                        <div class="card-actions mt-6 justify-end">
                            {{-- Sesuaikan route detail jika ada --}}
                            <a href="#" class="btn btn-ghost btn-sm text-success hover:bg-success/10"> 
                                Lihat Detail
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Card Publikasi Non ARC --}}
                <div class="card bg-base-100 shadow-xl fade-in" style="animation-delay: 0.3s;">
                    <div class="card-body p-6">
                         <div class="flex justify-between items-start mb-4">
                            <div>
                                <h2 class="card-title text-base-content/70 font-semibold">Publikasi Non. ARC</h2>
                                <p class="text-4xl font-bold text-warning mt-1">{{ $nonArcSummary['total'] }}</p>
                            </div>
                            <div class="bg-warning/10 p-3 rounded-xl">
                                {{-- Ikon Non ARC --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-warning" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                            </div>
                        </div>

                         {{-- Total Masuk (Sesuaikan jika perlu) --}}
                        <div class="bg-base-200/60 p-4 rounded-lg mb-4 flex justify-between items-center">
                            <span class="font-medium text-base-content/80">Publikasi Masuk</span>
                            <span class="font-semibold text-lg text-base-content">{{ $nonArcSummary['publikasi_masuk'] }}</span>
                        </div>

                         {{-- Grid Status Detail --}}
                        <div class="grid grid-cols-2 gap-4 text-center">
                            <div class="stat-card bg-base-200/40 p-4 rounded-lg transition-all duration-200">
                                <div class="text-2xl font-semibold text-info">{{ $nonArcSummary['sedang_diperiksa'] }}</div>
                                <div class="text-xs text-base-content/70 mt-1">Sedang Diperiksa</div>
                            </div>
                            <div class="stat-card bg-base-200/40 p-4 rounded-lg transition-all duration-200">
                                <div class="text-2xl font-semibold text-warning">{{ $nonArcSummary['butuh_perbaikan'] }}</div>
                                <div class="text-xs text-base-content/70 mt-1">Butuh Perbaikan</div>
                            </div>
                            <div class="stat-card bg-base-200/40 p-4 rounded-lg transition-all duration-200">
                                <div class="text-2xl font-semibold text-success">{{ $nonArcSummary['disetujui'] }}</div>
                                <div class="text-xs text-base-content/70 mt-1">Disetujui</div>
                            </div>
                            <div class="stat-card bg-base-200/40 p-4 rounded-lg transition-all duration-200">
                                <div class="text-2xl font-semibold text-error">{{ $nonArcSummary['ditolak'] }}</div>
                                <div class="text-xs text-base-content/70 mt-1">Ditolak</div>
                            </div>
                        </div>
                        
                         <div class="card-actions mt-6 justify-end">
                             {{-- Sesuaikan route detail jika ada --}}
                            <a href="#" class="btn btn-ghost btn-sm text-warning hover:bg-warning/10">
                                Lihat Detail
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>