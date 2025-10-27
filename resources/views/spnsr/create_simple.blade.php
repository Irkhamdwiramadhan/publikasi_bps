<x-app-layout>
    {{-- CSS Kustom (Konsisten) --}}
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
        /* Tambahkan style untuk select2 jika Anda menggunakannya nanti */
    </style>

    {{-- Header Halaman (Konsisten) --}}
    <x-slot name="header">
        <div class="fade-in">
            <h2 class="font-semibold text-xl text-base-content leading-tight">
                ➕ {{ __('Buat Pengajuan SPNSR Baru') }}
            </h2>
            <div class="text-sm breadcrumbs text-base-content/70">
                 <ul>
                    <li><a href="{{ route('dashboard') }}" class="hover:text-primary">Dashboard</a></li>
                    <li><a href="{{ route('spnsr.index') }}" class="hover:text-primary">Pengajuan SPNSR</a></li>
                    <li>Buat Baru</li>
                </ul>
            </div>
        </div>
    </x-slot>

    {{-- Konten Utama --}}
    <div class="py-12 fade-in">
        <div class="px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto">

            <div class="card bg-base-100 shadow-xl">
                <div class="card-body p-6 md:p-8">

                    <h3 class="text-xl font-bold text-base-content/90 mb-6">Formulir Pengajuan SPNSR</h3>

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

                    {{-- REVISI: Form action diubah ke spnsr.store --}}
                    <form action="{{ route('spnsr.store') }}" method="POST">
                        @csrf

                        <div class="space-y-6">

                            {{-- Grup 1: Informasi Surat --}}
                            <section>
                                <h4 class="text-lg font-medium text-base-content/80 border-b border-base-200 pb-2 mb-4">Informasi Surat</h4>
                                <div class="space-y-4">
                                    {{-- Nomor Surat --}}
                                    <div class="form-control w-full">
                                        <label for="nomor_surat" class="block text-sm font-semibold text-base-content/80 mb-1">Nomor Surat</label>
                                        <input id="nomor_surat" type="text" name="nomor_surat" value="{{ old('nomor_surat') }}" placeholder="Contoh: B-262/3328/HM.600/2025" class="input input-bordered w-full rounded-[15px] {{ $errors->has('nomor_surat') ? 'input-error' : '' }}" required>
                                        @error('nomor_surat')
                                            <p class="text-error text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Kalimat Tanggal Surat --}}
                                    <div class="form-control w-full">
                                        <label for="tanggal_prosa" class="block text-sm font-semibold text-base-content/80 mb-1">Kalimat Tanggal Surat (Sesuai PDF)</label>
                                        <textarea id="tanggal_prosa" name="tanggal_prosa" rows="3" class="textarea textarea-bordered w-full rounded-[15px] {{ $errors->has('tanggal_prosa') ? 'textarea-error' : '' }}" required placeholder="Contoh: Pada hari Kamis tanggal dua puluh tujuh bulan Februari tahun dua ribu dua puluh lima">{{ old('tanggal_prosa') }}</textarea>
                                         <small class="text-xs text-base-content/60 mt-1">
                                            <b>Penting:</b> Masukkan kalimat tanggal lengkap seperti contoh di atas.
                                        </small>
                                        @error('tanggal_prosa')
                                            <p class="text-error text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </section>

                            {{-- Grup 2: Detail Publikasi --}}
                            <section>
                                <h4 class="text-lg font-medium text-base-content/80 border-b border-base-200 pb-2 mb-4">Detail Publikasi</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">

                                    {{-- REVISI: Judul Publikasi (Dropdown) --}}
                                    <div class="form-control w-full md:col-span-2">
                                        <label for="publication_id" class="block text-sm font-semibold text-base-content/80 mb-1">Pilih Judul Publikasi</label>
                                        {{-- Input hidden untuk menyimpan judul teks --}}
                                        <input type="hidden" name="judul_publikasi" id="judul_publikasi_text" value="{{ old('judul_publikasi') }}">
                                        {{-- Input hidden untuk menyimpan tipe arc --}}
                                        <input type="hidden" name="tipe_arc" id="tipe_arc_text" value="{{ old('tipe_arc') }}">

                                        <select id="publication_id" name="publication_id" class="select select-bordered w-full rounded-[15px] {{ $errors->has('publication_id') ? 'select-error' : '' }}" required>
                                            <option value="" disabled {{ old('publication_id') ? '' : 'selected' }}>-- Cari & Pilih Publikasi --</option>
                                            @foreach ($publications as $pub)
                                                <option value="{{ $pub->id }}"
                                                        data-title="{{ $pub->title_ind }}"
                                                        data-type="{{ $pub->publication_type }}"
                                                        @selected(old('publication_id') == $pub->id)>
                                                    [{{ $pub->publication_type }}] {{ $pub->title_ind }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('publication_id') {{-- Error untuk ID --}}
                                            <p class="text-error text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                         @error('judul_publikasi') {{-- Error untuk teks judul jika diperlukan --}}
                                            <p class="text-error text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                         @error('tipe_arc') {{-- Error untuk tipe arc jika diperlukan --}}
                                            <p class="text-error text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    {{-- AKHIR REVISI JUDUL --}}

                                    {{-- Keterangan (Pindah ke sini agar layout seimbang) --}}
                                    <div class="form-control w-full md:col-span-2"> {{-- Dibuat full width --}}
                                        <label for="keterangan" class="block text-sm font-semibold text-base-content/80 mb-1">Keterangan <span class="text-gray-400 font-normal">(Opsional)</span></label>
                                        <input id="keterangan" type="text" name="keterangan" value="{{ old('keterangan') }}" class="input input-bordered w-full rounded-[15px] {{ $errors->has('keterangan') ? 'input-error' : '' }}">
                                         @error('keterangan')
                                            <p class="text-error text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </section>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex justify-end items-center mt-8 pt-6 border-t border-base-200 gap-3">
                            <a href="{{ route('spnsr.index') }}" class="btn btn-ghost">Batal</a>
                             {{-- REVISI: Tombol Simpan --}}
                            <button type="submit" class="btn btn-primary btn-premium rounded-[15px] text-black">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                Simpan Pengajuan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- REVISI: Tambahkan script untuk update hidden input --}}
    @push('scripts')
        {{-- Opsional: Tambahkan Select2 untuk dropdown yang bisa dicari --}}
        {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
        {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
        {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const publicationSelect = document.getElementById('publication_id');
                const judulHiddenInput = document.getElementById('judul_publikasi_text');
                const tipeHiddenInput = document.getElementById('tipe_arc_text');

                // Inisialisasi Select2 (jika Anda memakainya)
                // $(publicationSelect).select2({ theme: "bootstrap-5" }); // Contoh tema

                publicationSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const title = selectedOption.getAttribute('data-title');
                    const type = selectedOption.getAttribute('data-type');

                    if (judulHiddenInput) {
                        judulHiddenInput.value = title || '';
                    }
                     if (tipeHiddenInput) {
                        tipeHiddenInput.value = type || '';
                    }
                });

                // Set initial value on page load if old value exists
                if (publicationSelect.value) {
                     const selectedOption = publicationSelect.options[publicationSelect.selectedIndex];
                     const title = selectedOption.getAttribute('data-title');
                     const type = selectedOption.getAttribute('data-type');
                     if (judulHiddenInput) judulHiddenInput.value = title || '';
                     if (tipeHiddenInput) tipeHiddenInput.value = type || '';
                }

            });
        </script>
    @endpush

</x-app-layout>