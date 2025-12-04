<x-app-layout>
    {{-- CSS Kustom --}}
    <style>
        body { font-family: 'Inter', sans-serif; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .fade-in { animation: fadeIn 0.8s ease-out forwards; }
        .rounded-\[15px\] { border-radius: 15px; }

        /* Mencegah icon di dalam dropdown berputar saat dropdown dibuka */
        .dropdown .btn-square svg { transition: none; }
    </style>

    {{-- Header Halaman --}}
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center fade-in">
            <div>
                <h2 class="font-semibold text-xl text-base-content leading-tight">
                    📊 {{ __('Berita Resmi Statistik (BRS)') }}
                </h2>
                <div class="text-sm breadcrumbs text-base-content/70">
                     <ul>
                        <li><a href="{{ route('dashboard') }}" class="hover:text-primary">Dashboard</a></li>
                        <li>Data BRS</li>
                    </ul>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- Konten Utama --}}
    <div class="py-12 fade-in">
        <div class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">

            {{-- Alert Notifikasi --}}
            @if (session('success'))
                <div role="alert" class="alert alert-success mb-5 shadow-lg rounded-[15px]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
             @if (session('error'))
                 <div role="alert" class="alert alert-error mb-5 shadow-lg rounded-[15px]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- Tombol Tambah Baru --}}
            <div class="flex justify-end mb-4">
                <a href="{{ route('brs.create') }}" class="btn btn-primary btn-sm rounded-[15px] text-white shadow-md hover:scale-105 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Buat BRS Baru
                </a>
            </div>

            {{-- Tabel Data --}}
            <div class="card bg-base-100 shadow-xl rounded-[15px]">
                <div class="card-body p-0">
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead class="bg-base-200/50 text-base-content">
                                <tr>
                                    <th class="w-10 text-center">No.</th>
                                    <th>Judul & Nomor</th>
                                    <th>Pengelola</th>
                                    <th>File Tersedia</th> {{-- REVISI: Nama Kolom --}}
                                    <th class="w-32 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($brs_list as $brs)
                                    <tr class="hover group transition-colors">
                                        <th class="text-center">{{ $loop->iteration + ($brs_list->currentPage() - 1) * $brs_list->perPage() }}</th>
                                        
                                        {{-- Judul & Nomor --}}
                                        <td class="whitespace-normal min-w-[300px]">
                                            <div class="font-bold text-base-content">{{ $brs->judul }}</div>
                                            <div class="text-xs text-base-content/60 font-mono mt-1 bg-base-200 inline-block px-2 py-0.5 rounded">{{ $brs->nomor_brs }}</div>
                                        </td>
                                        
                                        <td>
                                            <div class="flex items-center gap-2">
                                                <div class="avatar placeholder">
                                                    <div class="bg-neutral-focus text-neutral-content rounded-full w-8">
                                                        <span class="text-xs">{{ substr($brs->user->name ?? 'A', 0, 1) }}</span>
                                                    </div>
                                                </div>
                                                <span class="text-sm font-medium">{{ $brs->user->name ?? 'N/A' }}</span>
                                            </div>
                                        </td>

                                        {{-- REVISI UTAMA: Kolom Icon File Lengkap --}}
                                        <td>
                                            <div class="flex flex-wrap gap-2 items-center">
                                                
                                                {{-- 1. PDF (Merah) --}}
                                                @if ($brs->pdf_path)
                                                    <div class="tooltip" data-tip="Download PDF">
                                                        <a href="{{ Storage::url($brs->pdf_path) }}" target="_blank" class="btn btn-ghost btn-xs btn-square bg-red-50 hover:bg-red-100 text-red-600 border-red-200">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                            </svg>
                                                        </a>
                                                    </div>
                                                @endif

                                                {{-- 2. ZIP (Kuning/Orange) --}}
                                                @if ($brs->zip_path)
                                                    <div class="tooltip" data-tip="Download ZIP">
                                                        <a href="{{ Storage::url($brs->zip_path) }}" target="_blank" class="btn btn-ghost btn-xs btn-square bg-amber-50 hover:bg-amber-100 text-amber-600 border-amber-200">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 3v5a2 2 0 01-2 2H5a2 2 0 01-2-2V3a2 2 0 012-2h2a2 2 0 012 2zm10 0v5a2 2 0 01-2 2h-2a2 2 0 01-2-2V3a2 2 0 012-2h2a2 2 0 012 2z" />
                                                            </svg>
                                                        </a>
                                                    </div>
                                                @endif

                                                {{-- 3. Excel (Hijau) --}}
                                                @if ($brs->excel_path)
                                                    <div class="tooltip" data-tip="Download Excel">
                                                        <a href="{{ Storage::url($brs->excel_path) }}" target="_blank" class="btn btn-ghost btn-xs btn-square bg-green-50 hover:bg-green-100 text-green-600 border-green-200">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                            </svg>
                                                        </a>
                                                    </div>
                                                @endif

                                                {{-- 4. Infografis (Biru - Dropdown) --}}
                                                @if ($brs->infografis_paths && count($brs->infografis_paths) > 0)
                                                    <div class="dropdown dropdown-end">
                                                        <div class="tooltip" data-tip="Lihat {{ count($brs->infografis_paths) }} Gambar">
                                                            <label tabindex="0" class="btn btn-ghost btn-xs btn-square bg-blue-50 hover:bg-blue-100 text-blue-600 border-blue-200">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                </svg>
                                                            </label>
                                                        </div>
                                                        <ul tabindex="0" class="dropdown-content menu p-2 shadow-lg bg-base-100 rounded-box w-52 z-20 border border-base-200">
                                                            <li class="menu-title text-xs uppercase text-base-content/50 px-2 pb-1">Daftar Infografis</li>
                                                            @foreach ($brs->infografis_paths as $index => $path)
                                                                <li>
                                                                    <a href="{{ Storage::url($path) }}" target="_blank" class="text-xs">
                                                                        🖼️ Gambar #{{ $index + 1 }}
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif

                                                {{-- Jika KOSONG --}}
                                                @if(!$brs->pdf_path && !$brs->zip_path && !$brs->excel_path && empty($brs->infografis_paths))
                                                    <span class="text-xs text-base-content/40 italic">Belum ada file</span>
                                                @endif

                                            </div>
                                        </td>

                                        {{-- Kolom Aksi --}}
                                      <td class="text-center">
    <div class="flex justify-center items-center gap-2">
        
        {{-- 1. TOMBOL UPLOAD (Trigger Modal) --}}
        <div class="tooltip" data-tip="{{ $brs->pdf_path ? 'Update File' : 'Upload File Baru' }}">
            <button onclick="openUploadModal('{{ $brs->id }}', '{{ addslashes($brs->judul) }}')" 
                    class="btn btn-sm btn-square {{ $brs->pdf_path ? 'btn-ghost text-base-content/70' : 'btn-warning text-black animate-pulse' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
            </button>
        </div>

        {{-- 2. TOMBOL EDIT (BARU) --}}
        <div class="tooltip" data-tip="Edit Data (Judul/Tanggal)">
            <a href="{{ route('brs.edit', $brs->id) }}" class="btn btn-ghost btn-sm btn-square text-info">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </a>
        </div>

        {{-- 3. TOMBOL DETAIL --}}
        <div class="tooltip" data-tip="Lihat Detail Data">
            <a href="{{ route('brs.show', $brs->id) }}" class="btn btn-ghost btn-sm btn-square">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </a>
        </div>

    </div>
</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-10">
                                            <div class="flex flex-col items-center justify-center opacity-50">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                                <p>Belum ada data BRS.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Link Paginasi --}}
            <div class="mt-6">
                {{ $brs_list->links() }}
            </div>
            
        </div>
    </div>

    {{-- MODAL UPLOAD FILE (TETAP SAMA SEPERTI SEBELUMNYA) --}}
    <dialog id="upload_modal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box w-11/12 max-w-3xl rounded-[20px] p-0 overflow-hidden">
            
            {{-- Header Modal --}}
            <div class="bg-primary text-primary-content p-6 relative">
                <h3 class="font-bold text-lg">📤 Upload Dokumen BRS</h3>
                <p id="modal_brs_judul" class="text-sm opacity-90 mt-1 italic pr-8">...</p>
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4 text-white">✕</button>
                </form>
            </div>

            {{-- Body Modal --}}
            <div class="p-6 md:p-8 bg-base-100">
                <form id="form_upload" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    {{-- Alert Info --}}
                    <div role="alert" class="alert alert-info shadow-sm text-sm py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Pastikan file sesuai format. Upload ulang akan menimpa file lama.</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- PDF --}}
                        <div class="form-control">
                            <label class="label"><span class="label-text font-semibold">File PDF (Wajib)</span></label>
                            <input type="file" name="pdf" class="file-input file-input-bordered file-input-primary w-full rounded-[15px]" required />
                            <label class="label"><span class="label-text-alt">Max: 50MB</span></label>
                        </div>

                        {{-- ZIP --}}
                        <div class="form-control">
                            <label class="label"><span class="label-text font-semibold">File ZIP (Wajib)</span></label>
                            <input type="file" name="zip" class="file-input file-input-bordered file-input-secondary w-full rounded-[15px]" required />
                            <label class="label"><span class="label-text-alt">Max: 10MB</span></label>
                        </div>

                        
                        {{-- Infografis --}}
                        <div class="form-control md:col-span-2">
                            <label class="label"><span class="label-text font-semibold">Infografis (Gambar)</span></label>
                            <input type="file" name="infografis[]" class="file-input file-input-bordered file-input-info w-full rounded-[15px]" multiple required />
                            <label class="label"><span class="label-text-alt">Bisa pilih lebih dari satu file (JPG/PNG)</span></label>
                        </div>
                        {{-- Excel --}}
                        <div class="form-control md:col-span-2">
                            <label class="label"><span class="label-text font-semibold">File Excel (Opsional)</span></label>
                            <input type="file" name="excel" class="file-input file-input-bordered file-input-success w-full rounded-[15px]" />
                        </div>
                    </div>

                    <div class="modal-action border-t pt-4">
                        <form method="dialog">
                            <button class="btn btn-ghost rounded-[15px]">Batal</button>
                        </form>
                        <button type="submit" class="btn btn-primary text-black rounded-[15px] px-8">
                            💾 Simpan File
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    {{-- Script Logic Modal --}}
    @push('scripts')
        <script>
            function openUploadModal(id, judul) {
                const modal = document.getElementById('upload_modal');
                const form = document.getElementById('form_upload');
                const judulText = document.getElementById('modal_brs_judul');
                
                // Set URL Action Form
                const baseUrl = "{{ url('brs') }}"; 
                form.action = `${baseUrl}/${id}/upload`;

                // Set Judul
                judulText.textContent = "Mengupload untuk: " + judul;

                // Buka Modal
                modal.showModal();
            }
        </script>
    @endpush
</x-app-layout>