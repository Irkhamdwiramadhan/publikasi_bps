<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="{ theme: localStorage.getItem('theme') || 'light' }" 
      x-bind:data-theme="theme">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Aplikasi Publikasi') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Sidebar scrollbar styling */
        .sidebar-scroll::-webkit-scrollbar {
            width: 5px;
        }
        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.4);
        }

        /* Table styling (keep original muted style) */
        .table-muted {
            background: #f3f4f6;
            color: #0f172a;
            border-radius: 0.5rem;
            overflow: hidden;
        }
        .table-muted thead {
            background: #e6edf8;
            color: #0b2440;
            font-weight: 600;
        }
        .table-muted tbody tr:nth-child(even) {
            background: #eef2f6;
        }

        /* Animation utilities */
        .soft-transition {
            transition: all 220ms cubic-bezier(.2,.9,.2,1);
        }
        .fade-in-up {
            opacity: 0;
            transform: translateY(8px);
            animation: fadeInUp 420ms forwards cubic-bezier(.2,.9,.2,1);
        }
        @keyframes fadeInUp {
            to { opacity: 1; transform: translateY(0); }
        }
        .ring-focus:focus {
            outline: none;
            box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.12);
        }
        /* TAMBAHKAN INI: Paksa warna background muncul */
        .bps-gradient {
            background: linear-gradient(135deg, #0f766e 0%, #0e7490 100%) !important;
        }
    </style>
</head>

<body class="font-sans antialiased bg-white text-slate-800">

    <div class="drawer lg:drawer-open min-h-screen">
        <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />

        {{-- ====== KONTEN UTAMA ====== --}}
        <div class="drawer-content flex flex-col min-h-screen">
            
            {{-- NAVBAR --}}
            <div class="sticky top-0 z-40">
                <nav class="px-4 sm:px-6 lg:px-8 h-16 text-white shadow-lg">
                    @include('layouts.navigation')
                </nav>
            </div>

            {{-- Header --}}
            @if (isset($header))
            <header class="bg-white border-b border-slate-200">
                <div class="py-4 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
            @endif

            {{-- Main Slot --}}
            <main class="flex-grow p-6 sm:p-8">
                <div class="max-w-full mx-auto space-y-6">
                    <div class="bg-white shadow-sm rounded-2xl p-6 soft-transition fade-in-up">
                        {{ $slot }}
                    </div>
                </div>
            </main>

            {{-- Footer --}}
            <footer class="py-4 text-center text-sm text-slate-500 border-t border-slate-100">
                Copyright © {{ date('Y') }} - All right reserved by BPS Kabupaten Tegal And STT Terpadu Nurul Fikri
            </footer>
        </div>

        {{-- ====== SIDEBAR AREA ====== --}}
        <div class="drawer-side z-50">
            <label for="my-drawer-2" aria-label="close sidebar" class="drawer-overlay"></label>
            
            {{-- PANGGIL FILE SIDEBAR DI SINI --}}
            @include('layouts.sidebar')
            
        </div>
    </div>

    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Helper styling scripts
            document.querySelectorAll('.drawer-side a, .drawer-side button').forEach(el => {
                // Ensure sidebar links have consistent base classes if needed
                el.classList.add('soft-transition');
            });

            document.querySelectorAll('main table').forEach(t => {
                t.classList.add('table-muted', 'w-full', 'overflow-hidden');
                const wrapper = document.createElement('div');
                wrapper.className = 'p-0 overflow-x-auto'; // Added overflow auto for responsiveness
                t.parentNode.insertBefore(wrapper, t);
                wrapper.appendChild(t);
            });
        });
    </script>
</body>
</html>