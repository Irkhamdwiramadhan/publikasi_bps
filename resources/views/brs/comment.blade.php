<x-app-layout>
    {{-- CSS Kustom --}}
    <style>
        body { font-family: 'Inter', sans-serif; }
        .fade-in { animation: fadeIn 0.6s ease-out forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
        
        .card-custom {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            border: 1px solid #f3f4f6;
        }
        
        .label-text-custom {
            font-size: 0.75rem;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.25rem;
            display: block;
        }
    </style>

    <x-slot name="header">
        <div class="flex items-center gap-3 fade-in">
            <div class="p-2 bg-white rounded-lg shadow-sm text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">
                    Diskusi & Komentar Pengajuan
                </h2>
                <div class="text-sm text-gray-500 breadcrumbs p-0 mt-0">
                    <ul>
                        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('brs.index') }}">Data BRS</a></li>
                        <li>Diskusi</li>
                    </ul>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 fade-in">
        <div class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
            
            {{-- LAYOUT GRID 2 KOLOM (Main Content & Sidebar) --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- KOLOM KIRI (2/3 Lebar): Input Komentar & Riwayat --}}
                <div class="lg:col-span-2 space-y-8">

                    {{-- 1. BAGIAN INPUT KOMENTAR (Di Atas) --}}
                    <div class="card-custom p-6">
                        <h3 class="font-bold text-lg text-gray-800 mb-6">Beri Komentar</h3>
                        
                        <form action="{{ route('brs.storeComment', $brs->id) }}" method="POST">
                            @csrf
                            <div class="form-control">
                                <label class="label pl-0">
                                    <span class="font-semibold text-sm text-gray-700">Tulis Komentar:</span>
                                </label>
                                <textarea name="body" 
                                          class="textarea textarea-bordered w-full h-32 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-base" 
                                          placeholder="Masukkan komentar perbaikan atau balasan Anda di sini..." required></textarea>
                            </div>
                            
                            <div class="flex justify-end mt-4">
                                <button type="submit" class="btn btn-neutral rounded-lg gap-2 px-6">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                    </svg>
                                    Kirim Komentar
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- 2. BAGIAN RIWAYAT DISKUSI (Di Bawah) --}}
                    <div class="card-custom p-6 min-h-[300px]">
                        <h3 class="font-bold text-lg text-gray-800 mb-6">Riwayat Diskusi</h3>

                        <div class="space-y-6">
                            @forelse($brs->comments as $comment)
                                <div class="flex gap-4 group">
                                    {{-- Avatar Inisial --}}
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm shadow-sm
                                            {{ $comment->role == 'Penyusun' ? 'bg-emerald-100 text-emerald-600' : '' }}
                                            {{ $comment->role == 'Pemeriksa' ? 'bg-blue-100 text-blue-600' : '' }}
                                            {{ $comment->role == 'Pimpinan' ? 'bg-purple-100 text-purple-600' : '' }}">
                                            {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                        </div>
                                    </div>

                                    <div class="flex-grow">
                                        {{-- Header Pesan --}}
                                        <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                                            <span class="font-semibold text-sm text-gray-800">{{ $comment->user->name }}</span>
                                            
                                            {{-- Badge Role --}}
                                            <span class="badge border-0 text-[10px] font-bold py-2
                                                {{ $comment->role == 'Penyusun' ? 'bg-emerald-500 text-white' : '' }}
                                                {{ $comment->role == 'Pemeriksa' ? 'bg-blue-500 text-white' : '' }}
                                                {{ $comment->role == 'Pimpinan' ? 'bg-purple-500 text-white' : '' }}">
                                                {{ $comment->role }}
                                            </span>

                                            <span class="text-xs text-gray-400 ml-auto">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>

                                        {{-- Isi Pesan (Bubble Abu-abu) --}}
                                        <div class="bg-gray-100 p-4 rounded-xl rounded-tl-none text-sm text-gray-700 leading-relaxed">
                                            {{ $comment->body }}
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-10">
                                    <div class="inline-flex p-4 bg-gray-50 rounded-full mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>
                                    </div>
                                    <p class="text-gray-400 text-sm">Belum ada riwayat diskusi pada BRS ini.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>

                {{-- KOLOM KANAN (1/3 Lebar): Detail Pengajuan --}}
                <div class="lg:col-span-1">
                    <div class="card-custom p-6 sticky top-6">
                        <h3 class="font-bold text-lg text-gray-800 mb-6 pb-4 border-b">Detail Pengajuan</h3>

                        <div class="space-y-5">
                            
                            {{-- Judul --}}
                            <div>
                                <span class="label-text-custom">JUDUL PUBLIKASI/BRS</span>
                                <p class="text-sm font-medium text-gray-900">{{ $brs->judul }}</p>
                            </div>

                            {{-- Nomor --}}
                            <div>
                                <span class="label-text-custom">NOMOR BRS</span>
                                <p class="text-sm font-mono bg-gray-100 px-2 py-1 rounded inline-block text-gray-700">
                                    {{ $brs->nomor_brs }}
                                </p>
                            </div>

                            {{-- Penyusun --}}
                            <div>
                                <span class="label-text-custom">PENYUSUN/PENGELOLA</span>
                                <div class="flex items-center gap-2">
                                    <div class="avatar placeholder">
                                        <div class="bg-gray-200 text-gray-600 rounded-full w-6 h-6 text-xs">
                                            <span>{{ substr($brs->user->name ?? 'A', 0, 1) }}</span>
                                        </div>
                                    </div>
                                    <p class="text-sm font-medium text-gray-700">{{ $brs->user->name ?? '-' }}</p>
                                </div>
                            </div>

                            {{-- Status Saat Ini --}}
                            <div>
                                <span class="label-text-custom">STATUS SAAT INI</span>
                                @php
                                    $colors = [
                                        'draft' => 'bg-gray-200 text-gray-700',
                                        'sedang_diperiksa' => 'bg-blue-100 text-blue-700',
                                        'disetujui' => 'bg-green-100 text-green-700',
                                        'butuh_perbaikan' => 'bg-yellow-100 text-yellow-700',
                                        'ditolak' => 'bg-red-100 text-red-700',
                                    ];
                                    $colorClass = $colors[$brs->status] ?? 'bg-gray-100 text-gray-600';
                                @endphp
                                <span class="badge border-0 font-bold py-3 px-4 rounded-lg {{ $colorClass }}">
                                    {{ strtoupper(str_replace('_', ' ', $brs->status)) }}
                                </span>
                            </div>

                            {{-- Tautan Pendukung (Link ke Drive/File) --}}
                            <div>
                                <span class="label-text-custom">TAUTAN PENDUKUNG</span>
                                @if($brs->pdf_path)
                                    @php 
                                        $link = filter_var($brs->pdf_path, FILTER_VALIDATE_URL) ? $brs->pdf_path : Storage::url($brs->pdf_path); 
                                    @endphp
                                    <a href="{{ $link }}" target="_blank" class="flex items-center gap-2 text-blue-600 hover:text-blue-800 hover:underline transition-colors text-sm font-medium">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                        File Publikasi/BRS
                                    </a>
                                @else
                                    <span class="text-sm text-gray-400 italic">Belum ada file diupload</span>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>