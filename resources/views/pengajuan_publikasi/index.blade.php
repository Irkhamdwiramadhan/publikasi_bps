<x-app-layout>
    {{-- 💎 CSS Kustom (Menambahkan animasi baris dan styling tooltip) --}}
    <style>
        body { font-family: 'Inter', sans-serif; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .fade-in { animation: fadeIn 0.8s ease-out forwards; }
        
        /* REVISI: Menambahkan animasi slideUpIn untuk baris tabel */
        @keyframes slideUpIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
        .table-row-animated { opacity: 0; animation: slideUpIn 0.5s ease-out forwards; animation-delay: var(--delay); }
        
        .table tbody tr.hover:hover, .table-hover tbody tr:hover {
            background-color: hsl(var(--b2, 220 13% 91%) / 0.5); /* Warna hover halus */
        }
        .table thead th {
            font-weight: 600; /* Lebih tebal */
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: hsl(var(--bc) / 0.7); /* Sedikit lebih gelap */
            border-bottom-width: 2px;
            background-color: hsl(var(--b2, 220 13% 91%) / 0.3); /* Background header halus */
        }
        .btn-premium {
            transition: all 0.2s ease-in-out;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        }
        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }
        /* Styling Tooltip DaisyUI */
        .tooltip:before {
            font-size: 0.75rem; /* Ukuran teks tooltip */
            padding: 0.3rem 0.6rem;
        }
        .border{
            border-radius: 15px;
        }
    </style>

    {{-- 🌟 Header (Dibuat konsisten) --}}
    <x-slot name="header">
        <div class="fade-in">
             {{-- REVISI: Menggunakan text-base-content agar konsisten --}}
            <h2 class="font-semibold text-xl text-base-content leading-tight">
                {{ __('Daftar Pengajuan Publikasi') }}
            </h2>
            {{-- REVISI: Menggunakan text-base-content/70 --}}
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
        <div class="px-4 sm:px-6 lg:px-8">

            {{-- Alert Notifikasi --}}
            @if (session('success')) <div role="alert" class="alert alert-success mb-5 shadow-lg"><span>{{ session('success') }}</span></div> @endif
            @if (session('error')) <div role="alert" class="alert alert-error mb-5 shadow-lg"><span>{{ session('error') }}</span></div> @endif

            {{-- REVISI: Menggunakan Card seperti halaman lain --}}
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body p-6 md:p-8">
            
                    {{-- Header Card --}}
                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                        <h3 class=" text-xl font-bold text-base-content/90">Data Pengajuan Publikasi</h3>
                        <a href="{{ route('pengajuan_publikasi.create') }}"
                           class="border btn btn-primary btn-premium w-full md:w-auto text-white flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                            Tambah Pengajuan
                        </a>
                    </div>

                    {{-- 📋 Tabel Data --}}
                    <div class="overflow-x-auto">
                        <table class="table w-full text-sm">
                            <thead>
                                {{-- REVISI: Header dibuat rata tengah dan lebih jelas --}}
                                <tr class="text-center">
                                    <th>#</th>
                                    <th class="text-left">Judul Publikasi</th>
                                    <th>Jenis</th>
                                    <th>Penyusun</th>
                                    <th>Fungsi</th>
                                    <th>Tgl Ajuan</th>
                                    <th>Tgl Update</th>
                                    <th>Tautan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($submissions as $index => $item)
                                    {{-- REVISI: Menambahkan class animasi dan delay --}}
                                    <tr class="table-row-animated hover text-center border-b border-base-200" style="--delay: {{ $loop->iteration * 0.05 }}s;">
                                        
                                        <td class="py-3 font-medium text-gray-700">{{ $submissions->firstItem() + $index }}</td>
                                        <td class="font-semibold text-base-content text-left px-3 py-3 whitespace-normal"> {{-- Whitespace normal agar judul panjang bisa wrap --}}
                                            {{ $item->publication->title_ind }}
                                        </td>
                                        <td class="text-gray-600">{{ $item->publication->publication_type }}</td>
                                        <td class="text-gray-700">{{ $item->user->name }}</td>
                                        <td class="text-gray-600">{{ $item->fungsi_pengusul }}</td>
                                        <td class="text-gray-600">{{ $item->created_at->format('d M Y') }}</td> {{-- Format tanggal lebih jelas --}}
                                        <td class="text-gray-600">
                                            {{ $item->updated_at && $item->updated_at->ne($item->created_at) ? $item->updated_at->format('d M Y') : '-' }} {{-- Logic pengecekan update lebih aman --}}
                                        </td>

                                        {{-- Kolom Tautan (REVISI IKON & TOOLTIP) --}}
                                        <td class="text-center">
                                            <div class="flex justify-center gap-2">
                                                @if ($item->tautan_publikasi)
                                                    {{-- Ikon Link --}}
                                                    <div class="tooltip tooltip-bottom" data-tip="Lihat Publikasi">
                                                        <a href="{{ $item->tautan_publikasi }}" target="_blank" class="text-primary hover:text-primary-focus">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                                                        </a>
                                                    </div>
                                                @endif
                                                @if ($item->spnrs_ketua_tim)
                                                    {{-- Ikon File/Dokumen (Contoh) --}}
                                                    <div class="tooltip tooltip-bottom" data-tip="Lihat SPNRS Ketua Tim">
                                                        <a href="{{ $item->spnrs_ketua_tim }}" target="_blank" class="text-success hover:text-success-focus">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                                        </a>
                                                    </div>
                                                @endif
                                                {{-- Jika tidak ada link sama sekali --}}
                                                @if (!$item->tautan_publikasi && !$item->spnrs_ketua_tim)
                                                   <span class="text-xs opacity-50">-</span>
                                                @endif
                                            </div>
                                        </td>

                                        {{-- Kolom Status (REVISI: Select dibuat -xs agar lebih kompak) --}}
                                        <td class="text-center space-y-1 py-2">
                                            @hasanyrole('Pemeriksa|Admin')
                                                <select class="select select-bordered select-xs w-full max-w-xs status-select" data-id="{{ $item->id }}">
                                                    <option value="draft" {{ $item->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                                    <option value="sedang_diperiksa" {{ $item->status == 'sedang_diperiksa' ? 'selected' : '' }}>Diperiksa</option>
                                                    <option value="disetujui" {{ $item->status == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                                    <option value="butuh_perbaikan" {{ $item->status == 'butuh_perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                                                    <option value="ditolak" {{ $item->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                                </select>
                                            @endhasanyrole
                                            
                                            {{-- Badge status tetap dipertahankan untuk visual cepat --}}
                                            <span class="badge badge-sm mt-1 px-2 py-1 text-white font-medium 
                                                @if($item->status == 'draft') bg-gray-400 @elseif($item->status == 'sedang_diperiksa') bg-blue-500 @elseif($item->status == 'disetujui') bg-green-500 @elseif($item->status == 'butuh_perbaikan') bg-yellow-500 @elseif($item->status == 'ditolak') bg-red-500 @endif">
                                                {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                            </span>
                                        </td>

                                        {{-- Kolom Aksi (REVISI IKON & TOOLTIP) --}}
                                        <td class="py-2">
                                            <div class="flex justify-center gap-1">
                                                {{-- Tombol Edit --}}
                                                @role('Penyusun')
                                                <div class="tooltip tooltip-left" data-tip="Edit Pengajuan">
                                                    <a href="{{ route('pengajuan_publikasi.edit', $item->id) }}"
                                                       class="btn btn-ghost btn-xs btn-circle text-warning hover:bg-warning/10">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                                    </a>
                                                </div>
                                                @endrole

                                                {{-- Tombol Komentar --}}
                                                @hasanyrole('Pemeriksa|Penyusun|Admin') {{-- Admin juga bisa lihat komentar --}}
                                                <div class="tooltip tooltip-left" data-tip="Lihat/Tambah Komentar">
                                                    <a href="{{ route('pengajuan_publikasi.comment', $item->id) }}"
                                                       class="btn btn-ghost btn-xs btn-circle text-info hover:bg-info/10">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                                                    </a>
                                                </div>
                                                @endhasanyrole
                                                
                                                {{-- Tombol Hapus (Jika diperlukan, misal untuk Admin) --}}
                                                @role('Admin')
                                                <div class="tooltip tooltip-left" data-tip="Hapus Pengajuan">
                                                     <form action="{{ route('pengajuan_publikasi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus pengajuan ini? Tindakan ini tidak dapat dibatalkan.');">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-ghost btn-xs btn-circle text-error hover:bg-error/10">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                                @endrole
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-16 text-base-content/50">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0l2.25 1.75a3 3 0 013.5 0L17 7m-12 0" /></svg>
                                            Belum ada pengajuan publikasi ditemukan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginasi (jika ada) --}}
                     @if($submissions->hasPages())
                        <div class="mt-6">
                            {{ $submissions->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ⚙️ Script Update Status & SweetAlert --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.querySelectorAll('.status-select').forEach(select => {
                select.addEventListener('change', function() {
                    const id = this.dataset.id;
                    const value = this.value;
                    const badge = this.parentElement.querySelector('.badge'); // Target badge yang benar

                    fetch(`/pengajuan_publikasi/${id}/update-status`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json' // Tambahkan header Accept
                        },
                        body: JSON.stringify({ status: value })
                    })
                    // Tambahkan .then() untuk memeriksa status respons
                    .then(res => {
                        if (!res.ok) { // Jika status bukan 2xx (misal 404, 500)
                            throw new Error(`HTTP error! status: ${res.status}`);
                        }
                        return res.json(); 
                    })
                    .then(data => {
                        if (data.success) {
                            // Update teks badge
                            badge.textContent = value.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()); // Format teks badge
                            
                            // Update kelas warna badge
                            const colorClasses = {
                                'draft': 'bg-gray-400', 'sedang_diperiksa': 'bg-blue-500', 
                                'disetujui': 'bg-green-500', 'butuh_perbaikan': 'bg-yellow-500', 'ditolak': 'bg-red-500'
                            };
                            // Hapus semua kelas warna lama, tambahkan yang baru
                            badge.className = 'badge badge-sm mt-1 px-2 py-1 text-white font-medium ' + (colorClasses[value] || 'bg-gray-400');

                            Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Status diperbarui.', showConfirmButton: false, timer: 1500 });
                        } else {
                             // Kembalikan select ke nilai semula jika gagal
                            this.value = badge.textContent.toLowerCase().replace(/ /g, '_'); 
                            Swal.fire({ icon: 'error', title: 'Gagal!', text: data.message || 'Gagal memperbarui status.' });
                        }
                    })
                    .catch((error) => {
                        console.error('Fetch error:', error); // Log error ke console
                         // Kembalikan select ke nilai semula jika gagal
                        this.value = badge.textContent.toLowerCase().replace(/ /g, '_'); 
                        Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan koneksi atau server.' });
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>