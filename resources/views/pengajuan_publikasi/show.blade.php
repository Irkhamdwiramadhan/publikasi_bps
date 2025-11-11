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
            border-color: hsl(var(--bc) / 0.2) !important;
            padding-top: 0.6rem !important;
            padding-bottom: 0.6rem !important;
        }

        .ts-control.plugin-remove_button .item {
            border-radius: 10px;
            background: hsl(var(--p));
            color: hsl(var(--pc));
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

        /* ▼▼▼ CSS Kustom untuk Halaman Detail (BARU) ▼▼▼ */
        /* Menggunakan kelas bawaan Tailwind sebisa mungkin untuk styling <dl> */
        .detail-label {
            font-size: 0.875rem;
            /* text-sm */
            font-weight: 500;
            /* medium */
            color: hsl(var(--bc) / 0.7);
            /* text-base-content/70 */
        }

        .detail-value {
            font-size: 1rem;
            /* text-base */
            font-weight: 500;
            /* medium */
            color: hsl(var(--bc));
            margin-top: 0.25rem;
            /* mt-1 */
            word-break: break-words;
            /* Mencegah teks panjang merusak layout */
        }

        /* Style untuk layout <dl> di layar desktop */
        @media (min-width: 768px) {
            .dl-layout {
                display: grid;
                grid-template-columns: repeat(1, 1fr);
                /* Default 1 kolom */
            }

            .dl-layout>div {
                padding-top: 1.5rem;
                /* py-6 */
                padding-bottom: 1.5rem;
                /* py-6 */
                border-top-width: 1px;
                /* border-t */
                border-color: hsl(var(--bc) / 0.1);
                /* border-base-200 */
                display: grid;
                grid-template-columns: 1fr 2fr;
                /* 1 bagian label, 2 bagian data */
                gap: 1.5rem;
                /* gap-6 */
            }

            .dl-layout>div:first-child {
                border-top: none;
            }

            .dl-layout .dl-value-only {
                /* Untuk item yang hanya punya nilai, seperti icon */
                grid-column-start: 2;
                margin-top: 0;
            }
        }

        /* ▲▲▲ AKHIR CSS Halaman Detail ▲▲▲ */
    </style>

    <x-slot name="header">
        <div class="fade-in">
            <h2 class="font-semibold text-xl text-base-content leading-tight">
                📄 {{ __('Detail Pengajuan SPRP') }}
            </h2>
            <div class="text-sm breadcrumbs text-base-content/70">
                <ul>
                    <li><a href="{{ route('dashboard') }}" class="hover:text-primary">Dashboard</a></li>
                    <li><a href="{{ route('sprp.index') }}" class="hover:text-primary">Pengajuan SPRP</a></li>
                    <li>Detail Pengajuan</li>
                </ul>
            </div>
        </div>
    </x-slot>

    <div class="py-12 fade-in">
        <div class="px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto">
            <div class="card bg-base-100 shadow-xl rounded-[15px]">
                <div class="card-body p-6 md:p-8">

                    {{-- Judul dan Tombol Aksi --}}
                    <div class="flex flex-col sm:flex-row justify-between sm:items-start mb-6 pb-6 border-b border-base-200 gap-4">
                        <div class="flex-1">
                            <h3 class="card-title text-2xl font-bold break-words max-w-full">
                                {{ $sprp->submissionPublication->judul_publikasi ?? 'Data Tidak Ditemukan' }}
                            </h3>
                            <p class="text-lg text-base-content/70 italic mt-1">
                                {{ $sprp->submissionPublication->judul_eng ?? '-' }}
                            </p>
                        </div>
                        <div class="flex-shrink-0 flex gap-3">
                            <a href="{{ route('pengajuan_publikasi.index') }}" class="btn btn-ghost rounded-[15px] btn-sm sm:btn-md">
                                <svg xmlns="http://www.w.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h18" />
                                </svg>
                                Kembali
                            </a>
                        </div>
                    </div>

                    <div classs="space-y-10">

                        {{-- 📘 Bagian 1: Informasi Inti Publikasi --}}
                        <section>
                            <h3 class="text-lg font-semibold text-base-content mb-6">📘 Informasi Inti Publikasi</h3>
                            
                            {{-- TATA LETAK BARU MENGGUNAKAN <dl> --}}
                            <dl class="dl-layout">
                                {{-- Item 1: Nomor Katalog --}}
                                <div class="md:grid-cols-[1fr_2fr]">
                                    <dt class="detail-label">Nomor Katalog</dt>
                                    <dd class="detail-value">
                                        {{ $sprp->submissionPublication->catalog->nomor_katalog ?? 'N/A' }} -
                                        {{ $sprp->submissionPublication->catalog->judul_katalog ?? 'N/A' }}
                                    </dd>
                                </div>

                                {{-- Item 2: Tipe Publikasi --}}
                                <div class="md:grid-cols-[1fr_2fr]">
                                    <dt class="detail-label">Tipe Publikasi</dt>
                                    <dd class="detail-value">{{ $sprp->submissionPublication->type_publikasi }}</dd>
                                </div>

                                {{-- Item 3: Estimasi Rilis --}}
                                <div class="md:grid-cols-[1fr_2fr]">
                                    <dt class="detail-label">Estimasi Rilis</dt>
                                    <dd class="detail-value">
                                        {{ \Carbon\Carbon::parse($sprp->submissionPublication->estimasi_rilis)->translatedFormat('d F Y') }}
                                    </dd>
                                </div>

                                {{-- Item 4: Bahasa --}}
                                <div class="md:grid-cols-[1fr_2fr]">
                                    <dt class="detail-label">Bahasa</dt>
                                    <dd class="detail-value">{{ $sprp->submissionPublication->bahasa }}</dd>
                                </div>
                            </dl>
                        </section>

                        <div class="divider my-10"></div>

                        {{-- 📄 Bagian 2: Detail Registrasi SPRP --}}
                        <section>
                            <h3 class="text-lg font-semibold text-base-content mb-6">📄 Detail Registrasi (Data SPRP)</h3>

                            {{-- TATA LETAK BARU MENGGUNAKAN <dl> --}}
                            <dl class="dl-layout">
                                {{-- Item 1: Fungsi Pengusul --}}
                                <div class="md:grid-cols-[1fr_2fr]">
                                    <dt class="detail-label">Fungsi Pengusul</dt>
                                    <dd class="detail-value">{{ $sprp->submissionPublication->fungsi_pengusul ?? 'N/A' }}</dd>
                                </div>

                                {{-- Item 2: Link Publikasi (PERMINTAAN ICON) --}}
                                <div class="md:grid-cols-[1fr_2fr]">
                                    <dt class="detail-label">Link Publikasi</dt>
                                    <dd class="dl-value-only text-white"> {{-- class 'dl-value-only' untuk menyejajarkan icon di kolom kedua --}}
                                        @if(!empty($sprp->submissionPublication->tautan_publikasi))
                                            <a href="{{ $sprp->submissionPublication->tautan_publikasi }}" target="_blank"
                                               class="btn btn-primary text-black btn-circle" data-tip="Buka Tautan Eksternal">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                                    <path fill-rule="evenodd" d="M4.25 5.5a.75.75 0 00-.75.75v8.5c0 .414.336.75.75.75h8.5a.75.75 0 00.75-.75v-4a.75.75 0 011.5 0v4A2.25 2.25 0 0112.75 17h-8.5A2.25 2.25 0 012 14.75v-8.5A2.25 2.25 0 014.25 4h5a.75.75 0 010 1.5h-5z" clip-rule="evenodd" />
                                                    <path fill-rule="evenodd" d="M6.25 10a.75.75 0 00.75.75h5.5a.75.75 0 000-1.5h-5.5a.75.75 0 00-.75.75z" clip-rule="evenodd" />
                                                    <path fill-rule="evenodd" d="M10.75 3.25a.75.75 0 01.75-.75h3.5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-2.25L9.53 9.53a.75.75 0 11-1.06-1.06L13.75 3.25H11.5a.75.75 0 01-.75-.75z" clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                        @else
                                            <span class="detail-value text-base-content/50 italic">Tidak ada tautan</span>
                                        @endif
                                    </dd>
                                </div>

                                {{-- Item 3: Kategori --}}
                                <div class="md:grid-cols-[1fr_2fr]">
                                    <dt class="detail-label">Kategori Publikasi</dt>
                                    <dd class="detail-value">{{ $sprp->kategori ?? 'N/A' }}</dd>
                                </div>

                                {{-- Item 4: ISBN --}}
                                <div class="md:grid-cols-[1fr_2fr]">
                                    <dt class="detail-label">No. ISBN (Jika ada)</dt>
                                    <dd class="detail-value">{{ $sprp->submissionPublication->isbn ?? '-' }}</dd>
                                </div>

                                {{-- Item 5: ISSN --}}
                                <div class="md:grid-cols-[1fr_2fr]">
                                    <dt class="detail-label">No. ISSN (Jika ada)</dt>
                                    <dd class="detail-value">{{ $sprp->submissionPublication->issn ?? '-' }}</dd>
                                </div>

                                {{-- Item 6: Halaman Romawi --}}
                                <div class="md:grid-cols-[1fr_2fr]">
                                    <dt class="detail-label">Jumlah Halaman Romawi</dt>
                                    <dd class="detail-value">{{ $sprp->jumlah_romawi ?? '-' }}</dd>
                                </div>

                                {{-- Item 7: Halaman Arab --}}
                                <div class="md:grid-cols-[1fr_2fr]">
                                    <dt class="detail-label">Jumlah Halaman Arab</dt>
                                    <dd class="detail-value">{{ $sprp->jumlah_arab ?? '-' }}</dd>
                                </div>

                                {{-- Item 8: Pembuat Cover --}}
                                <div class="md:grid-cols-[1sfr_2fr]">
                                    <dt class="detail-label">Pembuat Cover</dt>
                                    <dd class="detail-value">{{ $sprp->pembuat_cover }}</dd>
                                </div>

                                {{-- Item 9: Target --}}
                                <div class="md:grid-cols-[1fr_2fr]">
                                    <dt class="detail-label">Diterbitkan Untuk</dt>
                                    <dd class="detail-value">{{ $sprp->diterbitkan_untuk }}</dd>
                                </div>

                                {{-- Item 10: Orientasi --}}
                                <div class="md:grid-cols-[1fr_2fr]">
                                    <dt class="detail-label">Orientasi</dt>
                                    <dd class="detail-value">{{ $sprp->orientasi }}</dd>
                                </div>

                                {{-- Item 11: Ukuran Kertas --}}
                                <div class="md:grid-cols-[1fr_2fr]">
                                    <dt class="detail-label">Ukuran Kertas</dt>
                                    <dd class="detail-value">{{ $sprp->ukuran_kertas }}</dd>
                                </div>
                            </dl>
                        </section>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>