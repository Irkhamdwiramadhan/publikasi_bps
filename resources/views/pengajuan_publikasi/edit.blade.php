{{-- 
    Halaman ini adalah untuk "Buat Pengajuan Baru" (Halaman 8 PDF)
    Penyusun memilih publikasi dan mengunggah tautan.
--}}
<x-app-layout>
    {{-- CSS Kustom --}}
    <style>
        body { font-family: 'Inter', sans-serif; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .fade-in { animation: fadeIn 0.8s ease-out forwards; }
    </style>

    {{-- Header Halaman --}}
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-black leading-tight">
                Buat Pengajuan Publikasi Baru
            </h2>
            <div class="text-sm breadcrumbs text-gray-500">
                <ul>
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li> 
                    <li><a href="{{ route('pengajuan_publikasi.index') }}">Pengajuan Publikasi</a></li> 
                    <li>Buat Baru</li>
                </ul>
            </div>
        </div>
    </x-slot>

    {{-- Konten Utama --}}
    <div class="py-12 fade-in">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="card bg-base-100 shadow-xl max-w-3xl mx-auto">
                <form action="{{ route('pengajuan_publikasi.store') }}" method="POST">
                    @csrf
                    <div class="card-body p-6 md:p-8">
                        <h3 class="text-xl font-bold text-base-content/90 mb-6">Formulir Pengajuan (SPNSR)</h3>

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

                        <div class="grid grid-cols-1 gap-y-4">
                            
                            {{-- 1. Judul Publikasi --}}
                            <div class="form-control w-full">
                                <label class="label"><span class="label-text font-semibold">Judul Publikasi</span></label>
                                <select name="publication_id" class="select select-bordered w-full" required>
                                    <option disabled selected value="">Pilih publikasi dari master data...</option>
                                    @foreach($publications as $pub)
                                        <option value="{{ $pub->id }}" {{ old('publication_id') == $pub->id ? 'selected' : '' }}>
                                            [{{ $pub->publication_type }}] - {{ $pub->title_ind }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- 2. Fungsi Pengusul --}}
                            <div class="form-control w-full">
                                <label class="label"><span class="label-text font-semibold">Fungsi Pengusul</span></label>
                                <select name="fungsi_pengusul" class="select select-bordered w-full" required>
                                    <option disabled selected value="">Pilih fungsi Anda...</option>
                                    <option value="Produksi" {{ old('fungsi_pengusul') == 'Produksi' ? 'selected' : '' }}>Produksi</option>
                                    <option value="Distribusi" {{ old('fungsi_pengusul') == 'Distribusi' ? 'selected' : '' }}>Distribusi</option>
                                    <option value="Sosial" {{ old('fungsi_pengusul') == 'Sosial' ? 'selected' : '' }}>Sosial</option>
                                    <option value="IPDS" {{ old('fungsi_pengusul') == 'IPDS' ? 'selected' : '' }}>IPDS</option>
                                    <option value="Umum" {{ old('fungsi_pengusul') == 'Umum' ? 'selected' : '' }}>Umum</option>
                                </select>
                            </div>

                            {{-- 3. Tautan Publikasi --}}
                            <div class="form-control w-full">
                                <label class="label">
                                    <span class="label-text font-semibold">Tautan Publikasi (Draft)</span>
                                    <span class="label-text-alt">Contoh: Google Drive, Dropbox, dll.</span>
                                </label>
                                <input type="url" name="tautan_publikasi" placeholder="https://link-publikasi-anda.com" 
                                       class="input input-bordered w-full" value="{{ old('tautan_publikasi') }}">
                            </div>

                            {{-- 4. Tautan SPNSR Ketua Tim --}}
                            <div class="form-control w-full">
                                <label class="label">
                                    <span class="label-text font-semibold">Tautan SPNSR Ketua Tim</span>
                                    <span class="label-text-alt">Surat Persetujuan Naskah Siap Rilis</span>
                                </label>
                                <input type="url" name="spnrs_ketua_tim" placeholder="https://link-spnsr-ketua-tim.com" 
                                       class="input input-bordered w-full" value="{{ old('spnrs_ketua_tim') }}">
                            </div>

                        </div>
                        
                        <div class="flex justify-end items-center mt-8 pt-6 border-t border-base-200">
                            <a href="{{ route('pengajuan_publikasi.index') }}" class="btn btn-ghost mr-3">Batal</a>
                            <button type="submit" class="btn btn-primary text-white">
                                Simpan Pengajuan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
