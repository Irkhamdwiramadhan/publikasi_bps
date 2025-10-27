<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="{ theme: localStorage.getItem('theme') || 'light' }" 
      x-bind:data-theme="theme">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Aplikasi Publikasi') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Scrollbar sidebar */
        .sidebar-scroll::-webkit-scrollbar {
            width: 6px;
        }
        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: hsl(var(--b3));
            border-radius: 3px;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: hsl(var(--b1));
        }
    </style>
</head>

<body class="font-sans antialiased bg-base-200/50 dark:bg-base-300">

    <div class="drawer lg:drawer-open">
        <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />

        {{-- KONTEN UTAMA --}}
        <div class="drawer-content flex flex-col min-h-screen">
            {{-- ... (Navbar, Header, Konten, Footer Anda tetap sama) ... --}}
            @include('layouts.navigation')

            @if (isset($header))
            <header class="bg-base-100 dark:bg-base-200 shadow-sm">
                <div class="py-4 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
            @endif

            <main class="flex-grow">
                {{ $slot }}
            </main>
            
            <footer class="py-4 text-center text-sm text-base-content/50">
                Copyright © {{ date('Y') }} - All right reserved by BPS Kabupaten Tegal
            </footer>
        </div>
        

        {{-- 
          SIDEBAR (MODIFIKASI DI SINI)
        --}}
        <div class="drawer-side">
            <label for="my-drawer-2" aria-label="close sidebar" class="drawer-overlay"></label>
            
            {{-- 
              PERUBAHAN:
              1. Hapus 'bg-base-100' dan 'text-base-content'
              2. Tambahkan class gradien, shadow, dan rounded dari file sidebar.blade.php
              3. Hapus 'p-4' dari <nav> karena padding sudah diatur di dalam sidebar.
            --}}
            <aside class="w-64 min-h-full 
                          bg-slate-300 from-black via-gray-50 to-gray-100 text-white
                          shadow-md rounded-r-2xl overflow-hidden">
                
                {{-- Kita buat <nav> ini membungkus sidebar dan mengelola scrolling --}}
                <nav class="flex flex-col h-full sidebar-scroll overflow-y-auto">
                    @include('layouts.sidebar')
                </nav>
            </aside>
        </div>
    </div>

    @stack('scripts')

</body>
</html>
