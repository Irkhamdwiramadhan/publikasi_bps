<x-app-layout>
    {{-- CSS Kustom (Konsisten) --}}
    <style>
        body { font-family: 'Inter', sans-serif; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .fade-in { animation: fadeIn 0.8s ease-out forwards; }
        .rounded-\[15px\] { border-radius: 15px; }
        .btn-premium {
            transition: all 0.2s ease-in-out;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        }
        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }
    </style>

    {{-- Header Halaman (Dibuat lebih baik) --}}
    <x-slot name="header">
        <div class="fade-in">
            <h2 class="font-semibold text-xl text-base-content leading-tight">
                📝 {{ __('Tambah Data BRS Baru') }}
            </h2>
            <div class="text-sm breadcrumbs text-base-content/70">
                 <ul>
                    <li><a href="{{ route('dashboard') }}" class="hover:text-primary">Dashboard</a></li>
                    <li><a href="{{ route('brs.index') }}" class="hover:text-primary">Data BRS</a></li>
                    <li>Tambah Baru</li>
                </ul>
            </div>
        </div>
    </x-slot>

    {{-- Konten Utama --}}
    <div class="py-12 fade-in">
        <div class="px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto"> {{-- Dibuat max-w-5xl agar form lebih fokus --}}
            
            <div class="card bg-base-100 shadow-xl rounded-[15px]">
                <div class="card-body p-6 md:p-8">

                    {{-- Menampilkan Error Validasi (Style Ditingkatkan) --}}
                    @if ($errors->any())
                        <div role="alert" class="alert alert-error mb-6 shadow-lg rounded-[15px]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <div>
                                <h3 class="font-bold">Terjadi Kesalahan!</h3>
                                <ul class="list-disc pl-5 mt-1 text-xs">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('brs.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        {{-- Menggunakan space-y-10 untuk jarak antar grup --}}
                        <div class="space-y-10">

                            {{-- Grup 1: Informasi BRS --}}
                            <section>
                                <h3 class="text-lg font-semibold text-base-content mb-4">📘 Informasi BRS</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    
                                    {{-- Judul (Dibuat 2 kolom penuh) --}}
                                    <div class="form-control w-full md:col-span-2">
                                        <label for="judul" class="label"><span class="label-text">Judul BRS</span></label>
                                        <input type="text" name="judul" id="judul" class="input input-bordered w-full rounded-[15px] {{ $errors->has('judul') ? 'input-error' : '' }}" value="{{ old('judul') }}" required>
                                    </div>

                                    {{-- Bulan --}}
                                    <div class="form-control w-full">
                                        <label for="bulan" class="label"><span class="label-text">Bulan Rilis</span></label>
                                        <input type="month" name="bulan" id="bulan" class="input input-bordered w-full rounded-[15px] {{ $errors->has('bulan') ? 'input-error' : '' }}" value="{{ old('bulan') }}" required>
                                    </div>

                                    {{-- Pengelola --}}
                                    <div class="form-control w-full">
                                        <label for="user_id" class="label"><span class="label-text">Pengelola</span></label>
                                        <select name="user_id" id="user_id" class="select select-bordered w-full rounded-[15px] {{ $errors->has('user_id') ? 'select-error' : '' }}" required>
                                            <option value="" disabled selected>-- Pilih Pengelola --</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </section>

                            {{-- Grup 2: Upload File --}}
                            <section>
                                <h3 class="text-lg font-semibold text-base-content mb-4">📤 Upload File</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                    {{-- Upload PDF --}}
                                    <div class="form-control w-full">
                                        <label for="pdf" class="label"><span class="label-text">File PDF</span></label>
                                        <input type="file" name="pdf" id="pdf" class="file-input file-input-bordered w-full rounded-[15px] {{ $errors->has('pdf') ? 'file-input-error' : '' }}" required>
                                    </div>
                                    
                                    {{-- Upload ZIP --}}
                                    <div class="form-control w-full">
                                        <label for="zip" class="label"><span class="label-text">File ZIP</span></label>
                                        <input type="file" name="zip" id="zip" class="file-input file-input-bordered w-full rounded-[15px] {{ $errors->has('zip') ? 'file-input-error' : '' }}" required>
                                    </div>

                                    {{-- Upload Infografis (Multiple) (Dibuat 2 kolom penuh) --}}
                                    <div class="form-control w-full md:col-span-2">
                                        <label for="infografis" class="label">
                                            <span class="label-text">Infografis</span>
                                            <span class="label-text-alt text-primary">(Bisa pilih lebih dari 1 file)</span>
                                        </label>
                                        <input type="file" name="infografis[]" id="infografis" class="file-input file-input-bordered w-full rounded-[15px] {{ $errors->has('infografis') ? 'file-input-error' : '' }}" required multiple>
                                    </div>

                                    {{-- Upload Excel (Opsional) (Dibuat 2 kolom penuh) --}}
                                    <div class="form-control w-full md:col-span-2">
                                        <label for="excel" class="label">
                                            <span class="label-text">File Excel</span>
                                            <span class="label-text-alt">(Opsional)</span>
                                        </label>
                                        <input type="file" name="excel" id="excel" class="file-input file-input-bordered w-full rounded-[15px] {{ $errors->has('excel') ? 'file-input-error' : '' }}">
                                    </div>

                                </div>
                            </section>

                        </div>

                        {{-- Tombol Aksi (Dibuat lebih baik) --}}
                        <div class="flex justify-end items-center mt-10 pt-6 border-t border-base-200 gap-3">
                            <a href="{{ route('brs.index') }}" class="btn btn-ghost rounded-[15px]">Batal</a>
                            <button type="submit" class="btn btn-primary btn-premium rounded-[15px] text-black">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
Input
                                Simpan Data
                            </button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>