<x-app-layout>
    {{-- 🌟 Header --}}
    <x-slot name="header">
        <div class="flex flex-col gap-1 fade-in">
            <h2 class="text-2xl font-semibold text-gray-800 leading-tight flex items-center gap-2">
                🗂️ Daftar Pengajuan Publikasi
            </h2>
            <p class="text-sm text-gray-500">Kelola semua pengajuan publikasi dengan tampilan modern & elegan.</p>
        </div>
    </x-slot>

    {{-- 💎 Konten Utama --}}
    <div class="p-8 space-y-6 fade-in">

        {{-- Header Card --}}
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h3 class="text-lg font-bold text-gray-700">📘 Data Pengajuan Publikasi</h3>
            <a href="{{ route('pengajuan_publikasi.create') }}"
               class="btn btn-primary btn-premium text-white flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor"
                    viewBox="0 0 20 20"><path fill-rule="evenodd"
                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                    clip-rule="evenodd" /></svg>
                Tambah Publikasi
            </a>
        </div>

        {{-- 📋 Tabel Data --}}
        <div class="overflow-x-auto bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300">
            <table class="table w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase tracking-wide text-xs font-semibold border-b">
                    <tr class="text-center">
                        <th>No</th>
                        <th>Judul Publikasi</th>
                        <th>Jenis</th>
                        <th>Penyusun</th>
                        <th>Fungsi Pengusul</th>
                        <th>Tgl Pengajuan</th>
                        <th>Tgl Perbaikan</th>
                        <th>Tautan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($submissions as $index => $item)
                        <tr class="hover:bg-gray-50 transition-all duration-200 ease-in-out text-center border-b">
                            <td class="py-3 font-medium text-gray-700">{{ $index + 1 }}</td>
                            <td class="font-semibold text-gray-900 text-left px-3">{{ $item->publication->title_ind }}</td>
                            <td class="text-gray-600">{{ $item->publication->publication_type }}</td>
                            <td class="text-gray-700">{{ $item->user->name }}</td>
                            <td class="text-gray-600">{{ $item->fungsi_pengusul }}</td>
                            <td class="text-gray-600">{{ $item->created_at->format('d-m-Y') }}</td>
                            <td class="text-gray-600">
                                {{ $item->updated_at && $item->updated_at != $item->created_at ? $item->updated_at->format('d-m-Y') : '-' }}
                            </td>

                            {{-- Kolom Tautan --}}
                            <td class="text-center">
                                @if ($item->tautan_publikasi)
                                    <a href="{{ $item->tautan_publikasi }}" target="_blank" class="text-blue-500 hover:text-blue-700 mx-1" title="Lihat Publikasi">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.8" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M13.828 10.172a4 4 0 010 5.656l-1.415 1.414a4 4 0 01-5.656-5.656l1.414-1.414m7.071-2.829a4 4 0 010 5.657l-1.414 1.414a4 4 0 11-5.657-5.657l1.414-1.414" />
                                        </svg>
                                    </a>
                                @endif

                                @if ($item->tautan_spnrs)
                                    <a href="{{ $item->tautan_spnrs }}" target="_blank" class="text-green-500 hover:text-green-700 mx-1" title="Lihat SPNRS Ketua Tim">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.8" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.02.166m-1.02-.165L18.16 20.79A2 2 0 0116.164 23H7.836a2 2 0 01-1.997-2.21L6.772 5.79m12.456 0a48.108 48.108 0 00-3.478-.397m-8 .562c.338-.06.678-.115 1.02-.166m0 0A48.11 48.11 0 0112 5c1.318 0 2.626.054 3.919.156" />
                                        </svg>
                                    </a>
                                @endif
                            </td>

                            {{-- Kolom Status --}}
                            <td class="text-center space-y-1">
                                @hasanyrole('Pemeriksa|Admin')
                                    <select class="select select-bordered select-sm text-sm border-gray-300 rounded-md status-select"
                                            data-id="{{ $item->id }}">
                                        <option value="draft" {{ $item->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="sedang_diperiksa" {{ $item->status == 'sedang_diperiksa' ? 'selected' : '' }}>Sedang Diperiksa</option>
                                        <option value="disetujui" {{ $item->status == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                        <option value="butuh_perbaikan" {{ $item->status == 'butuh_perbaikan' ? 'selected' : '' }}>Butuh Perbaikan</option>
                                        <option value="ditolak" {{ $item->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    </select>
                                @endhasanyrole

                                <span class="badge mt-1 px-3 py-2 text-white font-medium 
                                    @if($item->status == 'draft') bg-gray-400
                                    @elseif($item->status == 'sedang_diperiksa') bg-blue-500
                                    @elseif($item->status == 'disetujui') bg-green-500
                                    @elseif($item->status == 'butuh_perbaikan') bg-yellow-500
                                    @elseif($item->status == 'ditolak') bg-red-500
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                </span>
                            </td>

                            {{-- Kolom Aksi --}}
                            <td>
                                <div class="flex justify-center gap-2">
                                    {{-- Tombol Edit hanya untuk Penyusun --}}
                                    @role('Penyusun')
                                        <a href="{{ route('pengajuan_publikasi.edit', $item->id) }}"
                                           class="btn btn-outline btn-warning btn-sm hover:scale-105 transition"
                                           title="Edit Pengajuan">
                                           ✏️
                                        </a>
                                    @endrole

                                    {{-- Tombol Komentar untuk Penyusun dan Pemeriksa --}}
                                    @hasanyrole('Pemeriksa|Penyusun')
                                        <a href="{{ route('pengajuan_publikasi.comment', $item->id) }}"
                                           class="btn btn-outline btn-info btn-sm hover:scale-105 transition"
                                           title="Komentar">
                                           💬
                                        </a>
                                    @endhasanyrole
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-10 text-gray-400 font-medium">
                                Belum ada pengajuan publikasi
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ⚙️ Script Update Status --}}
    <script>
        document.querySelectorAll('.status-select').forEach(select => {
            select.addEventListener('change', function() {
                const id = this.dataset.id;
                const value = this.value;

                fetch(`/pengajuan_publikasi/${id}/update-status`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ status: value })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const badge = select.parentElement.querySelector('.badge');
                        badge.textContent = value.replace('_', ' ').charAt(0).toUpperCase() + value.slice(1);
                        badge.className = 'badge mt-1 px-3 py-2 text-white font-medium ' +
                            (value === 'draft' ? 'bg-gray-400' :
                             value === 'sedang_diperiksa' ? 'bg-blue-500' :
                             value === 'disetujui' ? 'bg-green-500' :
                             value === 'butuh_perbaikan' ? 'bg-yellow-500' :
                             value === 'ditolak' ? 'bg-red-500' : '');

                        Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Status diperbarui.', showConfirmButton: false, timer: 1500 });
                    }
                })
                .catch(() => {
                    Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan saat memperbarui status.' });
                });
            });
        });
    </script>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .fade-in { animation: fadeIn 0.7s ease-out forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .btn-premium:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); transition: all 0.2s; }
    </style>
</x-app-layout>
