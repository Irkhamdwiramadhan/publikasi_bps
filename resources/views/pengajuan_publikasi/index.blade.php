<x-app-layout>
    {{-- 💎 CSS Kustom (Menambahkan animasi baris dan styling tooltip) --}}
    <style>
        body { font-family: 'Inter', sans-serif; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .fade-in { animation: fadeIn 0.8s ease-out forwards; }
        
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
        .tooltip:before {
            font-size: 0.75rem; /* Ukuran teks tooltip */
            padding: 0.3rem 0.6rem;
        }
         /* Pastikan border radius diterapkan */
        .rounded-\[15px\] { 
             border-radius: 15px;
        }
    </style>

    {{-- 🌟 Header (Dibuat konsisten) --}}
    <x-slot name="header">
        <div class="fade-in">
             {{-- REVISI: Konsistensi warna --}}
            <h2 class="font-semibold text-xl text-base-content leading-tight flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" /></svg>
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
        <div class="px-4 sm:px-6 lg:px-8">

            @if (session('success')) <div role="alert" class="alert alert-success mb-5 shadow-lg rounded-[15px]"><span>{{ session('success') }}</span></div> @endif
            @if (session('error')) <div role="alert" class="alert alert-error mb-5 shadow-lg rounded-[15px]"><span>{{ session('error') }}</span></div> @endif

            <div class="card bg-base-100 shadow-xl rounded-[15px]">
                <div class="card-body p-6 md:p-8">
            
                    {{-- Header Card --}}
                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                        <h3 class="text-xl font-bold text-base-content/90">Data Pengajuan Publikasi</h3>
                        <a href="{{ route('pengajuan_publikasi.create') }}"
                           class="btn btn-primary btn-premium w-full md:w-auto text-white flex items-center gap-2 rounded-[15px]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                            Tambah Pengajuan
                        </a>
                    </div>

                    {{-- 📋 Tabel Data --}}
                    <div class="overflow-x-auto">
                        <table class="table w-full text-sm">
                            <thead>
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
                                    <th>SPNSR TTD</th> {{-- 👈 KOLOM BARU --}}
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($submissions as $index => $item) {{-- $item adalah SubmissionPublication --}}
                                    <tr class="table-row-animated hover text-center border-b border-base-200" style="--delay: {{ $loop->iteration * 0.05 }}s;">
                                        
                                        <td class="py-3 font-medium text-gray-700">{{ $submissions->firstItem() + $index }}</td>
                                        
                                        {{-- Menggunakan relasi publication (master) --}}
                                        <td class="font-semibold text-base-content text-left px-3 py-3 whitespace-normal">
                                            {{ $item->publication?->title_ind ?? 'N/A' }} {{-- nullsafe --}}
                                        </td>
                                        <td>{{ $item->publication?->publication_type ?? 'N/A' }}</td> {{-- nullsafe --}}
                                        
                                        <td>{{ $item->user?->name ?? 'N/A' }}</td> {{-- nullsafe --}}
                                        <td>{{ $item->fungsi_pengusul }}</td>
                                        <td>{{ $item->created_at->format('d M Y') }}</td>
                                        <td>
                                            {{ $item->updated_at && $item->updated_at->ne($item->created_at) ? $item->updated_at->format('d M Y') : '-' }}
                                        </td>

                                        {{-- Kolom Tautan (dari $item) --}}
                                        <td class="text-center">
                                            <div class="flex justify-center gap-2">
                                                @if ($item->tautan_publikasi)
                                                    <div class="tooltip tooltip-bottom" data-tip="Lihat Publikasi">
                                                        <a href="{{ $item->tautan_publikasi }}" target="_blank" class="text-primary hover:text-primary-focus">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                                                        </a>
                                                    </div>
                                                @endif
                                                @if ($item->spnrs_ketua_tim)
                                                    <div class="tooltip tooltip-bottom" data-tip="Lihat SPNRS (Lama/Draft)">
                                                        <a href="{{ $item->spnrs_ketua_tim }}" target="_blank" class="text-gray-400 hover:text-gray-600">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                                        </a>
                                                    </div>
                                                @endif
                                                @if (!$item->tautan_publikasi && !$item->spnrs_ketua_tim)
                                                   <span class="text-xs opacity-50">-</span>
                                                @endif
                                            </div>
                                        </td>

                                        {{-- Kolom Status Pengajuan Publikasi (dari $item) --}}
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

                                        {{-- ================================================= --}}
                                        {{-- REVISI KRITIS: KOLOM BARU SPNSR TTD --}}
                                        {{-- ================================================= --}}
                                        <td class="py-2 text-center">
                                            {{-- Cek relasi LANGSUNG: $item->spnsrSubmission --}}
                                            @if ($item->spnsrSubmission)
                                                
                                                @php $spnsr = $item->spnsrSubmission; @endphp

                                                {{-- 1. SPNSR terhubung, Disetujui, DAN file ada --}}
                                                @if ($spnsr->status == 'Disetujui' && $spnsr->signed_spnsr_path)
                                                    <div class="tooltip tooltip-bottom" data-tip="Unduh SPNSR TTD ({{ $spnsr->nomor_surat }})">
                                                        <a href="{{ route('spnsr.download.signed', $spnsr->id) }}" 
                                                           class="btn btn-ghost btn-xs btn-circle text-success hover:bg-success/10">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"/>
                                                            </svg>
                                                        </a>
                                                    </div>
                                                {{-- 2. SPNSR terhubung TAPI masih 'Draft' atau 'Ditolak' --}}
                                                @else
                                                    <div class="tooltip tooltip-bottom" data-tip="SPNSR ({{ $spnsr->nomor_surat }}) masih '{{ $spnsr->status }}'">
                                                        <span class="badge badge-warning badge-sm">Belum TTD</span>
                                                    </div>
                                                @endif
                                            @else
                                                {{-- 3. Tidak ada SPNSR terhubung --}}
                                                <span class="badge badge-ghost badge-sm">Belum Diajukan</span>
                                            @endif
                                        </td>
                                        {{-- ================================================= --}}
                                        {{-- AKHIR KOLOM BARU SPNSR TTD --}}
                                        {{-- ================================================= --}}

                                        {{-- Kolom Aksi (dari $item) --}}
                                        <td class="py-2">
                                            <div class="flex justify-center gap-1">
                                                @role('Penyusun')
                                                <div class="tooltip tooltip-left" data-tip="Edit Pengajuan">
                                                    <a href="{{ route('pengajuan_publikasi.edit', $item->id) }}"
                                                       class="btn btn-ghost btn-xs btn-circle text-warning hover:bg-warning/10">
                                                       {{-- Ikon SVG Pensil --}}
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                                    </a>
                                                </div>
                                                @endrole

                                                @hasanyrole('Pemeriksa|Penyusun|Admin')
                                                <div class="tooltip tooltip-left" data-tip="Lihat/Tambah Komentar">
                                                    <a href="{{ route('pengajuan_publikasi.comment', $item->id) }}"
                                                       class="btn btn-ghost btn-xs btn-circle text-info hover:bg-info/10">
                                                       {{-- Ikon SVG Komentar --}}
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                                                    </a>
                                                </div>
                                                @endhasanyrole
                                                
                                                @role('Admin')
                                                <div class="tooltip tooltip-left" data-tip="Hapus Pengajuan">
                                                     <form action="{{ route('pengajuan_publikasi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus pengajuan ini?');">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-ghost btn-xs btn-circle text-error hover:bg-error/10">
                                                            {{-- Ikon SVG Hapus --}}
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
                                        {{-- REVISI: Colspan jadi 11 --}}
                                        <td colspan="11" class="text-center py-16 text-base-content/50">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0l2.25 1.75a3 3 0 013.5 0L17 7m-12 0" /></svg>
                                            Belum ada pengajuan publikasi ditemukan.
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

    {{-- ⚙️ Script Update Status --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            // Script untuk status-select (jika Anda masih menggunakannya)
            document.querySelectorAll('.status-select').forEach(select => {
                select.addEventListener('change', function() {
                    const id = this.dataset.id;
                    const value = this.value;
                    const badge = this.parentElement.querySelector('.badge'); 
                    const initialValue = badge.textContent.trim().toLowerCase().replace(/ /g, '_'); // Simpan nilai awal dari badge

                    // Tampilkan konfirmasi
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
                            // Lanjutkan fetch jika dikonfirmasi
                            fetch(`/pengajuan_publikasi/${id}/update-status`, {
                                method: 'PATCH', // Pastikan route Anda PATCH
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({ status: value })
                            })
                            .then(res => {
                                if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
                                return res.json(); 
                            })
                            .then(data => {
                                if (data.success) {
                                    // Update teks badge
                                    badge.textContent = value.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                                    
                                    // Update warna badge
                                    const colorClasses = {
                                        'draft': 'bg-gray-400', 'sedang_diperiksa': 'bg-blue-500', 
                                        'disetujui': 'bg-green-500', 'butuh_perbaikan': 'bg-yellow-500', 'ditolak': 'bg-red-500'
                                    };
                                    badge.className = 'badge badge-sm mt-1 px-2 py-1 text-white font-medium ' + (colorClasses[value] || 'bg-gray-400');

                                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Status diperbarui.', showConfirmButton: false, timer: 1500 });
                                } else {
                                    this.value = initialValue; // Kembalikan jika gagal
                                    Swal.fire({ icon: 'error', title: 'Gagal!', text: data.message || 'Gagal memperbarui status.' });
                                }
                            })
                            .catch((error) => {
                                this.value = initialValue; // Kembalikan jika gagal
                                console.error('Fetch error:', error);
                                Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan koneksi.' });
                            });
                        } else {
                            // Kembalikan select ke nilai awal jika dibatalkan
                            this.value = initialValue;
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>

