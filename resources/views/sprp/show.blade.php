{{-- 
    Halaman Detail (Show) SPRP
    Menampilkan semua data dari satu entri SPRP.
--}}
<x-app-layout>
    {{-- CSS Kustom untuk Animasi --}}
    <style>
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .fade-in { animation: fadeIn 0.8s ease-out forwards; }
        
        /* Styling untuk daftar detail */
        .detail-list {
            display: grid;
            grid-template-columns: repeat(1, minmax(0, 1fr));
            gap: 0.75rem 1.5rem; /* 3 6 */
        }
        @media (min-width: 768px) {
            .detail-list {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        .detail-item {
            display: flex;
            flex-direction: column;
            padding: 0.75rem; /* 3 */
            border-radius: 0.5rem; /* rounded-lg */
            background-color: hsl(var(--b2) / 0.5); /* bg-base-200/50 */
        }
        .detail-item dt {
       
            color: hsl(var(--bc) / 0.7); /* text-base-content/70 */
            font-size: 0.875rem; /* text-sm */
            line-height: 1.25rem;
            margin-bottom: 0.25rem; /* mb-1 */
        }
        .detail-item dd {
           
            color: hsl(var(--bc)); /* text-base-content */
            font-size: 1rem; /* text-base */
            line-height: 1.5rem;
        }
    </style>

    {{-- Header Halaman --}}
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-black leading-tight">
                {{ __('Detail Pengajuan SPRP') }}
            </h2>
            <div class="text-sm breadcrumbs text-black">
                <ul>
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li> 
                    <li><a href="{{ route('sprp.index') }}">Pengajuan SPRP</a></li>
                    <li>Detail: {{ $sprp->publication->title_ind ?? 'N/A' }}</li>
                </ul>
            </div>
        </div>
    </x-slot>

    {{-- Konten Utama --}}
    <div class="py-12 fade-in">
        <div class="px-4 sm:px-6 lg:px-8">
            
            {{-- Form Card --}}
            <div class="card bg-base-100 shadow-xl max-w-4xl mx-auto">
                <div class="card-body p-6 md:p-8">

                    {{-- Header Kartu --}}
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-xl font-bold">{{ $sprp->publication->title_ind ?? 'Judul Tidak Ditemukan' }}</h3>
                            
                        </div>
                     
                    </div>
                    
                    <div class="divider"></div>

                    {{-- Daftar Detail --}}
                    <dl class="detail-list">
                        <div class="detail-item"><dt>Jenis Publikasi</dt> <dd>{{ $sprp->publication->publication_type ?? 'Judul Tidak Ditemukan' }}</dd></div>
                        <div class="detail-item"><dt>Katalog</dt> <dd>{{ $sprp->publication->catalog_number ?? 'Judul Tidak Ditemukan' }}</dd></div>
           
                        


                        
                        {{-- Data dari form create --}}
                        <div class="detail-item"><dt>Kategori</dt> <dd>{{ $sprp->kategori ?? '-' }}</dd></div>
                        <div class="detail-item"><dt>Pembuat Cover</dt> <dd>{{ $sprp->pembuat_cover ?? '-' }}</dd></div>
                        <div class="detail-item"><dt>ISBN</dt> <dd>{{ $sprp->isbn ?? '-' }}</dd></div>
                        <div class="detail-item"><dt>ISSN</dt> <dd>{{ $sprp->issn ?? '-' }}</dd></div>
                        <div class="detail-item"><dt>Halaman Romawi</dt> <dd>{{ $sprp->jumlah_romawi ?? '-' }}</dd></div>
                        <div class="detail-item"><dt>Halaman Arab</dt> <dd>{{ $sprp->jumlah_arab ?? '-' }}</dd></div>
                        <div class="detail-item"><dt>Orientasi</dt> <dd>{{ $sprp->orientasi ?? '-' }}</dd></div>
                        <div class="detail-item"><dt>Diterbitkan Untuk</dt> <dd>{{ $sprp->diterbitkan_untuk ?? '-' }}</dd></div>
                        <div class="detail-item"><dt>Ukuran Kertas</dt> <dd>{{ $sprp->ukuran_kertas ?? '-' }}</dd></div>
                        
                    
                        <div class="detail-item"><dt>No. Publikasi</dt> <dd>{{ $sprp->nomor_publikasi_final ?? '-' }}</dd></div>

                      
                    </dl>

                    {{-- Tombol Aksi Bawah --}}
                    <div class="card-actions justify-end pt-6 mt-6 border-t border-base-200">
                        <a href="{{ route('sprp.index') }}" class="btn btn-ghost">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

