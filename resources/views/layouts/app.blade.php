<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="{ theme: localStorage.getItem('theme') || 'light' }" 
      x-bind:data-theme="theme">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Aplikasi Publikasi') }}</title>

    <!-- Font Modern -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Sidebar scrollbar */
        .sidebar-scroll::-webkit-scrollbar {
            width: 6px;
        }
        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.12);
            border-radius: 3px;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.22);
        }

        /* Table subtle dark style (not full black) */
        .table-muted {
            background: #f3f4f6; /* slate-100 */
            color: #0f172a;      /* slate-900 */
            border-radius: 0.5rem;
            overflow: hidden;
        }
        .table-muted thead {
            background: #e6edf8; /* very light blue tint */
            color: #0b2440;      /* deep blue text */
            font-weight: 600;
        }
        .table-muted tbody tr:nth-child(even) {
            background: #eef2f6; /* alternating soft rows */
        }

        /* Soft global transitions */
        .soft-transition {
            transition: all 220ms cubic-bezier(.2,.9,.2,1);
        }

        /* Fade-in for main content */
        .fade-in-up {
            opacity: 0;
            transform: translateY(8px);
            animation: fadeInUp 420ms forwards cubic-bezier(.2,.9,.2,1);
        }
        @keyframes fadeInUp {
            to { opacity: 1; transform: translateY(0); }
        }

        /* subtle focus ring for accessibility */
        .ring-focus:focus {
            outline: none;
            box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.12);
        }
    </style>
</head>

<body class="font-sans antialiased bg-white text-slate-800">

    <div class="drawer lg:drawer-open min-h-screen">
        <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />

        {{-- ====== KONTEN UTAMA ====== --}}
        <div class="drawer-content flex flex-col min-h-screen">
            
            {{-- NAVBAR: tetap dark/navy --}}
            <div class="sticky top-0 z-40">
                <nav class=" px-4 sm:px-6 lg:px-8 h-16
                             text-white
                            shadow-lg">
                    @include('layouts.navigation')
                </nav>
            </div>

            {{-- Header (bisa putih dengan subtle border) --}}
            @if (isset($header))
            <header class="bg-white border-b border-slate-200">
                <div class="py-4 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
            @endif

            {{-- Konten utama (background putih, card putih dengan shadow lembut) --}}
            <main class="flex-grow p-6 sm:p-8">
                <div class="max-w-full mx-auto space-y-6">
                    {{-- konten slot — beri wrapper card agar terkesan terangkat --}}
                    <div class="bg-white shadow-sm rounded-2xl p-6 soft-transition fade-in-up">
                        {{ $slot }}
                    </div>
                </div>
            </main>

            {{-- Footer ringan --}}
            <footer class="py-4 text-center text-sm text-slate-500 border-t border-slate-100">
                © {{ date('Y') }} — <span class="text-sky-600 font-semibold">{{ config('app.name', 'BPS Kabupaten Tegal') }}</span>. All rights reserved.
            </footer>
        </div>

        {{-- ====== SIDEBAR (BLUE DARK) ====== --}}
        <div class="drawer-side">
            <label for="my-drawer-2" aria-label="close sidebar" class="drawer-overlay"></label>
            
            <aside class="w-64 min-h-full
                          bg-gradient-to-b from-sky-900 via-slate-900 to-slate-800
                          text-slate-100 shadow-2xl rounded-r-2xl overflow-hidden soft-transition">
                
                
                   

                    {{-- keep original sidebar content; styling for items is done via classes in sidebar blade --}}
                    @include('layouts.sidebar')

                   
                </nav>
            </aside>
        </div>
    </div>

    @stack('scripts')

    {{-- Enhance sidebar items: add small hover/focus effects without changing markup --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // add classes to links inside sidebar for consistent look
            document.querySelectorAll('.drawer-side a, .drawer-side button').forEach(el => {
                el.classList.add('block', 'px-3', 'py-2', 'rounded-lg', 'soft-transition', 'ring-focus');
                // hover: subtle light bg
                el.addEventListener('mouseenter', () => el.classList.add('bg-white/6', 'translate-x-1'));
                el.addEventListener('mouseleave', () => el.classList.remove('bg-white/6', 'translate-x-1'));
            });

            // style tables inside content area to be "slightly dark" as requested
            document.querySelectorAll('main table').forEach(t => {
                t.classList.add('table-muted', 'w-full', 'overflow-hidden');
                // add spacing for table container
                const wrapper = document.createElement('div');
                wrapper.className = 'p-0';
                t.parentNode.insertBefore(wrapper, t);
                wrapper.appendChild(t);
            });
        });
    </script>
</body>
</html>
