{{-- 
    Halaman untuk membuat data SPRP baru (Desain Modern & Rapi)
    Menggunakan Tailwind + struktur grid seragam agar setiap baris tidak berantakan.
--}}

<x-app-layout>
    {{-- 🌈 CSS Modern --}}
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #eef2ff 0%, #f8fafc 100%);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in { animation: fadeIn 0.6s ease-out forwards; }

        .card-modern {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(14px);
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.25);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .label-text { font-weight: 600; color: #1e293b; }
        .form-section { margin-bottom: 2rem; }
    </style>

    {{-- 🧭 Header --}}
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center fade-in">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    📝 {{ __('Buat Pengajuan SPRP Baru') }}
                </h2>
                <div class="text-sm breadcrumbs text-gray-500 mt-2">
                    <ul>
                        <li><a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
                        <li><a href="{{ route('sprp.index') }}" class="hover:text-blue-600">Pengajuan SPRP</a></li>
                        <li>Buat Baru</li>
                    </ul>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- 🧾 Konten Utama --}}
    <div class="py-12 fade-in" data-aos="fade-up">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="card-modern max-w-5xl mx-auto p-8">

                {{-- 🔔 Error Validasi --}}
                @if ($errors->any())
                    <div class="alert alert-error mb-6 shadow-lg">
                        <div class="flex gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current h-6 w-6" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <h3 class="font-bold">Terjadi Kesalahan!</h3>
                                <ul class="list-disc ml-5 text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- 🧩 Form --}}
                <form action="{{ route('sprp.store') }}" method="POST" class="space-y-10">
                    @csrf

                    {{-- 📘 Bagian 1: Informasi SPRP --}}
                    <div class="form-section">
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">📘 Informasi Publikasi</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-2">
                                <label class="label"><span class="label-text">Judul Publikasi</span></label>
                                <select name="publication_id" class="select select-bordered w-full" required>
                                    <option value="" disabled selected>-- Pilih Judul Publikasi --</option>
                                    @foreach($publications as $pub)
                                        <option value="{{ $pub->id }}" {{ old('publication_id') == $pub->id ? 'selected' : '' }}>
                                            [{{ $pub->publication_type }}] {{ $pub->title_ind }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="label"><span class="label-text">Kategori Publikasi</span></label>
                                <select name="kategori" class="select select-bordered w-full" required>
                                    <option value="" disabled selected>-- Pilih Kategori --</option>
                                        <option value="Statistik Kesejahteraan Rakyat" {{ old('kategori') == 'Statistik Kesejahteraan Rakyat' ? 'selected' : '' }}>Statistik Kesejahteraan Rakyat</option>
                                        <option value="Statistik Indonesia" {{ old('kategori') == 'Statistik Indonesia' ? 'selected' : '' }}>Statistik Indonesia</option>
                                        <option value="Statistik Daerah" {{ old('kategori') == 'Statistik Daerah' ? 'selected' : '' }}>Statistik Daerah</option>
                                        <option value="Indikator Kesejahteraan Rakyat" {{ old('kategori') == 'Indikator Kesejahteraan Rakyat' ? 'selected' : '' }}>Indikator Kesejahteraan Rakyat</option>
                                        <option value="PDRB Menurut Lapangan Usaha" {{ old('kategori') == 'PDRB Menurut Lapangan Usaha' ? 'selected' : '' }}>PDRB Menurut Lapangan Usaha</option>
                                        <option value="PDRB Menurut Pengeluaran" {{ old('kategori') == 'PDRB Menurut Pengeluaran' ? 'selected' : '' }}>PDRB Menurut Pengeluaran</option>
                                        <option value="PDRB Menurut Lapangan Usaha Triwulanan" {{ old('kategori') == 'PDRB Menurut Lapangan Usaha Triwulanan' ? 'selected' : '' }}>PDRB Menurut Lapangan Usaha Triwulanan</option>
                                        <option value="PDRB Menurut Pengeluaran Triwulanan" {{ old('kategori') == 'PDRB Menurut Pengeluaran Triwulanan' ? 'selected' : '' }}>PDRB Menurut Pengeluaran Triwulanan</option>
                                        <option value="PDRB Kabupaten/Kota di Provinsi" {{ old('kategori') == 'PDRB Kabupaten/Kota di Provinsi' ? 'selected' : '' }}>PDRB Kabupaten/Kota di Provinsi</option>
                                        <option value="PDRB Bulanan Data Sosial Ekonomi" {{ old('kategori') == 'PDRB Bulanan Data Sosial Ekonomi' ? 'selected' : '' }}>PDRB Bulanan Data Sosial Ekonomi</option>
                                        <option value="Katalog Publikasi" {{ old('kategori') == 'Katalog Publikasi' ? 'selected' : '' }}>Katalog Publikasi</option>
                                        <option value="Analisis Hasil Survei Kebutuhan Data BPS" {{ old('kategori') == 'Analisis Hasil Survei Kebutuhan Data BPS' ? 'selected' : '' }}>Analisis Hasil Survei Kebutuhan Data BPS</option>
                                        <option value="Publikasi Lainya" {{ old('kategori') == 'Publikasi Lainya' ? 'selected' : '' }}>Publikasi Lainya</option>
                                </select>
                            </div>

                            <div>
                                <label class="label"><span class="label-text">Pembuat Cover</span></label>
                                <select name="pembuat_cover" class="select select-bordered w-full" required>
                                    <option value="" disabled selected>-- Pilih Pembuat Cover --</option>
                                    <option value="Subdit Publikasi/IPDS">Subdit Publikasi/IPDS</option>
                                    <option value="Subject Matter">Subject Matter</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- 📄 Bagian 2: Detail Naskah --}}
                    <div class="form-section">
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">📄 Detail Naskah</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="label"><span class="label-text">No. ISBN (Jika ada)</span></label>
                                <input type="text" name="isbn" class="input input-bordered w-full" placeholder="Contoh: 978-602-XXX-XX-X" value="{{ old('isbn') }}">
                            </div>
                            <div>
                                <label class="label"><span class="label-text">No. ISSN (Jika ada)</span></label>
                                <input type="text" name="issn" class="input input-bordered w-full" placeholder="Contoh: 2774-16XX" value="{{ old('issn') }}">
                            </div>
                            <div>
                                <label class="label"><span class="label-text">Jumlah Halaman Romawi</span></label>
                                <input type="text" name="jumlah_romawi" class="input input-bordered w-full" placeholder="Contoh: xiv" value="{{ old('jumlah_romawi') }}">
                            </div>
                            <div>
                                <label class="label"><span class="label-text">Jumlah Halaman Arab</span></label>
                                <input type="text" name="jumlah_arab" class="input input-bordered w-full" placeholder="Contoh: 108" value="{{ old('jumlah_arab') }}">
                            </div>
                        </div>
                    </div>

                    {{-- 📐 Bagian 3: Format Publikasi --}}
                    <div class="form-section">
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">📐 Format Publikasi</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="label"><span class="label-text">Orientasi</span></label>
                                <select name="orientasi" class="select select-bordered w-full" required>
                                    <option value="" disabled selected>-- Pilih Orientasi --</option>
                                    <option value="Portrait">Portrait</option>
                                    <option value="Landscape">Landscape</option>
                                </select>
                            </div>
                            <div>
                                <label class="label"><span class="label-text">Diterbitkan Untuk</span></label>
                                <select name="diterbitkan_untuk" class="select select-bordered w-full" required>
                                    <option value="" disabled selected>-- Pilih Target --</option>
                                    <option value="Publik">Publik/Luar BPS</option>
                                    <option value="Internal">Internal/BPS</option>
                                </select>
                            </div>
                            <div>
                                <label class="label"><span class="label-text">Ukuran Kertas</span></label>
                                <select name="ukuran_kertas" class="select select-bordered w-full" required>
                                    <option value="" disabled selected>-- Pilih Ukuran --</option>
                                    <option value="A4">A4</option>
                                    <option value="A5">A5</option>
                                    <option value="B5 JIS">B5 JIS</option>
                                    <option value="B5 ISO">B5 ISO</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- 🎯 Tombol Aksi --}}
                    <div class="flex justify-end gap-3 pt-4">
                        <a href="{{ route('sprp.index') }}" class="btn btn-ghost">Batal</a>
                        <button type="submit" class="btn btn-primary text-black btn-premium flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            Simpan Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
        <script>AOS.init({ duration: 600, once: true });</script>
    @endpush
</x-app-layout>
