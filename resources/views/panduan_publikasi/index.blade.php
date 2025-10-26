<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            📖 Panduan Pengguna Aplikasi
        </h2>
    </x-slot>

    <div class="p-6">
        {{-- 
          Kita gunakan max-w-7xl agar lebih lebar 
          dan h-[85vh] agar iframe-nya tinggi 
        --}}
        <div class="bg-white shadow rounded-xl p-6 max-w-7xl mx-auto h-[85vh]">
            
            {{-- 
              Ini adalah tag iframe untuk me-embed PDF.
              Kita panggil file PDF dari folder public menggunakan asset().
            --}}
      <iframe 
   src="{{ asset('panduan/panduan_aplikasi.pdf') }}"

    class="w-full h-full rounded-lg border"
    frameborder="0">
</iframe>


        </div>
    </div>
</x-app-layout>