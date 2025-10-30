<x-app-layout>
    {{-- CSS Kustom (Konsisten dengan halaman lain) --}}
    <style>
        body { font-family: 'Inter', sans-serif; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .fade-in { animation: fadeIn 0.8s ease-out forwards; }
        .btn-premium {
            transition: all 0.2s ease-in-out;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        }
        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }
    </style>

    {{-- Header Halaman (Dibuat konsisten) --}}
    <x-slot name="header">
        <div class="fade-in">
            <h2 class="font-semibold text-xl text-base-content leading-tight">
                ➕ {{ __('Tambah Pengajuan Publikasi') }}
            </h2>
            <div class="text-sm breadcrumbs text-base-content/70">
                <ul>
                    <li><a href="{{ route('dashboard') }}" class="hover:text-primary">Dashboard</a></li> 
                    <li><a href="{{ route('pengajuan_publikasi.index') }}" class="hover:text-primary">Pengajuan Publikasi</a></li> 
                    <li>Tambah Baru</li>
                </ul>
            </div>
        </div>
    </x-slot>

    {{-- Konten Utama --}}
    <div class="py-12 fade-in">
        {{-- Menggunakan max-w-5xl untuk layout yang lebih fokus --}}
        <div class="px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto">

            <div class="card bg-base-100 shadow-xl">
                <div class="card-body p-6 md:p-8">

                    <h3 class="text-xl font-bold text-base-content/90 mb-6">Formulir Pengajuan Publikasi</h3>

                    {{-- Menampilkan Error Validasi Ringkasan --}}
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

                    <form action="{{ route('pengajuan_publikasi.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        {{-- Menggunakan space-y-6 untuk memisahkan grup --}}
                        <div class="space-y-6">

                            {{-- Grup 1: Informasi Dasar --}}
                            <section>
                                <h4 class="text-lg font-medium text-base-content/80 border-b border-base-200 pb-2 mb-4">Informasi Dasar</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                                    {{-- Pilih publikasi --}}
                                    <div class="form-control w-full md:col-span-2">
                                        <label for="publication_id" class="block text-sm font-semibold text-base-content/80 mb-1">Judul Publikasi</label>
                                        <select id="publication_id" name="publication_id" class="select select-bordered w-full rounded-[15px] {{ $errors->has('publication_id') ? 'select-error' : '' }}" required>
                                            <option value="" disabled selected>-- Pilih Publikasi --</option>
                                            @foreach ($publications as $pub)
                                                <option value="{{ $pub->id }}" {{ old('publication_id') == $pub->id ? 'selected' : '' }}>
                                                    [{{ $pub->publication_type }}] {{ $pub->title_ind }}
                                                </option>
                                            @endforeach
                                        </select>
                                         @error('publication_id')
                                            <p class="text-error text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Fungsi pengusul --}}
                                    <div class="form-control w-full">
                                        <label for="fungsi_pengusul" class="block text-sm font-semibold text-base-content/80 mb-1">Fungsi Pengusul</label>
                                        <input id="fungsi_pengusul" type="text" name="fungsi_pengusul" class="input input-bordered w-full rounded-[15px] {{ $errors->has('fungsi_pengusul') ? 'input-error' : '' }}"
                                               placeholder="Contoh: Fungsi Statistik Sosial" value="{{ old('fungsi_pengusul') }}" required>
                                        @error('fungsi_pengusul')
                                            <p class="text-error text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    {{-- Nama penyusun (otomatis user login) --}}
                                    <div class="form-control w-full">
                                        <label for="penyusun" class="block text-sm font-semibold text-base-content/80 mb-1">Nama Penyusun</label>
                                        <input id="penyusun" type="text" class="input input-bordered w-full rounded-[15px] bg-base-200/50" 
                                               value="{{ Auth::user()->name }}" readonly disabled>
                                         {{-- Tidak perlu error handling karena readonly --}}
                                    </div>
                                </div>
                            </section>

                            {{-- Grup 2: Tautan Pendukung --}}
                            <section>
                                <h4 class="text-lg font-medium text-base-content/80 border-b border-base-200 pb-2 mb-4">Tautan Pendukung <span class="text-gray-400 font-normal">(Opsional)</span></h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                                     {{-- Tautan publikasi --}}
                                    <div class="form-control w-full">
                                        <label for="tautan_publikasi" class="block text-sm font-semibold text-base-content/80 mb-1">Tautan File Publikasi</label>
                                        <input id="tautan_publikasi" type="url" name="tautan_publikasi" class="input input-bordered w-full rounded-[15px] {{ $errors->has('tautan_publikasi') ? 'input-error' : '' }}"
                                               placeholder="https://..." value="{{ old('tautan_publikasi') }}">
                                        @error('tautan_publikasi')
                                            <p class="text-error text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- SPNRS Ketua Tim --}}
                                    <!-- <div class="form-control w-full">
                                        <label for="spnrs_ketua_tim" class="block text-sm font-semibold text-base-content/80 mb-1">SPNRS dari Ketua Tim</label>
                                        <input id="spnrs_ketua_tim" type="url" name="spnrs_ketua_tim" class="input input-bordered w-full rounded-[15px] {{ $errors->has('spnrs_ketua_tim') ? 'input-error' : '' }}"
                                               placeholder="https://..." value="{{ old('spnrs_ketua_tim') }}">
                                         @error('spnrs_ketua_tim')
                                            <p class="text-error text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div> -->
                                </div>
                            </section>
                        </div>

                        {{-- Tombol aksi --}}
                        <div class="flex justify-end items-center mt-8 pt-6 border-t border-base-200 gap-3">
                            <a href="{{ route('pengajuan_publikasi.index') }}" class="btn btn-ghost">Batal</a>
                            <button type="submit" class="btn btn-primary btn-premium rounded-[15px] text-black">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Simpan Pengajuan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>