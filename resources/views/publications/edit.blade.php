{{-- 
    File ini adalah versi revisi total UI/UX halaman Edit Publikasi.
    Desainnya disamakan dengan halaman index untuk menciptakan pengalaman yang konsisten,
    menggunakan layout lebar dan gaya form yang bersih.
--}}

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
            <h2 class="font-semibold text-xl text-black leading-tight">
                {{ __('Edit Publikasi') }}
            </h2>
            <div class="text-sm breadcrumbs text-gray-500">
                <ul>
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li> 
                    <li><a href="{{ route('publications.index') }}">Master Publikasi</a></li> 
                    <li>Edit</li>
                </ul>
            </div>
        </div>
    </x-slot>

    {{-- Konten Utama --}}
    <div class="py-12 fade-in">
        <div class="px-4 sm:px-6 lg:px-8">

            <div class="card bg-base-100 shadow-xl max-w-4xl mx-auto">
                <div class="card-body p-6 md:p-8">

                    <h3 class="text-xl font-bold text-base-content/90 mb-6">Formulir Edit Publikasi</h3>

                    {{-- Menampilkan Error Validasi --}}
                    @if ($errors->any())
                        <div class="alert alert-error mb-6 shadow-lg">
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span><b>Terjadi Kesalahan:</b> Periksa kembali data yang Anda masukkan.</span>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('publications.update', $publication->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                            
                            {{-- Judul Publikasi (IND) --}}
                            <div class="form-control w-full md:col-span-2">
                                <label class="label"><span class="label-text font-semibold">Judul Publikasi (IND)</span></label>
                                <input name="title_ind" type="text" value="{{ old('title_ind', $publication->title_ind) }}" class="input input-bordered w-full" required />
                            </div>

                            {{-- Judul Publikasi (ENG) --}}
                            <div class="form-control w-full md:col-span-2">
                                <label class="label"><span class="label-text font-semibold">Judul Publikasi (ENG)</span></label>
                                <input name="title_eng" type="text" value="{{ old('title_eng', $publication->title_eng) }}" placeholder="Opsional" class="input input-bordered w-full" />
                            </div>
                            
                            {{-- Tipe Publikasi --}}
                            <div class="form-control w-full">
                                <label class="label"><span class="label-text font-semibold">Tipe Publikasi</span></label>
                                <select name="publication_type" class="select select-bordered" required>
                                    <option disabled>Pilih Tipe</option>
                                    <option value="ARC" {{ old('publication_type', $publication->publication_type) == 'ARC' ? 'selected' : '' }}>ARC</option>
                                    <option value="Non ARC" {{ old('publication_type', $publication->publication_type) == 'Non ARC' ? 'selected' : '' }}>Non ARC</option>
                                </select>
                            </div>

                            {{-- Nomor Katalog --}}
                            <div class="form-control w-full">
                                <label class="label"><span class="label-text font-semibold">Nomor Katalog</span></label>
                                <input name="catalog_number" type="text" value="{{ old('catalog_number', $publication->catalog_number) }}" class="input input-bordered w-full" required />
                            </div>

                            {{-- Tahun --}}
                            <div class="form-control w-full">
                                <label class="label"><span class="label-text font-semibold">Tahun</span></label>
                                <input name="year" type="number" value="{{ old('year', $publication->year) }}" class="input input-bordered w-full" required />
                            </div>

                            {{-- Frekuensi --}}
                            <div class="form-control w-full">
                                <label class="label"><span class="label-text font-semibold">Frekuensi</span></label>
                                <input name="frequency" type="text" value="{{ old('frequency', $publication->frequency) }}" class="input input-bordered w-full" required />
                            </div>

                            {{-- Nomor ISSN --}}
                            <div class="form-control w-full">
                                <label class="label"><span class="label-text font-semibold">No ISSN</span></label>
                                <input name="issn_number" type="text" value="{{ old('issn_number', $publication->issn_number) }}" placeholder="Opsional" class="input input-bordered w-full" />
                            </div>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex justify-end items-center mt-8 pt-6 border-t border-base-200">
                            <a href="{{ route('publications.index') }}" class="btn btn-ghost mr-3">Batal</a>
                            <button type="submit" class="btn btn-primary btn-premium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                    <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                </svg>
                                Update Publikasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

