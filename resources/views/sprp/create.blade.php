<x-app-layout>
    {{-- CSS Kustom (Konsisten) --}}
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .rounded-\[15px\] {
            border-radius: 15px;
        }

        /* ▼▼▼ CSS UNTUK TOMSELECT AGAR SESUAI TEMA ▼▼▼ */
        .ts-control {
            border-radius: 15px !important;
            /* Menggunakan warna border dari DaisyUI */
            border-color: hsl(var(--bc) / 0.2) !important;
            padding-top: 0.6rem !important;
            padding-bottom: 0.6rem !important;
        }

        .ts-control.plugin-remove_button .item {
            border-radius: 10px;
            background: hsl(var(--p));
            /* Warna primary */
            color: hsl(var(--pc));
            /* Warna teks primary */
        }

        .ts-dropdown {
            border-radius: 15px !important;
            border-color: hsl(var(--bc) / 0.2) !important;
        }

        .ts-dropdown .option.active {
            background: hsl(var(--p) / 0.2);
            color: inherit;
        }

        /* ▲▲▲ AKHIR CSS TOMSELECT ▲▲▲ */
    </style>

    <x-slot name="header">
        <div class="fade-in">
            <h2 class="font-semibold text-xl text-base-content leading-tight">
                📝 {{ __('Buat Pengajuan SPRP Baru') }}
            </h2>
            <div class="text-sm breadcrumbs text-base-content/70">
                <ul>
                    <li><a href="{{ route('dashboard') }}" class="hover:text-primary">Dashboard</a></li>
                    <li><a href="{{ route('sprp.index') }}" class="hover:text-primary">Pengajuan SPRP</a></li>
                    <li>Buat Baru</li>
                </ul>
            </div>
        </div>
    </x-slot>

    <div class="py-12 fade-in">
        <div class="px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto">
            <div class="card bg-base-100 shadow-xl rounded-[15px]">
                <div class="card-body p-6 md:p-8">

                    {{-- Notifikasi Error --}}
                    @if ($errors->any())
                    <div class="alert alert-error mb-6 shadow-lg rounded-[15px]">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
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

                    @if(session('error'))
                    <div class="alert alert-error mb-6 shadow-lg rounded-[15px]">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                    @endif

                    {{-- Form ini mengirim SEMUA data ke controller --}}
                    <form action="{{ route('sprp.store') }}" method="POST" class="space-y-10">
                        @csrf

                        {{-- 📘 Bagian 1: Informasi Inti Publikasi (Disimpan ke submission_publications) --}}
                        <section>
                            <h3 class="text-lg font-semibold text-base-content mb-4">📘 Informasi pengajuan (sprp)</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                <div class="form-control w-full md:col-span-2">
                                    <label for="judul_publikasi" class="label"><span class="label-text">Judul Publikasi (Indonesia)</span></label>
                                    <input type="text" name="judul_publikasi" id="judul_publikasi" value="{{ old('judul_publikasi') }}" class="input input-bordered w-full rounded-[15px]" required>
                                </div>

                                <div class="form-control w-full md:col-span-2">
                                    <label for="judul_eng" class="label"><span class="label-text">Judul Publikasi (English)</span></label>
                                    <input type="text" name="judul_eng" id="judul_eng" value="{{ old('judul_eng') }}" class="input input-bordered w-full rounded-[15px]">
                                </div>

                                <div class="form-control w-full">
                                    <label for="catalog-search" class="label"><span class="label-text">Nomor Katalog</span></label>
                                    {{-- Ini adalah dropdown dengan fitur search --}}
                                    <select name="catalog_id" id="catalog-search" class="w-full rounded-[15px]" required>
                                        <option value="" disabled selected>-- Pilih atau cari Nomor Katalog --</option>
                                        @foreach($catalogs as $catalog)
                                        <option value="{{ $catalog->id }}" {{ old('catalog_id') == $catalog->id ? 'selected' : '' }}>
                                            {{ $catalog->nomor_katalog }} - {{ $catalog->judul_katalog }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-control w-full">
                                    <label for="type_publikasi" class="label"><span class="label-text">Tipe Publikasi</span></label>
                                    <select name="type_publikasi" id="type_publikasi" class="select select-bordered w-full rounded-[15px]" required>
                                        <option value="" disabled selected>-- Pilih Tipe --</option>
                                        <option value="Non ARC" {{ old('type_publikasi') == 'Non ARC' ? 'selected' : '' }}>Non ARC</option>
                                        <option value="ARC" {{ old('type_publikasi') == 'ARC' ? 'selected' : '' }}>ARC</option>
                                    </select>
                                </div>

                                <div class="form-control w-full">
                                    <label for="estimasi_rilis" class="label"><span class="label-text">Estimasi Rilis</span></label>
                                    <input type="date" name="estimasi_rilis" id="estimasi_rilis" value="{{ old('estimasi_rilis') }}" class="input input-bordered w-full rounded-[15px]" required>
                                </div>

                                <div class="form-control w-full">
                                    <label for="bahasa" class="label"><span class="label-text">Bahasa</span></label>
                                    <select name="bahasa" id="bahasa" class="select select-bordered w-full rounded-[15px]" required>
                                        <option value="" disabled selected>-- Pilih Bahasa --</option>
                                        <option value="Indonesia" {{ old('bahasa') == 'Indonesia' ? 'selected' : '' }}>Indonesia</option>
                                        <option value="English" {{ old('bahasa') == 'English' ? 'selected' : '' }}>English</option>
                                        <option value="Indonesia & English" {{ old('bahasa') == 'Indonesia & English' ? 'selected' : '' }}>Indonesia & English</option>
                                    </select>
                                </div>

                                
                            </div>
                        </section>

                        {{-- 📄 Bagian 2: Detail Registrasi SPRP (Disimpan ke sprps) --}}
                        <section>
                            <h3 class="text-lg font-semibold text-base-content mb-4">📄 Detail Registrasi (Data SPRP)</h3>
                            <div class="form-control w-full md:col-span-2">
                                <label for="fungsi_pengusul" class="label"><span class="label-text">Fungsi Pengusul</span></label>
                                <input type="text" name="fungsi_pengusul" id="fungsi_pengusul" value="{{ old('fungsi_pengusul') }}" class="input input-bordered w-full rounded-[15px]" placeholder="Misal: Fungsi Statistik Sosial" required>
                            </div>
                            <br>

                            <div class="form-control w-full md:col-span-2">
                                <label for="link_publikasi" class="label"><span class="label-text">Link Publikasi</span></label>
                                <input type="url" name="link_publikasi" id="link_publikasi" value="{{ old('link_publikasi') }}" class="input input-bordered w-full rounded-[15px]" placeholder="https://">
                            </div>
                            <br>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                {{-- KATEGORI (Ada di SPRPS) --}}
                                <div class="form-control w-full md:col-span-2">
                                    <label for="kategori" class="label"><span class="label-text">Kategori Publikasi</span></label>
                                <select name="kategori" id="kategori" class="select select-bordered w-full rounded-[15px]" required>
    <option value="">--Pilih Kategori--</option>
    <option value="Statistik Daerah">Statistik Daerah</option>
    <option value="Statistik Indonesia">Statistik Indonesia</option>
    <option value="Statistik Kesejahteraan Rakyat">Statistik Kesejahteraan Rakyat</option>
    <option value="Indikator Kesejahteraan Rakyat">Indikator Kesejahteraan Rakyat </option>
    <option value="PDRB Menurut Lapangan Usaha">PDRB Menurut Lapangan Usaha</option>
    <option value="PDRB Menurut Pengeluaran">PDRB Menurut Pengeluaran</option>
    <option value="PDRB Menurut Lapangan Usaha Triwulanan">PDRB Menurut Lapangan Usaha Triwulanan</option>
    <option value="PDRB Menurut Pengaluaran Triwulanan">PDRB Menurut Pengeluaran Triwulanan</option>
    <option value="PDRB Kabupaten/Kota di Provinsi">PDRB Kabupaten/Kota di Provinsi</option>
    <option value="Laporan Bulanan Data Sosial Ekonomi">Laporan Bulanan Data Sosial Ekonomi</option>
    <option value="Katalog Publikasi">Katalog Publikasi</option>
    <option value="Analisis Hasil Survei Kebutuhan Data BPS">Analisis Hasil Survei Kebutuhan Data BPS</option>
    <option value="Publikasi Lainya">Publikasi Lainnya</option>
</select>
                                </div>

                                <div class="form-control w-full">
                                    <label for="isbn" class="label"><span class="label-text">No. ISBN (Jika ada)</span></label>
                                    <input type="text" name="isbn" id="isbn" value="{{ old('isbn') }}" class="input input-bordered w-full rounded-[15px]">
                                </div>

                                <div class="form-control w-full">
                                    <label for="issn" class="label">
                                        <span class="label-text">No. ISSN (Jika ada)</span>
                                    </label>
                                    <select name="issn" id="issn" class="select select-bordered w-full rounded-[15px]">
                                        <option value="">--Pilih ISSN--</option>
                                        <option value="0215-5850" {{ old('issn') == '0215-5850' ? 'selected' : '' }}>0215-5850 - Kabupaten Tegal Dalam Angka</option>
                                        <option value="2598-1897" {{ old('issn') == '2598-1897' ? 'selected' : '' }}>2598-1897 - Produk Domestik Regional Bruto Kabupaten Tegal Menurut Lapangan Usaha</option>
                                        <option value="2598-1900" {{ old('issn') == '2598-1900' ? 'selected' : '' }}>2598-1900 - Statistik Daerah Kabupaten Tegal</option>
                                        <option value="2598-1919" {{ old('issn') == '2598-1919' ? 'selected' : '' }}>2598-1919 - Statistik Kependudukan Kabupaten Tegal</option>
                                        <option value="2598-1927" {{ old('issn') == '2598-1927' ? 'selected' : '' }}>2598-1927 - Statistik Kesejahteraan Rakyat Kabupaten Tegal</option>
                                        <option value="2621-1769" {{ old('issn') == '2621-1769' ? 'selected' : '' }}>2621-1769 - Kecamatan Bumijawa Dalam Angka</option>
                                        <option value="2621-1777" {{ old('issn') == '2621-1777' ? 'selected' : '' }}>2621-1777 - Kecamatan Pagerbarang Dalam Angka</option>
                                        <option value="2621-1785" {{ old('issn') == '2621-1785' ? 'selected' : '' }}>2621-1785 - Kecamatan Jatinegara Dalam Angka</option>
                                        <option value="2621-1793" {{ old('issn') == '2621-1793' ? 'selected' : '' }}>2621-1793 - Kecamatan Dukuhwaru Dalam Angka</option>
                                        <option value="2621-1807" {{ old('issn') == '2621-1807' ? 'selected' : '' }}>2621-1807 - Kecamatan Adiwerna Dalam Angka</option>
                                        <option value="2621-1815" {{ old('issn') == '2621-1815' ? 'selected' : '' }}>2621-1815 - Kecamatan Talang Dalam Angka</option>
                                        <option value="2621-1823" {{ old('issn') == '2621-1823' ? 'selected' : '' }}>2621-1823 - Kecamatan Dukuhturi Dalam Angka</option>
                                        <option value="2623-1913" {{ old('issn') == '2623-1913' ? 'selected' : '' }}>2623-1913 - Kecamatan Bojong Dalam Angka</option>
                                    </select>
                                </div>


                                <div class="form-control w-full">
                                    <label for="jumlah_romawi" class="label">
                                        <span class="label-text">Jumlah Halaman Romawi</span>
                                    </label>
                                    <select name="jumlah_romawi" id="jumlah_romawi" class="select select-bordered w-full rounded-[15px]">
                                        <option value="">Romawi</option>
                                        <option value="-">-</option>
                                        @php
                                        // Fungsi konversi angka ke romawi
                                        function toRoman($num) {
                                        $map = [
                                        'M' => 1000,
                                        'CM' => 900,
                                        'D' => 500,
                                        'CD' => 400,
                                        'C' => 100,
                                        'XC' => 90,
                                        'L' => 50,
                                        'XL' => 40,
                                        'X' => 10,
                                        'IX' => 9,
                                        'V' => 5,
                                        'IV' => 4,
                                        'I' => 1
                                        ];
                                        $returnValue = '';
                                        while ($num > 0) {
                                        foreach ($map as $roman => $int) {
                                        if ($num >= $int) {
                                        $num -= $int;
                                        $returnValue .= strtolower($roman);
                                        break;
                                        }
                                        }
                                        }
                                        return $returnValue;
                                        }
                                        @endphp

                                        @for ($i = 2; $i <= 1000; $i +=2)
                                            <option value="{{ toRoman($i) }}" {{ old('jumlah_romawi') == toRoman($i) ? 'selected' : '' }}>
                                            {{ toRoman($i) }}
                                            </option>
                                            @endfor
                                    </select>
                                </div>


                                <div class="form-control w-full">
                                    <label for="jumlah_arab" class="label"><span class="label-text">Jumlah Halaman Arab</span></label>
                                    <input type="text" name="jumlah_arab" id="jumlah_arab" class="input input-bordered w-full rounded-[15px]" placeholder="Contoh: 108" value="{{ old('jumlah_arab') }}">
                                </div>

                                <div class="form-control w-full">
                                    <label for="pembuat_cover" class="label"><span class="label-text">Pembuat Cover</span></label>
                                    <select name="pembuat_cover" id="pembuat_cover" class="select select-bordered w-full rounded-[15px]" required>
                                        <option value="" disabled selected>-- Pilih Pembuat Cover --</option>
                                        <option value="Subdit Publikasi/IPDS" {{ old('pembuat_cover') == 'Subdit Publikasi/IPDS' ? 'selected' : '' }}>Subdit Publikasi/IPDS</option>
                                        <option value="Subject Matter" {{ old('pembuat_cover') == 'Subject Matter' ? 'selected' : '' }}>Subject Matter</option>
                                    </select>
                                </div>

                                <div class="form-control w-full">
                                    <label for="diterbitkan_untuk" class="label"><span class="label-text">Diterbitkan Untuk</span></label>
                                    <select name="diterbitkan_untuk" id="diterbitkan_untuk" class="select select-bordered w-full rounded-[15px]" required>
                                        <option value="" disabled selected>-- Pilih Target --</option>
                                        <option value="Publik" {{ old('diterbitkan_untuk') == 'Publik' ? 'selected' : '' }}>Publik/Luar BPS</option>
                                        <option value="Internal" {{ old('diterbitkan_untuk') == 'Internal' ? 'selected' : '' }}>Internal/BPS</option>
                                    </select>
                                </div>

                                <div class="form-control w-full">
                                    <label for="orientasi" class="label"><span class="label-text">Orientasi</span></label>
                                    <select name="orientasi" id="orientasi" class="select select-bordered w-full rounded-[15px]" required>
                                        <option value="" disabled selected>-- Pilih Orientasi --</option>
                                        <option value="Portrait" {{ old('orientasi') == 'Portrait' ? 'selected' : '' }}>Portrait</option>
                                        <option value="Landscape" {{ old('orientasi') == 'Landscape' ? 'selected' : '' }}>Landscape</option>
                                    </select>
                                </div>

                                <div class="form-control w-full">
                                    <label for="ukuran_kertas" class="label"><span class="label-text">Ukuran Kertas</span></label>
                                    <select name="ukuran_kertas" id="ukuran_kertas" class="select select-bordered w-full rounded-[15px]" required>
                                        <option value="" disabled selected>-- Pilih Ukuran --</option>
                                        <option value="A4" {{ old('ukuran_kertas') == 'A4' ? 'selected' : '' }}>A4</option>
                                        <option value="A5" {{ old('ukuran_kertas') == 'A5' ? 'selected' : '' }}>A5</option>
                                        <option value="B5 JIS" {{ old('ukuran_kertas') == 'B5 JIS' ? 'selected' : '' }}>B5 JIS</option>
                                        <option value="B5 ISO" {{ old('ukuran_kertas') == 'B5 ISO' ? 'selected' : '' }}>B5 ISO</option>
                                        <option value="Lainnya" {{ old('ukuran_kertas') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                </div>
                            </div>
                        </section>

                        {{-- 🎯 Tombol Aksi --}}
                        <div class="flex justify-end gap-3 pt-6 border-t border-base-200">
                
                            <button type="submit" class="btn btn-primary text-black rounded-[15px]">
                                Simpan Pengajuan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ▼▼▼ SCRIPT UNTUK SEARCH DROPDOWN ▼▼▼ --}}
        @push('scripts')
        <!-- 1. Impor TomSelect (CSS & JS) -->
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // 2. Inisialisasi TomSelect pada dropdown katalog
                new TomSelect("#catalog-search", {
                    create: false, // Tidak boleh membuat data baru dari sini
                    sortField: {
                        field: "text",
                        direction: "asc"
                    }
                });
            });
        </script>
        @endpush

</x-app-layout>