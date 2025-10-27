<x-app-layout>
    {{-- CSS Kustom (Konsisten) --}}
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
            font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;
            color: hsl(var(--bc) / 0.7); border-bottom-width: 2px;
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
        .tooltip:before { font-size: 0.75rem; padding: 0.3rem 0.6rem; }
    </style>

    {{-- Header Halaman (Konsisten) --}}
    <x-slot name="header">
        <div class="fade-in">
            <h2 class="font-semibold text-xl text-base-content leading-tight">
                📁 {{ __('Daftar Pengajuan SPNSR') }}
            </h2>
            <div class="text-sm breadcrumbs text-base-content/70">
                 <ul>
                    <li><a href="{{ route('dashboard') }}" class="hover:text-primary">Dashboard</a></li>
                    <li>Pengajuan SPNSR</li>
                </ul>
            </div>
        </div>
    </x-slot>

    {{-- Konten Utama --}}
    <div class="py-12 fade-in">
        <div class="px-4 sm:px-6 lg:px-8">

            @if (session('success')) <div role="alert" class="alert alert-success mb-5 shadow-lg"><span>{{ session('success') }}</span></div> @endif
            @if (session('error')) <div role="alert" class="alert alert-error mb-5 shadow-lg"><span>{{ session('error') }}</span></div> @endif

            <div class="card bg-base-100 shadow-xl">
                <div class="card-body p-6 md:p-8">
                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                        <h3 class="text-xl font-bold text-base-content/90">Riwayat Pengajuan</h3>
                        {{-- Tombol hanya untuk non-Pemimpin --}}
                        @unless(Auth::user()->hasRole('Pemimpin'))
                        <a href="{{ route('spnsr.create') }}" class="btn btn-primary btn-premium w-full md:w-auto text-white gap-2 rounded-[15px]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                            Buat Pengajuan Baru
                        </a>
                        @endunless
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table w-full text-sm">
                            <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th class="text-left">No. Surat</th>
                                    <th class="text-left">Judul Publikasi</th>
                                    <th>Penyusun</th>
                                    <th>Tgl Pengajuan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($submissions as $index => $submission)
                                    <tr class="table-row-animated hover text-center border-b border-base-200" style="--delay: {{ $loop->iteration * 0.05 }}s;">
                                        <th>{{ $submissions->firstItem() + $index }}</th>
                                        <td class="text-left font-mono">{{ $submission->nomor_surat }}</td>
                                        <td class="text-left font-semibold text-base-content whitespace-normal">{{ $submission->judul_publikasi }}</td>
                                        <td>{{ $submission->user?->name ?? 'N/A' }}</td> {{-- Null safe --}}
                                        <td>{{ $submission->created_at->format('d M Y') }}</td>

                                        {{-- REVISI KOLOM STATUS dengan Select Inline --}}
                                        <td class="py-2">
                                           
                                                {{-- Tampilkan Select Dropdown untuk Pemimpin --}}
                                                <select class="select select-bordered select-xs w-full max-w-xs status-select-inline rounded-[15px]"
                                                        data-id="{{ $submission->id }}">
                                                    <option value="Draft" @selected($submission->status == 'Draft')>Draft</option>
                                                    <option value="Disetujui" @selected($submission->status == 'Disetujui')>Disetujui</option>
                                                    <option value="Ditolak" @selected($submission->status == 'Ditolak')>Ditolak</option>
                                                </select>
                                        
                                                {{-- Tampilkan Badge biasa untuk non-Pemimpin --}}
                                                 <span class="badge badge-sm px-2 py-1 {{
                                                    $submission->status == 'Draft' ? 'badge-ghost' :
                                                    ($submission->status == 'Disetujui' ? 'badge-success text-success-content' :
                                                    ($submission->status == 'Ditolak' ? 'badge-error text-error-content' : 'badge-info'))
                                                }}">
                                                    {{ $submission->status }}
                                                </span>
                                     
                                        </td>
                                        {{-- AKHIR REVISI KOLOM STATUS --}}

                                        <td class="py-2">
                                            <div class="flex justify-center gap-1">
                                                {{-- Tombol Detail/Cetak PDF --}}
                                                <div class="tooltip tooltip-left" data-tip="Lihat/Cetak PDF">
                                                    <a href="{{ route('spnsr.pdf', $submission->id) }}" target="_blank" class="btn btn-ghost btn-xs btn-circle text-info hover:bg-info/10">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                                                    </a>
                                                </div>

                                                {{-- Tombol Edit Halaman (Opsional, bisa dihapus jika Pemimpin hanya update via select) --}}
                                                <!-- {{-- @role('Pemimpin')
                                                <div class="tooltip tooltip-left" data-tip="Edit Detail (Halaman)">
                                                    <a href="{{ route('spnsr.edit', $submission->id) }}" class="btn btn-ghost btn-xs btn-circle text-warning hover:bg-warning/10">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                                    </a>
                                                </div>
                                                @endrole --}} -->

                                                {{-- Tombol Hapus (Jika ada logicnya) --}}
                                                {{-- @can('delete', $submission) ... @endcan --}}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center py-16 text-base-content/50">
                                         <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0l2.25 1.75a3 3 0 013.5 0L17 7m-12 0" /></svg>
                                        Belum ada pengajuan SPNSR ditemukan.
                                    </td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($submissions->hasPages())
                        <div class="mt-6">{{ $submissions->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- REVISI: Tambahkan Script Update Status Inline --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const selects = document.querySelectorAll('.status-select-inline'); // Target select

                selects.forEach(select => {
                    const initialValue = select.value; // Simpan nilai awal

                    select.addEventListener('change', function () {
                        const submissionId = this.dataset.id;
                        const newStatus = this.value;
                        const selectElement = this; // Simpan referensi select
                        
                        // REVISI: Cari elemen badge di dalam parent TD yang sama
                        const badgeElement = selectElement.parentElement.querySelector('.badge'); 

                        // Konfirmasi SweetAlert sebelum update
                        Swal.fire({
                            title: 'Ubah Status?',
                            text: `Anda yakin ingin mengubah status pengajuan ini menjadi "${newStatus}"?`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ya, Ubah!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Lakukan fetch jika dikonfirmasi
                                fetch(`/spnsr/${submissionId}/update-status`, { // URL route PATCH
                                    method: 'PATCH',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': csrfToken,
                                        'Accept': 'application/json',
                                    },
                                    body: JSON.stringify({ status: newStatus })
                                })
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error(`HTTP error! status: ${response.status}`);
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire({ icon: 'success', title: 'Berhasil!', text: data.message || 'Status berhasil diperbarui.', showConfirmButton: false, timer: 1500 });
                                        
                                        // === REVISI UTAMA: UPDATE TAMPILAN BADGE ===
                                        if (badgeElement) {
                                            // 1. Update Teks Badge
                                            badgeElement.textContent = newStatus.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()); // Format teks (e.g., Disetujui)

                                            // 2. Update Warna Badge (sesuaikan class dengan Blade Anda)
                                            const colorClasses = {
                                                'Draft': 'badge-ghost', 
                                                'Disetujui': 'badge-success text-success-content', 
                                                'Ditolak': 'badge-error text-error-content'
                                                // Tambahkan class lain jika ada status lain
                                            };
                                            // Hapus class warna lama & tambahkan yang baru
                                            badgeElement.className = 'badge badge-sm px-2 py-1 ' + (colorClasses[newStatus] || 'badge-info'); // Default ke info jika status tak dikenal
                                        }
                                        // === AKHIR REVISI BADGE ===

                                    } else {
                                        selectElement.value = initialValue; // Kembalikan select ke nilai awal
                                        Swal.fire({ icon: 'error', title: 'Gagal!', text: data.message || 'Gagal memperbarui status.' });
                                    }
                                })
                                .catch(error => {
                                    console.error('Fetch error:', error);
                                    selectElement.value = initialValue; // Kembalikan select ke nilai awal
                                    Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan koneksi atau server.' });
                                });
                            } else {
                                // Jika dibatalkan, kembalikan select ke nilai awal
                                selectElement.value = initialValue;
                            }
                        });
                    });
                });
            });
        </script>
    @endpush

</x-app-layout>