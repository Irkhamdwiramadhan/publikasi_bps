{{-- 
    Halaman Index Surat Penyerahan Rancangan Publikasi (SPRP)
    Desain modern minimalis dengan TailwindCSS + DaisyUI + animasi AOS
--}}

<x-app-layout>
    {{-- ===== CSS Kustom Modern ===== --}}
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 100%);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in { animation: fadeIn 0.6s ease-out forwards; }

        .btn-premium {
            transition: all 0.25s ease-in-out;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .btn-premium:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        .card-modern {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(12px);
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        .table thead th {
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #475569;
            border-bottom: 2px solid #e2e8f0;
        }

        .table tbody tr:hover {
            background-color: #f8fafc;
            transition: background 0.3s ease;
        }

        .input-search {
            border-radius: 9999px;
            border: 1px solid #cbd5e1;
            transition: box-shadow 0.3s ease;
        }
        .input-search:focus {
            box-shadow: 0 0 0 3px rgba(59,130,246,0.2);
            border-color: #3b82f6;
        }
    </style>

    {{-- ===== Header Halaman ===== --}}
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center fade-in">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800">
                    📄 Surat Penyerahan Rancangan Publikasi
                </h2>
                <div class="text-sm breadcrumbs text-gray-500 mt-2">
                    <ul>
                        <li><a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
                        <li><a href="#">Pengajuan</a></li>
                        <li>Daftar SPRP</li>
                    </ul>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- ===== Konten Utama ===== --}}
    <div class="py-12 fade-in" data-aos="fade-up">
        <div class="px-4 sm:px-6 lg:px-8">

            {{-- Alert --}}
            @if (session('success'))
                <div class="alert alert-success mb-5 shadow-lg fade-in">
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-error mb-5 shadow-lg fade-in">
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- Card Utama --}}
            <div class="card-modern p-8 fade-in">
                {{-- Header Card --}}
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    <h3 class="text-2xl font-bold text-gray-800">Daftar Data SPRP</h3>
                    <a href="{{ route('sprp.create') }}"
                        class="btn btn-primary btn-premium text-white flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Buat Pengajuan
                    </a>
                </div>

                {{-- Pencarian --}}
                <div class="flex justify-end mb-4">
                    <form action="{{ route('sprp.index') }}" method="GET" class="w-full md:w-1/3 relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none">
                                <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z"
                                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        <input name="search" type="text" placeholder="Cari judul publikasi..."
                               class="input input-search w-full pl-10"
                               value="{{ request('search') }}" />
                    </form>
                </div>

                {{-- Tabel Data --}}
                <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white">
                    <table class="table w-full">
                        <thead>
                            <tr class="text-sm bg-gray-50">
                                <th>Jenis Publikasi</th>
                                <th>Kategori</th>
                                <th>Judul Publikasi</th>
                                <th>Katalog</th>
                                <th>No. Publikasi</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sprps as $sprp)
                                <tr class="hover transition-all duration-200">
                                    <td>
                                        @if ($sprp->publication)
                                            <span class="badge {{ $sprp->publication->publication_type == 'ARC' ? 'badge-success' : 'badge-info' }} text-white font-semibold">
                                                {{ $sprp->publication->publication_type }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ $sprp->kategori }}</td>
                                    <td class="font-semibold text-gray-800 whitespace-normal">
                                        {{ $sprp->publication->title_ind ?? 'N/A' }}
                                    </td>
                                    <td>{{ $sprp->publication->catalog_number ?? 'N/A' }}</td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <span class="font-mono">{{ $sprp->nomor_publikasi_final ?? '-' }}</span>
                                            @role('Pemeriksa')
                                                <button type="button"
                                                        class="btn btn-ghost btn-xs btn-circle"
                                                        data-id="{{ $sprp->id }}"
                                                        data-nomor="{{ $sprp->nomor_publikasi_final ?? '' }}"
                                                        onclick="openNomorModal(this)">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                        <path fill-rule="evenodd"
                                                              d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                                              clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            @endrole
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('sprp.show', $sprp->id) }}"
                                           class="btn btn-info btn-xs text-white btn-premium">
                                            Lihat Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-12">
                                        <div class="flex flex-col items-center opacity-70">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-3 text-gray-400"
                                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M10 21h7a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5-4.14-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v11m0 5l4.879-4.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242z" />
                                            </svg>
                                            <p class="text-lg font-semibold">Data Tidak Ditemukan</p>
                                            <p class="text-sm text-gray-500">Belum ada pengajuan yang dibuat.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginasi --}}
                @if($sprps->hasPages())
                    <div class="mt-6 flex justify-between items-center text-sm text-gray-600">
                        <p>
                            Menampilkan {{ $sprps->firstItem() }} - {{ $sprps->lastItem() }}
                            dari {{ $sprps->total() }} hasil
                        </p>
                        {{ $sprps->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ===== Modal Nomor Publikasi ===== --}}
   <dialog id="nomor_publikasi_modal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box bg-base-100 shadow-2xl rounded-2xl border border-base-200 p-6 transition-all duration-300 ease-out scale-95 hover:scale-100">
        <!-- Tombol Close -->
        <form method="dialog">
            <button 
                class="btn btn-sm btn-circle btn-ghost absolute right-3 top-3 hover:bg-base-200 transition">
                ✕
            </button>
        </form>

        <!-- Header -->
        <div class="text-center mb-6">
            <h3 class="font-extrabold text-xl text-primary mb-1">
                Tambah Nomor Publikasi
            </h3>
            <p class="text-sm text-base-content/70">
                Masukkan nomor publikasi dari portal publikasi resmi.
            </p>
        </div>

        <!-- Form -->
        <form id="nomor_form" method="POST" action="">
            @csrf
            @method('PATCH')

            <div class="form-control w-full">
                <label class="label">
                    <span class="label-text font-medium">Nomor Publikasi</span>
                </label>
                <input type="text"
                       id="nomor_input"
                       name="nomor_publikasi"
                       class="input input-bordered w-full rounded-xl focus:ring-2 focus:ring-primary focus:outline-none transition"
                       placeholder="Contoh: 12345/Publ/BPS/2025"
                       required />
                <p class="text-xs text-base-content/60 mt-1">
                    Pastikan nomor publikasi sesuai format resmi.
                </p>
            </div>
        </form>

        <!-- Tombol Aksi -->
        <div class="modal-action mt-8 flex justify-end gap-3">
            <form method="dialog">
                <button class="btn btn-ghost rounded-xl hover:bg-base-200">
                    Batal
                </button>
            </form>
            <button type="submit" form="nomor_form" class="btn btn-primary rounded-xl shadow-md hover:shadow-lg transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5 13l4 4L19 7" />
                </svg>
                Simpan
            </button>
        </div>
    </div>

    <!-- Backdrop -->
    <form method="dialog" class="modal-backdrop bg-black/40 backdrop-blur-sm">
        <button>close</button>
    </form>
</dialog>


    {{-- ===== Script Tambahan ===== --}}
    @push('scripts')
        <script>
            window.openNomorModal = function(button) {
                const id = button.getAttribute('data-id');
                const nomor = button.getAttribute('data-nomor');
                const modal = document.getElementById('nomor_publikasi_modal');
                const input = document.getElementById('nomor_input');
                const form = document.getElementById('nomor_form');

                input.value = nomor || '';
                form.action = `/sprp/${id}/update-nomor`;

                modal.showModal();
            };
        </script>
    @endpush

</x-app-layout>
