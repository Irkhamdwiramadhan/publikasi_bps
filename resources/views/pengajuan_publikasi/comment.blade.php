{{-- 
    Halaman ini adalah untuk fitur Komentar (Review)
    Mengimplementasikan Halaman 9 & 11 dari PDF.
--}}

<x-app-layout>
    {{-- CSS Kustom untuk Tampilan Chat --}}
    <style>
        body { font-family: 'Inter', sans-serif; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .fade-in { animation: fadeIn 0.8s ease-out forwards; }

        /* Styling untuk chat bubble */
        .chat-bubble-pemeriksa {
            background-color: #E0E7FF; /* bg-indigo-100 */
            color: #3730A3; /* text-indigo-900 */
        }
        .chat-bubble-penyusun {
            background-color: #F3F4F6; /* bg-gray-100 */
            color: #1F2937; /* text-gray-800 */
        }
    </style>

    {{-- Header Halaman --}}
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-black leading-tight">
                Diskusi & Komentar Pengajuan
            </h2>
            <div class="text-sm breadcrumbs text-gray-500">
                <ul>
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li> 
                    <li><a href="{{ route('pengajuan_publikasi.index') }}">Pengajuan Publikasi</a></li> 
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
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
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

                    {{-- 1. Form Input Komentar (Sesuai Halaman 11 PDF) --}}
                    <div class="card bg-base-100 shadow-xl">
                        <div class="card-body p-6">
                            <h3 class="text-lg font-bold text-base-content/90 mb-4">Beri Komentar</h3>
                            <form action="{{ route('pengajuan_publikasi.storeComment', $submission->id) }}" method="POST">
                                @csrf
                                <div class="form-control">
                                    <textarea name="body" class="textarea textarea-bordered w-full h-32" 
                                              placeholder="Tulis komentar perbaikan atau balasan Anda di sini... (Sesuai Halaman 11 PDF)" 
                                              required></textarea>
                                </div>
                                <div class="card-actions justify-end mt-4">
                                    <button type="submit" class="btn btn-primary text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd" /></svg>
                                        Kirim Komentar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- 2. Daftar Komentar (Sesuai Halaman 9 PDF) --}}
                    <div class="card bg-base-100 shadow-xl">
                        <div class="card-body p-6">
                            <h3 class="text-lg font-bold text-base-content/90 mb-6">Riwayat Diskusi</h3>
                            <div class="space-y-6">
                                @forelse ($submission->comments as $comment)
                                    <div class="chat @if($comment->user_id == auth()->id()) chat-end @else chat-start @endif">
                                        <div class="chat-image avatar">
                                            <div class="w-10 rounded-full bg-base-300 flex items-center justify-center font-semibold text-base-content/70">
                                                <span>{{ substr($comment->user->name, 0, 1) }}</span>
                                            </div>
                                        </div>
                                        <div class="chat-header">
                                            {{ $comment->user->name }}
                                            {{-- Label Peran (Pemeriksa/Penyusun) --}}
                                            @if ($comment->user->hasRole('Pemeriksa'))
                                                <span class="badge badge-sm badge-info text-white ml-2">Pemeriksa</span>
                                            @else
                                                <span class="badge badge-sm badge-success text-white ml-2">Penyusun</span>
                                            @endif
                                            <time class="text-xs opacity-50 ml-2">{{ $comment->created_at->diffForHumans() }}</time>
                                        </div>
                                        <div class="chat-bubble @if($comment->user->hasRole('Pemeriksa')) chat-bubble-pemeriksa @else chat-bubble-penyusun @endif prose prose-sm max-w-none">
                                            {{-- Tampilkan isi komentar sebagai HTML agar formatting (enter) terbaca --}}
                                            {!! nl2br(e($comment->body)) !!}
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center text-gray-500 py-10">
                                        <p>Belum ada komentar.</p>
                                        <p class="text-sm">Jadilah yang pertama memberi masukan.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kolom Sidebar (Info Publikasi) --}}
                <div class="lg:col-span-1 space-y-6">
                    <div class="card bg-base-100 shadow-xl">
                        <div class="card-body p-6">
                            <h3 class="text-lg font-bold text-base-content/90 mb-4">Detail Publikasi</h3>
                            <div class="space-y-3 text-sm">
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase">Judul Publikasi</label>
                                    <p class="font-medium text-base-content">{{ $submission->publication->title_ind }}</p>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase">Jenis</label>
                                    <p class="font-medium text-base-content">{{ $submission->publication->publication_type }}</p>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase">Penyusun</label>
                                    <p class="font-medium text-base-content">{{ $submission->user->name }}</p>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase">Fungsi Pengusul</label>
                                    <p class="font-medium text-base-content">{{ $submission->fungsi_pengusul }}</p>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase">Status Saat Ini</label>
                                    <span class="badge mt-1 px-3 py-2 text-white font-medium 
                                        @if($submission->status == 'draft') bg-gray-400
                                        @elseif($submission->status == 'sedang_diperiksa') bg-blue-500
                                        @elseif($submission->status == 'disetujui') bg-green-500
                                        @elseif($submission->status == 'butuh_perbaikan') bg-yellow-500
                                        @elseif($submission->status == 'ditolak') bg-red-500
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $submission->status)) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>

