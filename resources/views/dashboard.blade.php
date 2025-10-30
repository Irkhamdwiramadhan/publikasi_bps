<x-app-layout>
    <style>
        /* HAPUS efek fade awal agar tidak menghilang */
        .stat-card {
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        }

        /* Warna dan gaya elegan */
        .dashboard-title {
            color: #ffffff; /* Putih */
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }

        /* Header Section */
        .header-bar {
            background: linear-gradient(90deg, #60a5fa 0%, #3b82f6 100%);
            border-radius: 10px;
            padding: 1rem 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        /* Tombol tahun */
        .btn-year-active {
            background-color: #2563eb;
            color: white;
        }
        .btn-year-inactive {
            background-color: #e2e8f0;
            color: #1e293b;
        }
        .btn-year-inactive:hover {
            background-color: #cbd5e1;
        }
    </style>

    <x-slot name="header">
        <div class="header-bar flex flex-col md:flex-row justify-between items-center">
            <div>
                <h2 class="dashboard-title text-2xl font-bold leading-tight">
                    📊 {{ __('Dasbor') }}
                </h2>
                <div class="text-sm breadcrumbs text-blue-100 mt-1">
                    <ul>
                        <li><a href="{{ route('dashboard') }}" class="hover:text-white">Beranda</a></li> 
                        <li>Dasbor</li>
                    </ul>
                </div>
            </div>

            {{-- Filter Tahun --}}
            <div class="flex items-center gap-2 mt-3 md:mt-0">
                <span class="text-sm font-medium text-blue-100">Pilih Tahun:</span>
                <div class="join border border-blue-300 rounded-lg overflow-hidden">
                    @foreach($availableYears->take(3) as $yearOption)
                        <a href="{{ route('dashboard', ['year' => $yearOption]) }}" 
                           class="join-item btn btn-sm border-none {{ $selectedYear == $yearOption ? 'btn-year-active' : 'btn-year-inactive' }}">
                            {{ $yearOption }}
                        </a>
                    @endforeach
                    @if($availableYears->count() > 3)
                        <select class="select select-sm join-item border-none bg-blue-50 focus:outline-none" 
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

    <div class="py-10">
        <div class="px-4 sm:px-6 lg:px-8">
            <h3 class="text-lg font-bold text-slate-700 mb-6">Ringkasan Publikasi Tahun {{ $selectedYear }}</h3>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Card Publikasi ARC --}}
                <div class="card bg-white shadow-lg hover:shadow-xl transition rounded-xl">
                    <div class="card-body p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h2 class="card-title text-slate-600 font-semibold">Publikasi ARC</h2>
                                <p class="text-4xl font-bold text-green-500 mt-1">{{ $arcSummary['total'] }}</p>
                            </div>
                            <div class="bg-green-100 p-3 rounded-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>
                            </div>
                        </div>

                        <div class="bg-blue-50 p-4 rounded-lg mb-4 flex justify-between items-center">
                            <span class="font-medium text-slate-600">Publikasi Masuk</span>
                            <span class="font-semibold text-lg text-slate-800">{{ $arcSummary['publikasi_masuk'] }}</span>
                        </div>

                        <div class="grid grid-cols-2 gap-4 text-center">
                            <div class="stat-card bg-blue-50 p-4 rounded-lg">
                                <div class="text-2xl font-semibold text-blue-500">{{ $arcSummary['sedang_diperiksa'] }}</div>
                                <div class="text-xs text-slate-600 mt-1">Sedang Diperiksa</div>
                            </div>
                            <div class="stat-card bg-blue-50 p-4 rounded-lg">
                                <div class="text-2xl font-semibold text-yellow-500">{{ $arcSummary['butuh_perbaikan'] }}</div>
                                <div class="text-xs text-slate-600 mt-1">Butuh Perbaikan</div>
                            </div>
                            <div class="stat-card bg-blue-50 p-4 rounded-lg">
                                <div class="text-2xl font-semibold text-green-500">{{ $arcSummary['disetujui'] }}</div>
                                <div class="text-xs text-slate-600 mt-1">Disetujui</div>
                            </div>
                            <div class="stat-card bg-blue-50 p-4 rounded-lg">
                                <div class="text-2xl font-semibold text-red-500">{{ $arcSummary['ditolak'] }}</div>
                                <div class="text-xs text-slate-600 mt-1">Ditolak</div>
                            </div>
                        </div>

                        <div class="card-actions mt-6 justify-end">
                            <a href="#" class="btn btn-ghost btn-sm text-green-500 hover:bg-green-100"> 
                                Lihat Detail
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Card Publikasi Non ARC --}}
                <div class="card bg-white shadow-lg hover:shadow-xl transition rounded-xl">
                    <div class="card-body p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h2 class="card-title text-slate-600 font-semibold">Publikasi Non ARC</h2>
                                <p class="text-4xl font-bold text-yellow-500 mt-1">{{ $nonArcSummary['total'] }}</p>
                            </div>
                            <div class="bg-yellow-100 p-3 rounded-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                            </div>
                        </div>

                        <div class="bg-blue-50 p-4 rounded-lg mb-4 flex justify-between items-center">
                            <span class="font-medium text-slate-600">Publikasi Masuk</span>
                            <span class="font-semibold text-lg text-slate-800">{{ $nonArcSummary['publikasi_masuk'] }}</span>
                        </div>

                        <div class="grid grid-cols-2 gap-4 text-center">
                            <div class="stat-card bg-blue-50 p-4 rounded-lg">
                                <div class="text-2xl font-semibold text-blue-500">{{ $nonArcSummary['sedang_diperiksa'] }}</div>
                                <div class="text-xs text-slate-600 mt-1">Sedang Diperiksa</div>
                            </div>
                            <div class="stat-card bg-blue-50 p-4 rounded-lg">
                                <div class="text-2xl font-semibold text-yellow-500">{{ $nonArcSummary['butuh_perbaikan'] }}</div>
                                <div class="text-xs text-slate-600 mt-1">Butuh Perbaikan</div>
                            </div>
                            <div class="stat-card bg-blue-50 p-4 rounded-lg">
                                <div class="text-2xl font-semibold text-green-500">{{ $nonArcSummary['disetujui'] }}</div>
                                <div class="text-xs text-slate-600 mt-1">Disetujui</div>
                            </div>
                            <div class="stat-card bg-blue-50 p-4 rounded-lg">
                                <div class="text-2xl font-semibold text-red-500">{{ $nonArcSummary['ditolak'] }}</div>
                                <div class="text-xs text-slate-600 mt-1">Ditolak</div>
                            </div>
                        </div>

                        <div class="card-actions mt-6 justify-end">
                            <a href="#" class="btn btn-ghost btn-sm text-yellow-500 hover:bg-yellow-100">
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
