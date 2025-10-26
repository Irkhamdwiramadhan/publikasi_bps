<x-app-layout>
    {{-- CSS Kustom --}}
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

    {{-- Header Halaman --}}
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-base-content leading-tight">
                {{ __('Tambah Publikasi Baru') }}
            </h2>
            <div class="text-sm breadcrumbs text-base-content/70">
                <ul>
                    <li><a href="{{ route('dashboard') }}" class="hover:text-primary">Dashboard</a></li> 
                    <li><a href="{{ route('publications.index') }}" class="hover:text-primary">Master Publikasi</a></li> 
                    <li>Tambah Baru</li>
                </ul>
            </div>
        </div>
    </x-slot>

    {{-- Konten Utama --}}
    <div class="py-12 fade-in">
        <div class="px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto"> 

            <div class="card bg-base-100 shadow-xl">
                <div class="card-body p-6 md:p-8">

                    <h3 class="text-xl font-bold text-base-content/90 mb-6">Formulir Publikasi Baru</h3>

                    {{-- Menampilkan Error Validasi Ringkasan --}}
                    @if ($errors->any())
                        <div class="alert alert-error mb-6 shadow-lg">
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span><b>Terjadi Kesalahan:</b> Periksa kembali data yang Anda masukkan di bawah.</span>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('publications.store') }}" method="POST">
                        @csrf
                        
                        <div class="space-y-6">

                            {{-- Grup 1: Informasi Judul --}}
                            <section>
                                <h4 class="text-lg font-medium text-base-content/80 border-b border-base-200 pb-2 mb-4">Informasi Judul</h4>
                                <div class="space-y-4">
                                    {{-- Judul Publikasi (IND) --}}
                                    <div class="form-control w-full">
                                        <label for="title_ind" class="block text-sm font-semibold text-base-content/80 mb-1">Judul Publikasi (IND)</label>
                                        {{-- REVISI: Tambah rounded-[15px] --}}
                                        <input id="title_ind" name="title_ind" type="text" value="{{ old('title_ind') }}" placeholder="Contoh: Statistik Indonesia 2025" class="input input-bordered w-full rounded-[15px] {{ $errors->has('title_ind') ? 'input-error' : '' }}" required />
                                        @error('title_ind')
                                            <p class="text-error text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    
                                </div>
                            </section>

                            {{-- Grup 2: Detail & Identitas --}}
                            <section>
                                <h4 class="text-lg font-medium text-base-content/80 border-b border-base-200 pb-2 mb-4">Detail & Identitas</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                                    {{-- Tipe Publikasi --}}
                                    <div class="form-control w-full">
                                        <label for="publication_type" class="block text-sm font-semibold text-base-content/80 mb-1">Tipe Publikasi</label>
                                        {{-- REVISI: Tambah rounded-[15px] --}}
                                        <select id="publication_type" name="publication_type" class="select select-bordered rounded-[15px] {{ $errors->has('publication_type') ? 'select-error' : '' }}" required>
                                            <option value="" disabled {{ old('publication_type') ? '' : 'selected' }}>Pilih Tipe</option>
                                            <option value="ARC" {{ old('publication_type') == 'ARC' ? 'selected' : '' }}>ARC</option>
                                            <option value="Non ARC" {{ old('publication_type') == 'Non ARC' ? 'selected' : '' }}>Non ARC</option>
                                        </select>
                                        @error('publication_type')
                                            <p class="text-error text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Nomor Katalog --}}
                                    <div class="form-control w-full">
                                        <label for="catalog_number" class="block text-sm font-semibold text-base-content/80 mb-1">Nomor Katalog</label>
                                        {{-- REVISI: Tambah rounded-[15px] --}}
                                        <input id="catalog_number" name="catalog_number" type="text" value="{{ old('catalog_number') }}" placeholder="Contoh: 1101001" class="input input-bordered w-full rounded-[15px] {{ $errors->has('catalog_number') ? 'input-error' : '' }}" required />
                                        @error('catalog_number')
                                            <p class="text-error text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Tahun --}}
                                    <div class="form-control w-full">
                                        <label for="year" class="block text-sm font-semibold text-base-content/80 mb-1">Tahun</label>
                                        {{-- REVISI: Tambah rounded-[15px] --}}
                                        <input id="year" name="year" type="number" value="{{ old('year') }}" placeholder="Contoh: 2025" class="input input-bordered w-full rounded-[15px] {{ $errors->has('year') ? 'input-error' : '' }}" required />
                                        @error('year')
                                            <p class="text-error text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Frekuensi --}}
                                    <div class="form-control w-full">
                                        <label for="frequency" class="block text-sm font-semibold text-base-content/80 mb-1">Frekuensi</label>
                                        {{-- REVISI: Tambah rounded-[15px] --}}
                                        <input id="frequency" name="frequency" type="text" value="{{ old('frequency') }}" placeholder="Contoh: Tahunan" class="input input-bordered w-full rounded-[15px] {{ $errors->has('frequency') ? 'input-error' : '' }}" required />
                                        @error('frequency')
                                            <p class="text-error text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Nomor ISSN --}}
                                    <div class="form-control w-full">
                                        <label for="issn_number" class="block text-sm font-semibold text-base-content/80 mb-1">No ISSN <span class="text-gray-400 font-normal">(Opsional)</span></label>
                                        {{-- REVISI: Tambah rounded-[15px] --}}
                                        <input id="issn_number" name="issn_number" type="text" value="{{ old('issn_number') }}" class="input input-bordered w-full rounded-[15px] {{ $errors->has('issn_number') ? 'input-error' : '' }}" />
                                        @error('issn_number')
                                            <p class="text-error text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </section>

                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex justify-end items-center mt-8 pt-6 border-t border-base-200">
                            <a href="{{ route('publications.index') }}" class="btn btn-ghost mr-3">Batal</a>
                            {{-- REVISI: Tambah rounded-[15px] pada tombol primer --}}
                            <button type="submit" class="btn btn-primary btn-premium rounded-[15px]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Simpan Publikasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>