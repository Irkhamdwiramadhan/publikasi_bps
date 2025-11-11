<x-app-layout>
    {{-- 💎 CSS Kustom (Menambahkan animasi baris dan styling premium) --}}
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.8s ease-out forwards;
        }

        @keyframes slideUpIn {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .table-row-animated {
            opacity: 0;
            animation: slideUpIn 0.5s ease-out forwards;
            animation-delay: var(--delay);
        }

        .table tbody tr.hover:hover,
        .table-hover tbody tr:hover {
            background-color: hsl(var(--b2, 220 13% 91%) / 0.5);
        }

        .table thead th {
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: hsl(var(--bc) / 0.7);
            border-bottom-width: 2px;
            background-color: hsl(var(--b2, 220 13% 91%) / 0.3);
        }

        .btn-premium {
            transition: all 0.2s ease-in-out;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        }

        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }

        .tooltip:before {
            font-size: 0.75rem;
            padding: 0.3rem 0.6rem;
        }

        .rounded-\[15px\] {
            border-radius: 15px;
        }

        /* Style untuk form filter agar konsisten */
        .border-premium {
            border-width: 1px;
            border-color: hsl(var(--bc) / 0.2);
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            transition: all 0.2s ease-in-out;
            border-radius: 15px;
            /* Disesuaikan dengan rounded-[15px] */
        }
    </style>

    {{-- 🌟 Header (Dibuat konsisten) --}}
    <x-slot name="header">
        <div class="fade-in">
            <h2 class="font-semibold text-xl text-base-content leading-tight flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                </svg>
                {{ __('Daftar Pengajuan Publikasi') }}
            </h2>
            <div class="text-sm breadcrumbs text-base-content/70">
                <ul>
                    <li><a href="{{ route('dashboard') }}" class="hover:text-primary">Dashboard</a></li>
                    <li>Pengajuan Publikasi</li>
                </ul>
            </div>
        </div>
    </x-slot>

    {{-- 💎 Konten Utama --}}
    <div class="py-12 fade-in">
        <div class="px-4 sm:px-6 lg:px-8"> {{-- Dibuat full-width agar tabel muat --}}

            @if (session('success')) <div role="alert" class="alert alert-success mb-5 shadow-lg rounded-[15px]"><span>{{ session('success') }}</span></div> @endif
            @if (session('error')) <div role="alert" class="alert alert-error mb-5 shadow-lg rounded-[15px]"><span>{{ session('error') }}</span></div> @endif

            {{-- ▼▼▼ REVISI PENTING ▼▼▼ --}}
            {{-- Menghapus tombol 'Tambah' dan menggantinya dengan Info --}}
            <div role="alert" class="alert alert-info mb-5 shadow-lg rounded-[15px] text-white">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-white"><b>Informasi:</b> Gunakan menu SPRP untuk menambahkan pengajuan publikasi dan gunakan icon comment untuk konfirmasi status publikasi <b>Contoh ketika sudah direvisi</b>.</span>
            </div>
            {{-- ▲▲▲ AKHIR REVISI ▲▲▲ --}}

            <div class="card bg-base-100 shadow-xl rounded-[15px]">
                <div class="card-body p-6 md:p-8">



                    {{-- ▼▼▼ REVISI: Menambahkan Form Filter (dari Controller) ▼▼▼ --}}
                    <form action="{{ route('pengajuan_publikasi.index') }}" method="GET" class="mb-6">
                        <div class="flex flex-col md:flex-row gap-4 md:gap-3 md:items-center">
                            <!-- Jumlah per halaman -->
                            <div class="flex items-center">
                                <label for="per_page" class="text-sm font-medium text-base-content/70 mr-3 whitespace-nowrap">Tampil per</label>
                                <select id="per_page" name="per_page" class="select select-bordered w-full md:w-auto border-premium" onchange="this.form.submit()">
                                    <option value="10" {{ ($filters['per_page'] ?? 10) == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ ($filters['per_page'] ?? null) == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ ($filters['per_page'] ?? null) == 50 ? 'selected' : '' }}>50</option>
                                </select>
                            </div>

                            <!-- Tahun -->
                            <div class="flex items-center">
                                <label for="tahun" class="text-sm font-medium text-base-content/70 mr-3 whitespace-nowrap">Tahun</label>
                                <select id="tahun" name="tahun" class="select select-bordered w-full md:w-auto border-premium" onchange="this.form.submit()">
                                    <option value="">Semua</option>
                                    @php
                                    $currentYear = now()->year;
                                    for ($y = $currentYear; $y >= 2025; $y--) {
                                    $selected = ($filters['tahun'] ?? '') == $y ? 'selected' : '';
                                    echo "<option value='$y' $selected>$y</option>";
                                    }
                                    @endphp
                                </select>
                            </div>

                            <!-- Cari -->
                            <div class="flex items-center flex-grow">
                                <label for="search" class="text-sm font-medium text-base-content/70 mr-3 whitespace-nowrap">Cari</label>
                                <input type="text" id="search" name="search" class="input input-bordered w-full border-premium"
                                    placeholder="Ketik judul publikasi..." value="{{ $filters['search'] ?? '' }}">
                            </div>

                            <div class="flex gap-2">
                                <button type="submit" class="btn btn-primary btn-premium text-yellow ">Cari</button>
                                <a href="{{ route('pengajuan_publikasi.index') }}" class="btn btn-ghost btn-premium" title="Reset Filter">Reset</a>
                            </div>
                        </div>
                    </form>

                    {{-- ▲▲▲ AKHIR REVISI ▲▲▲ --}}

                    {{-- 📋 Tabel Data --}}
                    <div class="overflow-x-auto">
                        <table class="table w-full text-sm">
                            <thead>
                                {{-- ▼▼▼ REVISI: Header Tabel Baru (Sesuai Permintaan Anda) ▼▼▼ --}}
                                <tr class="text-center">
                                    <th>#</th>
                                    <th class="text-left">Judul</th>
                                    <th>Tipe</th>
                                    <th>Katalog</th>
                                    <th>No Publikasi</th>
                                    <th>Rilis</th>
                                    <th>Pembuat</th>
                                    <th>Link Publikasi</th>
                                    <th>Status</th>

                                    <th>Aksi</th>
                                </tr>
                                {{-- ▲▲▲ AKHIR REVISI ▲▲▲ --}}
                            </thead>
                            <tbody>
                                @forelse ($submissions as $index => $item) {{-- $item adalah SubmissionPublication --}}
                                <tr class="table-row-animated hover text-center border-b border-base-200" style="--delay: {{ $loop->iteration * 0.05 }}s;">

                                    <td class="py-3 font-medium text-gray-700">{{ $submissions->firstItem() + $index }}</td>

                                    {{-- ▼▼▼ REVISI: Sumber Data Baru ▼▼▼ --}}
                                    <td class="font-semibold text-base-content text-left px-3 py-3 whitespace-normal">
                                        {{ $item->judul_publikasi }}
                                        @if($item->judul_eng)
                                        <span class="text-xs italic text-base-content/60 block">{{ $item->judul_eng }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->type_publikasi == 'ARC')
                                        <span class="badge badge-success badge-sm text-white font-semibold">{{ $item->type_publikasi }}</span>
                                        @else
                                        <span class="badge badge-info badge-sm text-white font-semibold">{{ $item->type_publikasi }}</span>
                                        @endif
                                    </td>
                                    <td class="font-mono">{{ $item->catalog?->nomor_katalog ?? 'N/A' }}</td>
                                    <td class="font-mono">{{ $item->sprp?->nomor_publikasi_final ?? 'N/A' }}</td>
                                    <td>{{ $item->estimasi_rilis ? $item->estimasi_rilis->isoFormat('MM-DD-YYYY') : 'N/A' }}</td>

                                    <td>{{ $item->user?->name ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        @if ($item->tautan_publikasi)
                                        <div class="tooltip tooltip-bottom" data-tip="Lihat Naskah">
                                            <a href="{{ $item->tautan_publikasi }}" target="_blank" class="text-primary hover:text-primary-focus">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                                </svg>
                                            </a>
                                        </div>
                                        @else
                                        <span class="text-xs opacity-50">-</span>
                                        @endif
                                    </td>
                                    {{-- ▲▲▲ AKHIR REVISI SUMBER DATA ▲▲▲ --}}

                                    {{-- Kolom Status (Logika dipertahankan) --}}
                                    <td class="text-center space-y-1 py-2">
                                        @hasanyrole('Pemeriksa|Admin')
                                        <select class="select select-bordered select-xs w-full max-w-xs status-select rounded-[15px]" data-id="{{ $item->id }}">
                                            <option value="draft" {{ $item->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                            <option value="sedang_diperiksa" {{ $item->status == 'sedang_diperiksa' ? 'selected' : '' }}>Diperiksa</option>
                                            <option value="disetujui" {{ $item->status == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                            <option value="butuh_perbaikan" {{ $item->status == 'butuh_perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                                            <option value="ditolak" {{ $item->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                        </select>
                                        @endhasanyrole

                                        <span class="badge badge-sm mt-1 px-2 py-1 text-white font-medium 
                                                @if($item->status == 'draft') bg-gray-400 @elseif($item->status == 'sedang_diperiksa') bg-blue-500 @elseif($item->status == 'disetujui') bg-green-500 @elseif($item->status == 'butuh_perbaikan') bg-yellow-500 @elseif($item->status == 'ditolak') bg-red-500 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                        </span>
                                    </td>



                                    {{-- Kolom Aksi (Logika dipertahankan) --}}
                                    <td class="py-2">
                                        <div class="flex justify-center gap-1">
                                            @role('Penyusun|Pemeriksa|Admin') {{-- REVISI: Izinkan Pemeriksa edit --}}
                                            <div class="tooltip tooltip-left" data-tip="Edit Pengajuan">
                                                <a href="{{ route('pengajuan_publikasi.edit', $item->id) }}"
                                                    class="btn btn-ghost btn-xs btn-circle text-warning hover:bg-warning/10">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                </a>
                                            </div>
                                            @endrole

                                            @hasanyrole('Pemeriksa|Penyusun|Pimpinan')
                                            <div class="tooltip tooltip-left" data-tip="Lihat/Tambah Komentar">
                                                <a href="{{ route('pengajuan_publikasi.comment', $item->id) }}"
                                                    class="btn btn-ghost btn-xs btn-circle text-info hover:bg-info/10 relative">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                                    </svg>
                                                    @if($item->unread_count > 0)
                                                    <span class="badge badge-xs badge-error absolute -top-1 -right-1">{{ $item->unread_count }}</span>
                                                    @endif
                                                </a>
                                            </div>
                                            @endhasanyrole

                                            <div class="tooltip tooltip-left" data-tip="Lihat Detail">
                                                <a href="{{ route('pengajuan_publikasi.show', $item->id) }}"
                                                    class="btn btn-ghost btn-xs btn-circle text-primary hover:bg-primary/10">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                            </div>

                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    {{-- REVISI: Colspan jadi 12 --}}
                                    <td colspan="12" class="text-center py-16 text-base-content/50">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0l2.25 1.75a3 3 0 013.5 0L17 7m-12 0" />
                                        </svg>
                                        Belum ada pengajuan publikasi ditemukan.
                                        <span class="block text-sm">Data baru akan muncul di sini setelah dibuat dari menu "Pengajuan SPRP".</span>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginasi --}}
                    @if($submissions->hasPages())
                    <div class="mt-6">
                        {{ $submissions->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ⚙️ Script Update Status (Dipertahankan dari file Anda) --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.status-select').forEach(select => {
            select.addEventListener('change', function() {
                const id = this.dataset.id;
                const value = this.value;
                const badge = this.parentElement.querySelector('.badge');
                const initialValue = badge.textContent.trim().toLowerCase().replace(/ /g, '_');

                Swal.fire({
                    title: 'Ubah Status?',
                    text: `Anda yakin ingin mengubah status menjadi "${this.options[this.selectedIndex].text}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Ubah!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/pengajuan_publikasi/${id}/update-status`, {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    status: value
                                })
                            })
                            .then(res => {
                                if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
                                return res.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    badge.textContent = value.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                                    const colorClasses = {
                                        'draft': 'bg-gray-400',
                                        'sedang_diperiksa': 'bg-blue-500',
                                        'disetujui': 'bg-green-500',
                                        'butuh_perbaikan': 'bg-yellow-500',
                                        'ditolak': 'bg-red-500'
                                    };
                                    badge.className = 'badge badge-sm mt-1 px-2 py-1 text-white font-medium ' + (colorClasses[value] || 'bg-gray-400');
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: 'Status diperbarui.',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                } else {
                                    this.value = initialValue;
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal!',
                                        text: data.message || 'Gagal memperbarui status.'
                                    });
                                }
                            })
                            .catch((error) => {
                                this.value = initialValue;
                                console.error('Fetch error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: 'Terjadi kesalahan koneksi.'
                                });
                            });
                    } else {
                        this.value = initialValue;
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>