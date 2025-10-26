<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- 
          ANIMASI KEREN & EFEK MODERN
          Kita tambahkan CSS kustom di sini untuk animasi.
        --}}
        <style>
            /* Animasi fade-in-up yang halus */
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Terapkan animasi ke panel branding */
            .brand-panel {
                animation: fadeInUp 0.7s ease-out;
            }

            /* Terapkan animasi ke panel form dengan sedikit delay */
            .form-panel {
                animation: fadeInUp 0.7s ease-out 0.2s;
                opacity: 0; /* Mulai transparan */
                animation-fill-mode: forwards; /* Tetap di state akhir */
            }

            /* BG GAMBAR PROFESIONAL (Opsional, tapi sangat direkomendasikan)
              Ganti URL dengan gambar Anda sendiri (misal: gedung, pemandangan, dll)
              Anda bisa unggah ke public/images/auth-bg.jpg
            */
            .brand-bg {
                /* background-image: url("{{ asset('images/auth-bg.jpg') }}"); */
                background-size: cover;
                background-position: center;
                position: relative;
            }

            /* Overlay gradien biru tua (profesional) di atas gambar */
            .brand-bg::before {
                content: '';
                position: absolute;
                top: 0; left: 0; right: 0; bottom: 0;
                /* Gradien warna biru profesional (sesuaikan dengan warna instansi Anda)
                  Ini akan tetap terlihat bagus meskipun Anda tidak pakai gambar BG.
                */
                background: linear-gradient(135deg, #0d47a1 0%, #1976d2 100%); /* Biru tua ke biru sedang */
                z-index: 1;
            }
            
            /* Konten di panel branding (logo, teks) harus di atas overlay */
            .brand-content {
                position: relative;
                z-index: 2;
            }
        </style>
    </head>
    <body class="font-sans text-black antialiased">
        
        {{-- 
          LAYOUT UTAMA SPLIT-SCREEN
          flex-col (mobile) menjadi flex-row (desktop)
        --}}
        <div class="min-h-screen flex flex-col md:flex-row">

            <div class="brand-bg brand-panel md:w-1/2 hidden md:flex flex-col items-center justify-center p-12 text-white text-center">
                <div class="brand-content">
                    
                    <img src="{{ asset('images/logo_bps.png') }}" alt="Logo Instansi" class="w-32 h-32 mx-auto mb-6">

                    <h1 class="text-4xl font-bold mb-3">
                        Aplikasi SPNSR
                    </h1>
                    <p class="text-xl text-blue-100">
                        Badan Pusat Statistik Kabupaten Tegal
                    </p>
                </div>
            </div>

            <div class="w-full md:w-1/2 bg-gray-50 flex items-center justify-center p-6 md:p-12">
                
                <div class="w-full max-w-md form-panel">
                
                    <div class="md:hidden text-center mb-6">
                         <img src="{{ asset('images/logo_bps.png') }}" alt="Logo Instansi" class="w-20 h-20 mx-auto">
                    </div>

                    <div class="bg-gray-500 p-8 md:p-10 shadow-xl rounded-lg">
                        {{-- 
                          DI SINILAH form login.blade.php, register.blade.php, dll.
                          akan disuntikkan secara otomatis.
                        --}}
                        {{ $slot }}
                    </div>
                </div>
            </div>

        </div>
    </body>
</html>