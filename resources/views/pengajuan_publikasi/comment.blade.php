{{--
    Halaman ini adalah untuk fitur Komentar (Review)
    Mengimplementasikan Halaman 9 & 11 dari PDF dengan desain profesional.
--}}

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

        /* Animasi untuk chat bubble */
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

        .chat-animated {
            opacity: 0;
            animation: slideUpIn 0.5s ease-out forwards;
            animation-delay: var(--delay);
        }

        .btn-premium {
            transition: all 0.2s ease-in-out;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        }

        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }

        /* Styling tambahan untuk membatasi lebar prose di chat bubble */
        .chat-bubble.prose-sm {
            max-width: 100%;
            /* Atau sesuaikan sesuai kebutuhan */
        }
    </style>

    {{-- Header Halaman (Konsisten) --}}
    <x-slot name="header">
        <div class="fade-in">
            <h2 class="font-semibold text-xl text-base-content leading-tight">
                💬 Diskusi & Komentar Pengajuan
            </h2>
            <div class="text-sm breadcrumbs text-base-content/70">
                <ul>
                    <li><a href="{{ route('dashboard') }}" class="hover:text-primary">Dashboard</a></li>
                    <li><a href="{{ route('pengajuan_publikasi.index') }}" class="hover:text-primary">Pengajuan Publikasi</a></li>
                    <li>Komentar</li>
                </ul>
            </div>
        </div>
    </x-slot>

    {{-- Konten Utama --}}
    <div class="py-12 fade-in">
        <div class="px-4 sm:px-6 lg:px-8">

            @if (session('success'))
            <div role="alert" class="alert alert-success mb-5 shadow-lg"><span>{{ session('success') }}</span></div>
            @endif
            @if ($errors->any())
            <div class="alert alert-error mb-6 shadow-lg">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h3 class="font-bold">Terjadi Kesalahan!</h3>
                        <ul class="list-disc pl-5 mt-1 text-xs">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            {{-- Layout Grid 2 Kolom --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Kolom Utama (Chat) --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- 1. Form Input Komentar (Revisi Desain) --}}
                    <div class="card bg-base-100 shadow-xl">
                        <div class="card-body p-6">
                            <h3 class="text-lg font-bold text-base-content/90 mb-4">Beri Komentar</h3>
                            {{-- TAMBAHKAN DEBUG INI --}}

                            {{-- Form action sudah benar --}}
                            <form method="POST" action="{{ route('pengajuan_publikasi.storeComment', $submission) }}">
                                @csrf
                                <div class="form-control mb-4">
                                    {{-- REVISI: Label di atas --}}
                                    <label for="body" class="block text-sm font-semibold text-base-content/80 mb-1">Tulis Komentar:</label>
                                    {{-- REVISI: Tambah rounded-[15px] dan error class --}}
                                    <textarea id="body" name="body" class="textarea textarea-bordered w-full h-32 rounded-[15px] {{ $errors->has('body') ? 'textarea-error' : '' }}"
                                        placeholder="Masukkan komentar perbaikan atau balasan Anda di sini..."
                                        required>{{ old('body') }}</textarea>
                                    {{-- REVISI: Error inline --}}
                                    @error('body')
                                    <p class="text-error text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="card-actions justify-end">
                                    {{-- REVISI: Tombol konsisten + ikon send --}}
                                    <button type="submit" class="btn btn-primary btn-premium rounded-[15px] text-black">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 -rotate-45" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                                        </svg>
                                        Kirim Komentar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- 2. Daftar Komentar (Revisi Desain) --}}
                    <div class="card bg-base-100 shadow-xl">
                        <div class="card-body p-6">
                            <h3 class="text-lg font-bold text-base-content/90 mb-6">Riwayat Diskusi</h3>
                            <div class="space-y-6">
                                @forelse ($submission->comments->sortBy('created_at') as $index => $comment) {{-- Urutkan berdasarkan waktu --}}
                                {{-- REVISI: Tambahkan class animasi --}}
                                <div class="chat @if($comment->user_id == auth()->id()) chat-end @else chat-start @endif chat-animated"
                                    style="--delay: {{ $index * 0.1 }}s;">
                                    <div class="chat-image avatar">
                                        <div class="w-10 rounded-full bg-base-300 flex items-center justify-center font-semibold text-base-content/70">
                                            <span>{{ strtoupper(substr($comment->user->name, 0, 1)) }}</span> {{-- Uppercase agar lebih jelas --}}
                                        </div>
                                    </div>
                                    <div class="chat-header text-xs text-base-content/60 mb-1">
                                        <span class="font-medium text-base-content/80">{{ $comment->user->name }}</span>
                                        {{-- Label Peran (Pemeriksa/Penyusun) --}}
                                        @if ($comment->user->hasRole('Pemeriksa'))
                                        <span class="badge badge-xs badge-info text-white ml-1 align-middle">Pemeriksa</span>
                                        @elseif ($comment->user->hasRole('Penyusun'))
                                        <span class="badge badge-xs badge-success text-white ml-1 align-middle">Penyusun</span>
                                        @else
                                        <span class="badge badge-xs badge-ghost ml-1 align-middle">{{ $comment->user->getRoleNames()->first() ?? 'User' }}</span>
                                        @endif
                                        <time class="ml-2">{{ $comment->created_at->diffForHumans() }}</time>
                                    </div>
                                    {{-- REVISI: Gunakan chat-bubble-primary untuk user saat ini, default untuk yang lain --}}
                                    <div class="chat-bubble @if($comment->user_id == auth()->id()) chat-bubble-primary text-white @else bg-base-200 @endif prose prose-sm max-w-none">
                                        {!! nl2br(e($comment->body)) !!}
                                    </div>
                                </div>
                                @empty
                                <div class="text-center text-base-content/50 py-10">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                    <p>Belum ada komentar.</p>
                                    <p class="text-sm">Jadilah yang pertama memberi masukan.</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kolom Sidebar (Info Publikasi - Diaktifkan & Revisi Desain) --}}
                <div class="lg:col-span-1 space-y-6">
                    <div class="card bg-base-100 shadow-xl">
                        <div class="card-body p-6">
                            <h3 class="text-lg font-bold text-base-content/90 mb-4 border-b border-base-200 pb-2">Detail Pengajuan</h3>
                            <div class="space-y-4 text-sm">
                                <div>
                                    {{-- REVISI: Label lebih kecil dan di atas --}}
                                    <label class="block text-xs font-semibold text-base-content/60 uppercase mb-1">Judul Publikasi</label>
                                    <p class="font-medium text-base-content">{{ $submission->publication?->title_ind ?? 'N/A' }}</p> {{-- Null safe operator --}}
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-base-content/60 uppercase mb-1">Jenis</label>
                                    <p class="font-medium text-base-content">{{ $submission->publication?->publication_type ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-base-content/60 uppercase mb-1">Penyusun</label>
                                    <p class="font-medium text-base-content">{{ $submission->user?->name ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-base-content/60 uppercase mb-1">Fungsi Pengusul</label>
                                    <p class="font-medium text-base-content">{{ $submission->fungsi_pengusul }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-base-content/60 uppercase mb-1">Status Saat Ini</label>
                                    {{-- REVISI: Badge dibuat lebih kecil (badge-sm) --}}
                                    <span class="badge badge-sm mt-1 px-2 py-1 text-white font-medium 
                                        @if($submission->status == 'draft') bg-gray-400
                                        @elseif($submission->status == 'sedang_diperiksa') bg-blue-500
                                        @elseif($submission->status == 'disetujui') bg-green-500
                                        @elseif($submission->status == 'butuh_perbaikan') bg-yellow-500
                                        @elseif($submission->status == 'ditolak') bg-red-500
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $submission->status)) }}
                                    </span>
                                </div>
                                {{-- REVISI: Menampilkan Tautan jika ada --}}
                                @if($submission->tautan_publikasi || $submission->spnrs_ketua_tim)
                                <div class="pt-2 border-t border-base-200">
                                    <label class="block text-xs font-semibold text-base-content/60 uppercase mb-2">Tautan Pendukung</label>
                                    <div class="flex flex-col space-y-2">
                                        @if ($submission->tautan_publikasi)
                                        <a href="{{ $submission->tautan_publikasi }}" target="_blank" class="flex items-center gap-2 text-primary hover:underline text-xs">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                            </svg>
                                            File Publikasi
                                        </a>
                                        @endif
                                        @if ($submission->spnrs_ketua_tim)
                                        <a href="{{ $submission->spnrs_ketua_tim }}" target="_blank" class="flex items-center gap-2 text-success hover:underline text-xs">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            SPNRS Ketua Tim
                                        </a>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>