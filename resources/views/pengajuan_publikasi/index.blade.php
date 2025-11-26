<x-app-layout>
    {{-- 💎 CSS Kustom --}}
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .fade-in { animation: fadeIn 0.8s ease-out forwards; }
        
        @keyframes slideUpIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
        .table-row-animated { opacity: 0; animation: slideUpIn 0.5s ease-out forwards; animation-delay: var(--delay); }
        
        .table tbody tr.hover:hover, .table-hover tbody tr:hover {
            background-color: hsl(var(--b2, 220 13% 91%) / 0.5); 
        }
        .table thead th {
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: hsl(var(--bc) / 0.7);
            border-bottom-width: 2px;
            background-color: hsl(var(--b2, 220 13% 91%) / 0.5);
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        .btn-premium, .input-premium, .select-premium {
            transition: all 0.2s ease-in-out;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            border-radius: 12px;
        }
        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }
        
        .rounded-custom { border-radius: 12px; }
        
        /* Status Badge Colors */
        .bg-draft { background-color: #9ca3af; color: white; }
        .bg-diperiksa { background-color: #3b82f6; color: white; }
        .bg-disetujui { background-color: #22c55e; color: white; }
        .bg-perbaikan { background-color: #eab308; color: white; }
        .bg-ditolak { background-color: #ef4444; color: white; }
    </style>

    {{-- 🌟 Header --}}
    <x-slot name="header">
        <div class="fade-in">
            <h2 class="font-bold text-2xl text-base-content leading-tight flex items-center gap-3">
                <div class="p-2 bg-primary/10 rounded-lg text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                </div>
                {{ __('Daftar Pengajuan Publikasi') }}
            </h2>
            <div class="text-sm breadcrumbs text-base-content/70 mt-1">
                <ul>
                    <li><a href="{{ route('dashboard') }}" class="hover:text-primary transition">Dashboard</a></li> 
                    <li>Pengajuan Publikasi</li>
                </ul>
            </div>
        </div>
    </x-slot>

    {{-- 💎 Konten Utama --}}
    <div class="py-12 fade-in">
        <div class="px-4 sm:px-6 lg:px-8">

            {{-- Flash Messages --}}
            @if (session('success')) 
                <div role="alert" class="alert alert-success mb-6 shadow-lg rounded-custom text-white border-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ session('success') }}</span>
                </div> 
            @endif
            @if (session('error')) 
                <div role="alert" class="alert alert-error mb-6 shadow-lg rounded-custom text-white border-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ session('error') }}</span>
                </div> 
            @endif

            {{-- Informasi Alur --}}
            <div role="alert" class="alert bg-base-100 border-l-4 border-info shadow-md mb-6 rounded-r-custom">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-info shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div class="text-sm">
                    <h3 class="font-bold text-base-content">Informasi Alur</h3>
                    <div class="text-base-content/70">Data Pengajuan Publikasi baru dibuat otomatis melalui menu <b>Pengajuan SPRP</b>. Halaman ini digunakan untuk monitoring, revisi, dan persetujuan akhir.</div>
                </div>
            </div>

            {{-- Card Utama --}}
            <div class="card bg-base-100 shadow-xl rounded-custom border border-base-200">
                <div class="card-body p-0">
                    
                    {{-- Toolbar / Filter --}}
                    <div class="p-6 border-b border-base-200 bg-base-50/50 rounded-t-custom">
                        <form action="{{ route('pengajuan_publikasi.index') }}" method="GET">
                            <div class="flex flex-col md:flex-row gap-4 justify-between items-center">
                                
                                {{-- Kiri: Search & Year Filter --}}
                                <div class="flex flex-col md:flex-row gap-3 flex-grow w-full md:w-auto">
                                    
                                    {{-- 1. Input Search --}}
                                    <div class="relative flex-grow w-full md:w-auto">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-base-content/50">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                        </span>
                                        <input type="text" name="search" class="input input-bordered input-premium w-full pl-10" 
                                               placeholder="Cari judul publikasi..." value="{{ $filters['search'] ?? '' }}">
                                    </div>

                                    {{-- 2. Filter Tahun (DINAMIS DARI DB) --}}
                                    {{-- Ini menggunakan variabel $availableYears dari controller --}}
                                    <select name="year" class="select select-bordered select-premium w-full md:w-40" onchange="this.form.submit()">
                                        <option value="">Semua Tahun</option>
                                        
                                        @foreach ($availableYears as $y)
                                            <option value="{{ $y }}" {{ ($filters['year'] ?? '') == $y ? 'selected' : '' }}>
                                                {{ $y }}
                                            </option>
                                        @endforeach
                                        
                                        {{-- Fallback jika DB kosong --}}
                                        @if($availableYears->isEmpty())
                                            <option value="{{ date('Y') }}">{{ date('Y') }}</option>
                                        @endif
                                    </select>

                                </div>

                                {{-- Kanan: Per Page & Reset --}}
                                <div class="flex items-center gap-3 w-full md:w-auto">
                                    <select name="per_page" class="select select-bordered select-premium w-full md:w-auto" onchange="this.form.submit()">
                                        <option value="10" {{ ($filters['per_page'] ?? 10) == 10 ? 'selected' : '' }}>10 Baris</option>
                                        <option value="25" {{ ($filters['per_page'] ?? null) == 25 ? 'selected' : '' }}>25 Baris</option>
                                        <option value="50" {{ ($filters['per_page'] ?? null) == 50 ? 'selected' : '' }}>50 Baris</option>
                                    </select>
                                    
                                    @if(request()->has('search') || request()->has('per_page') || request()->has('year'))
                                        <a href="{{ route('pengajuan_publikasi.index') }}" class="btn btn-ghost btn-premium text-error" title="Reset Filter">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </a>
                                    @endif
                                    
                                    <button type="submit" class="hidden">Cari</button> 
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- 📋 Tabel Data --}}
                    <div class="overflow-x-auto w-full">
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th class="w-12 text-center">#</th>
                                    <th class="min-w-[250px]">Judul Publikasi</th>
                                    <th>Katalog</th>
                                    <th>Rilis</th>
                                    <th>Penyusun</th>
                                    <th class="text-center">Naskah</th>
                                    <th class="text-center min-w-[140px]">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($submissions as $index => $item)
                                    <tr class="table-row-animated hover border-b border-base-100 group transition-colors duration-200" style="--delay: {{ $loop->iteration * 0.05 }}s;">
                                        
                                        <td class="text-center font-medium opacity-60">{{ $submissions->firstItem() + $index }}</td>
                                        
                                        <td class="py-4">
                                            <div class="font-bold text-base-content">{{ $item->judul_publikasi }}</div>
                                            @if($item->judul_eng)
                                                <div class="text-xs italic text-base-content/60 mt-0.5">{{ $item->judul_eng }}</div>
                                            @endif
                                            <div class="mt-2 flex gap-2">
                                                <span class="badge badge-sm badge-outline opacity-70">{{ $item->type_publikasi }}</span>
                                                <span class="badge badge-sm badge-ghost opacity-70 font-mono">{{ $item->sprp?->nomor_publikasi_final ?? 'No SPRP' }}</span>
                                            </div>
                                        </td>

                                        <td class="font-mono text-sm">{{ $item->catalog?->nomor_katalog ?? '-' }}</td>
                                        
                                        <td class="text-sm">
                                            @if($item->estimasi_rilis)
                                                <div class="flex flex-col">
                                                    <span class="font-semibold">{{ $item->estimasi_rilis->isoFormat('D MMM Y') }}</span>
                                                    <span class="text-xs opacity-60">{{ $item->estimasi_rilis->diffForHumans() }}</span>
                                                </div>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        
                                        <td class="text-sm">
                                            <div class="flex items-center gap-2">
                                                <div class="avatar placeholder">
                                                    <div class="bg-neutral-focus text-neutral-content rounded-full w-6 h-6">
                                                        <span class="text-xs">{{ substr($item->user?->name ?? '?', 0, 1) }}</span>
                                                    </div>
                                                </div>
                                                <span class="truncate max-w-[100px]" title="{{ $item->user?->name }}">{{ $item->user?->name ?? '-' }}</span>
                                            </div>
                                        </td>
                                        
                                        {{-- Link Naskah --}}
                                        <td class="text-center">
                                            @if ($item->tautan_publikasi)
                                                <a href="{{ $item->tautan_publikasi }}" target="_blank" class="btn btn-sm btn-ghost btn-square text-primary tooltip tooltip-left" data-tip="Buka Google Drive">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                                                </a>
                                            @else
                                                <span class="text-base-content/30">-</span>
                                            @endif
                                        </td>

                                        {{-- Kolom Status dengan Select (Pemeriksa/Admin) --}}
                                        <td class="text-center align-middle">
                                            <div class="flex flex-col items-center justify-center gap-2 w-full">
                                                
                                                @hasanyrole('Pemeriksa|Admin')
                                                    <select class="select select-bordered select-xs w-full max-w-[130px] status-select text-xs shadow-sm" 
                                                            data-id="{{ $item->id }}"
                                                            style="border-radius: 8px;">
                                                        <option value="draft" {{ $item->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                                        <option value="sedang_diperiksa" {{ $item->status == 'sedang_diperiksa' ? 'selected' : '' }}>Diperiksa</option>
                                                        <option value="disetujui" {{ $item->status == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                                        <option value="butuh_perbaikan" {{ $item->status == 'butuh_perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                                                        <option value="ditolak" {{ $item->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                                    </select>
                                                @endhasanyrole
                                                
                                                {{-- BADGE DISPLAY STATUS --}}
                                                <span id="badge-status-{{ $item->id }}" 
                                                      class="badge border-0 font-bold text-xs py-3 px-3 w-full max-w-[130px]
                                                      @if($item->status == 'draft') bg-draft 
                                                      @elseif($item->status == 'sedang_diperiksa') bg-diperiksa 
                                                      @elseif($item->status == 'disetujui') bg-disetujui 
                                                      @elseif($item->status == 'butuh_perbaikan') bg-perbaikan 
                                                      @elseif($item->status == 'ditolak') bg-ditolak @endif">
                                                    {{ strtoupper(str_replace('_', ' ', $item->status)) }}
                                                </span>
                                            </div>
                                        </td>

                                        {{-- Kolom Aksi --}}
                                        <td class="text-center">
                                            <div class="flex items-center justify-center gap-1">
                                                <a href="{{ route('pengajuan_publikasi.show', $item->id) }}" class="btn btn-ghost btn-xs btn-square text-info tooltip tooltip-bottom" data-tip="Detail">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                </a>

                                                <a href="{{ route('pengajuan_publikasi.edit', $item->id) }}" class="btn btn-ghost btn-xs btn-square text-warning tooltip tooltip-bottom" data-tip="Edit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                                </a>
                                                
                                                <div class="indicator">
                                                    @if($item->unread_count > 0)
                                                        <span class="indicator-item badge badge-xs badge-error"></span>
                                                    @endif
                                                    <a href="{{ route('pengajuan_publikasi.comment', $item->id) }}" class="btn btn-ghost btn-xs btn-square text-success tooltip tooltip-bottom" data-tip="Komentar">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" /></svg>
                                                    </a>
                                                </div>

                                                @role('Admin')
                                                <form action="{{ route('pengajuan_publikasi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus permanen?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-ghost btn-xs btn-square text-error tooltip tooltip-bottom" data-tip="Hapus">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    </button>
                                                </form>
                                                @endrole
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-16">
                                            <div class="flex flex-col items-center justify-center text-base-content/50">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-4 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                                <span class="text-lg font-medium">Belum ada data publikasi.</span>
                                                <span class="text-sm">Silakan buat pengajuan baru di menu SPRP.</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginasi --}}
                    @if($submissions->hasPages())
                        <div class="p-6 border-t border-base-200">
                             {{ $submissions->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ⚙️ Script Update Status --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            const statusColors = {
                'draft': 'bg-draft',
                'sedang_diperiksa': 'bg-diperiksa',
                'disetujui': 'bg-disetujui',
                'butuh_perbaikan': 'bg-perbaikan',
                'ditolak': 'bg-ditolak'
            };

            document.querySelectorAll('.status-select').forEach(select => {
                let previousValue;
                select.addEventListener('focus', function () {
                    previousValue = this.value;
                });

                select.addEventListener('change', function() {
                    const id = this.dataset.id;
                    const newValue = this.value;
                    const statusText = this.options[this.selectedIndex].text;
                    const badge = document.getElementById(`badge-status-${id}`);

                    Swal.fire({
                        title: 'Konfirmasi Perubahan',
                        text: `Ubah status menjadi "${statusText}"? ${newValue === 'disetujui' ? 'Sistem akan otomatis memindahkan file ke Google Drive Kantor (Mungkin memakan waktu beberapa detik).' : ''}`,
                        icon: newValue === 'disetujui' ? 'info' : 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Proses!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Sedang Memproses...',
                                html: 'Mohon tunggu sebentar...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading()
                                }
                            });

                            fetch(`/pengajuan-publikasi/update-status/${id}`, { 
                                method: 'POST', 
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({ status: newValue })
                            })
                            .then(async res => {
                                const data = await res.json();
                                if (!res.ok) throw new Error(data.message || `HTTP error! status: ${res.status}`);
                                return data;
                            })
                            .then(data => {
                                if (data.success) {
                                    badge.textContent = newValue.replace(/_/g, ' ').toUpperCase();
                                    Object.values(statusColors).forEach(cls => badge.classList.remove(cls));
                                    badge.classList.add(statusColors[newValue] || 'bg-gray-400');

                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: data.message, 
                                        timer: 3000,
                                        showConfirmButton: true
                                    });
                                    previousValue = newValue;
                                } else {
                                    throw new Error(data.message);
                                }
                            })
                            .catch((error) => {
                                this.value = previousValue;
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: error.message || 'Terjadi kesalahan saat menghubungi server.'
                                });
                            });
                        } else {
                            this.value = previousValue;
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>