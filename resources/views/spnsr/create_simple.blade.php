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
    </style>

    {{-- Header Halaman (Konsisten) --}}
    <x-slot name="header">
        <div class="fade-in">
            <h2 class="font-semibold text-xl text-base-content leading-tight">
                📄 {{ __('Formulir Cetak SPNSR') }}
            </h2>
            <div class="text-sm breadcrumbs text-base-content/70">
                <ul>
                    <li><a href="{{ route('dashboard') }}" class="hover:text-primary">Dashboard</a></li> 
                    <li>Cetak SPNSR</li>
                </ul>
            </div>
        </div>
    </x-slot>

    {{-- Konten Utama --}}
    <div class="py-12 fade-in">
        <div class="px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto">

            <div class="card bg-base-100 shadow-xl">
                <div class="card-body p-6 md:p-8">

                    <h3 class="text-xl font-bold text-base-content/90 mb-6">Masukkan Detail SPNSR</h3>

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

                    <form action="{{ route('spnsr.generate') }}" method="POST">
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
                                    {{-- Judul Publikasi --}}
                                    <div class="form-control w-full md:col-span-2">
                                        <label for="judul_publikasi" class="block text-sm font-semibold text-base-content/80 mb-1">Judul Publikasi</label>
                                        <input id="judul_publikasi" type="text" name="judul_publikasi" value="{{ old('judul_publikasi') }}" placeholder="Contoh: Kabupaten Tegal Dalam Angka 2025" class="input input-bordered w-full rounded-[15px] {{ $errors->has('judul_publikasi') ? 'input-error' : '' }}" required>
                                        @error('judul_publikasi')
                                            <p class="text-error text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- ARC/Non ARC --}}
                                    <div class="form-control w-full">
                                        <label for="tipe_arc" class="block text-sm font-semibold text-base-content/80 mb-1">ARC/Non ARC</label>
                                        <select id="tipe_arc" name="tipe_arc" class="select select-bordered w-full rounded-[15px] {{ $errors->has('tipe_arc') ? 'select-error' : '' }}" required>
                                            <option value="" disabled {{ old('tipe_arc') ? '' : 'selected' }}>-- Pilih Tipe --</option>
                                            <option value="ARC" @if(old('tipe_arc') == 'ARC') selected @endif>ARC</option>
                                            <option value="Non ARC" @if(old('tipe_arc') == 'Non ARC') selected @endif>Non ARC</option>
                                        </select>
                                        @error('tipe_arc')
                                            <p class="text-error text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Keterangan --}}
                                    <div class="form-control w-full">
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
                            {{-- Tombol Batal bisa ditambahkan jika perlu --}}
                            {{-- <a href="{{ route('dashboard') }}" class="btn btn-ghost">Batal</a> --}} 
                            <button type="submit" class="btn btn-primary btn-premium rounded-[15px] text-black">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                  <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v3a2 2 0 002 2h6a2 2 0 002-2v-3h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v3h6v-3z" clip-rule="evenodd" />
                                  <path d="M9 11h2v2H9v-2z" />
                                </svg>
                                Cetak PDF
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>