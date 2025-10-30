<x-app-layout>
    {{-- CSS Kustom (Konsisten) --}}
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

        /* Pastikan border radius diterapkan */
        .rounded-\[15px\] {
            border-radius: 15px;
        }
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

            @if (session('success')) <div role="alert" class="alert alert-success mb-5 shadow-lg rounded-[15px]"><span>{{ session('success') }}</span></div> @endif
            @if (session('error')) <div role="alert" class="alert alert-error mb-5 shadow-lg rounded-[15px]"><span>{{ session('error') }}</span></div> @endif

            <div class="card bg-base-100 shadow-xl rounded-[15px]"> {{-- Tambahkan radius --}}
                <div class="card-body p-6 md:p-8">
                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                        <h3 class="text-xl font-bold text-base-content/90">Riwayat Pengajuan</h3>
                        {{-- Tombol hanya untuk non-Pemimpin --}}
                        @unless(Auth::user()->hasRole('Pimpinan'))
                        <a href="{{ route('spnsr.create') }}" class="btn btn-primary btn-premium w-full md:w-auto text-white gap-2 rounded-[15px]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
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
                                    <td class="text-left font-semibold text-base-content whitespace-normal">
                                        {{--
                                                Alur: $submission (SPNSR) -> submissionPublication (Pengajuan) -> publication (Master) -> title_ind (Judul)
                                                Gunakan null-safe operator (?->) untuk mencegah error jika relasi putus
                                            --}}
                                        {{ $submission->submissionPublication?->publication?->title_ind ?? 'N/A (Relasi Publikasi Hilang)' }}
                                    </td>
                                    <td>{{ $submission->user?->name ?? 'N/A' }}</td> {{-- Null safe --}}
                                    <td>{{ $submission->created_at->format('d M Y') }}</td>

                                    {{-- ====================================== --}}
                                    {{-- REVISI LOGIKA ROLE PADA KOLOM STATUS --}}
                                    {{-- ====================================== --}}
                                    <td class="py-2">
                                        @role('Pemimpin')
                                        {{-- Tampilkan Select Dropdown HANYA untuk Pemimpin --}}
                                        <select class="select select-bordered select-xs w-full max-w-xs status-select-inline rounded-[15px]"
                                            data-id="{{ $submission->id }}">
                                            <option value="Draft" @selected($submission->status == 'Draft')>Draft</option>
                                            <option value="Disetujui" @selected($submission->status == 'Disetujui')>Disetujui</option>
                                            <option value="Ditolak" @selected($submission->status == 'Ditolak')>Ditolak</option>
                                        </select>
                                        {{-- Badge Tambahan (Opsional, bisa dihapus jika select sudah cukup) --}}
                                        {{-- <span class="badge badge-sm mt-1 ... status-badge">{{ $submission->status }}</span> --}}
                                        @else
                                        {{-- Tampilkan Badge biasa untuk SEMUA role SELAIN Pemimpin --}}
                                        <span class="badge badge-sm px-2 py-1 {{
                                                    $submission->status == 'Draft' ? 'badge-ghost' :
                                                    ($submission->status == 'Disetujui' ? 'badge-success text-success-content' :
                                                    ($submission->status == 'Ditolak' ? 'badge-error text-error-content' : 'badge-info'))
                                                }}">
                                            {{ $submission->status }}
                                        </span>
                                        @endrole
                                    </td>
                                    {{-- ====================================== --}}
                                    {{-- AKHIR REVISI KOLOM STATUS            --}}
                                    {{-- ====================================== --}}

                                    <td class="py-2">
                                        <div class="flex justify-center gap-1">
                                            {{-- Tombol Detail/Cetak PDF DRAFT --}}
                                            {{-- Tombol Aksi Khusus Role Pemimpin --}}
                                            @role('Pimpinan')
                                            {{-- Tombol Unduh Draft PDF --}}
                                            <div class="tooltip tooltip-left" data-tip="Unduh Draft SPNSR">
                                                <a href="{{ route('spnsr.pdf.draft', $submission->id) }}"
                                                    target="_blank"
                                                    class="btn btn-ghost btn-xs btn-circle text-blue-500 hover:bg-blue-500/10 transition-all duration-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-4 w-4"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                        stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                    </svg>
                                                </a>
                                            </div>

                                            {{-- Tombol Unggah SPNSR Bertanda Tangan --}}
                                            <div class="tooltip tooltip-left" data-tip="Unggah SPNSR Bertanda Tangan">
                                                <button
                                                    onclick="document.getElementById('upload_modal_{{ $submission->id }}').showModal()"
                                                    class="btn btn-ghost btn-xs btn-circle text-accent hover:bg-accent/10 transition-all duration-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-4 w-4"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                        stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                                    </svg>
                                                </button>
                                            </div>
                                            @endrole



                                            {{-- Tombol Unduh TTD (Jika sudah diunggah & disetujui) --}}
                                            @if($submission->signed_spnsr_path && $submission->status == 'Disetujui')
                                            <div class="tooltip tooltip-left" data-tip="Unduh SPNSR Bertanda Tangan">
                                                <a href="{{ route('spnsr.download.signed', $submission->id) }}"
                                                    class="btn btn-ghost btn-xs btn-circle text-success hover:bg-success/10">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd" />
                                                    </svg>
                                                </a>
                                            </div>
                                            @endif

                                            {{-- Tombol Hapus (Jika ada logicnya) --}}
                                            {{-- @can('delete', $submission) ... @endcan --}}
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-16 text-base-content/50">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0l2.25 1.75a3 3 0 013.5 0L17 7m-12 0" />
                                        </svg>
                                        Belum ada pengajuan SPNSR ditemukan.
                                    </td>
                                </tr>
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


    @foreach ($submissions as $submission)
    <dialog id="upload_modal_{{ $submission->id }}" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box rounded-[15px]">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="font-bold text-lg">Unggah SPNSR Bertanda Tangan</h3>
            <p class="py-2 text-sm">Untuk No. Surat: <span class="font-mono">{{ $submission->nomor_surat }}</span></p>
            <p class="py-1 text-sm">Judul: {{ $submission->submissionPublication?->publication?->title_ind ?? 'N/A (Relasi Publikasi Hilang)' }}</p>

            {{-- Form Unggah --}}
            <form action="{{ route('spnsr.upload.signed', $submission->id) }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label class="label"><span class="label-text font-semibold">Pilih File PDF Bertanda Tangan</span></label>
                    <input type="file" name="signed_spnsr_file" class="file-input file-input-bordered file-input-primary w-full rounded-[15px]" required accept=".pdf" />
                    {{-- Error handling spesifik untuk file upload --}}
                    @error('signed_spnsr_file', 'upload_' . $submission->id) {{-- Error bag spesifik --}}
                    <p class="text-error text-xs mt-2">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-base-content/60 mt-1">Hanya file PDF, maksimal 5MB.</p>
                </div>
                <div class="modal-action">
                    <button type="submit" class="btn btn-primary text-black rounded-[15px] btn-premium">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        Unggah & Setujui
                    </button>
                </div>
            </form>
        </div>
        {{-- Klik backdrop untuk menutup modal --}}
        <form method="dialog" class="modal-backdrop"><button>close</button></form>
    </dialog>
    @endforeach


    {{-- Script Update Status Inline (Hanya untuk Pemimpin) --}}

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const selects = document.querySelectorAll('.status-select-inline'); // Target select

            selects.forEach(select => {
                const initialValue = select.value; // Simpan nilai awal

                select.addEventListener('change', function() {
                    const submissionId = this.dataset.id;
                    const newStatus = this.value;
                    const selectElement = this; // Simpan referensi select

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
                                    body: JSON.stringify({
                                        status: newStatus
                                    })
                                })
                                .then(response => {
                                    if (!response.ok) {
                                        // Coba parse error JSON jika ada
                                        return response.json().then(errData => {
                                            throw new Error(errData.message || `HTTP error! status: ${response.status}`);
                                        }).catch(() => {
                                            throw new Error(`HTTP error! status: ${response.status}`);
                                        });
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Berhasil!',
                                            text: data.message || 'Status berhasil diperbarui.',
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                        // Update tampilan select (sudah otomatis)
                                    } else {
                                        selectElement.value = initialValue; // Kembalikan ke nilai awal
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Gagal!',
                                            text: data.message || 'Gagal memperbarui status.'
                                        });
                                    }
                                })
                                .catch(error => {
                                    console.error('Fetch error:', error);
                                    selectElement.value = initialValue; // Kembalikan ke nilai awal
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal!',
                                        text: error.message || 'Terjadi kesalahan koneksi atau server.'
                                    });
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