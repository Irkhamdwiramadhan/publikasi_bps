{{-- Sidebar Modern dan Elegan --}}
<div class="flex flex-col h-full bg-gradient-to-b from-white via-gray-50 to-gray-100 text-gray-800 shadow-md rounded-r-2xl overflow-hidden">

    <!-- Logo & Judul Aplikasi -->
    <a href="{{ route('dashboard') }}" 
       class="flex items-center gap-3 px-6 py-5 border-b border-gray-200 hover:bg-blue-50 transition-all duration-300">
        <img src="{{ asset('images/logo1.png') }}" class="h-10 w-auto" alt="Logo BPS" />
        <div>
            <h1 class="font-bold text-base text-gray-800">Aplikasi Publikasi</h1>
            <p class="text-xs text-gray-500">BPS Kabupaten Tegal</p>
        </div>
    </a>

    <!-- Menu Utama -->
    <ul class="menu flex-1 px-4 py-4 text-sm space-y-1">

        {{-- Dashboard --}}
        <li>
            <a href="{{ route('dashboard') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-all duration-300
                      {{ request()->routeIs('dashboard') 
                          ? 'bg-blue-600 text-white shadow-md' 
                          : 'text-gray-700 hover:bg-blue-100 hover:text-blue-700' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                Dashboard
            </a>
        </li>

        {{-- Master Data (Admin) --}}
        @role('Admin')
        <li class="mt-3">
            <details open class="group">
                <summary class="flex items-center gap-3 px-3 py-2.5 rounded-lg cursor-pointer 
                                text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4M4 7s0 0 0 0l8 5 8-5" />
                    </svg>
                    <span class="font-medium">Master Data</span>
                </summary>
                <ul class="ml-4 mt-1 space-y-1 border-l border-blue-100 pl-3">
                    <li>
                        <a href="{{ route('publications.index') }}"
                           class="block rounded-lg px-3 py-1.5 transition-all duration-300 
                                  {{ request()->routeIs('publications.*') 
                                      ? 'bg-blue-100 text-blue-700 font-medium' 
                                      : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' }}">
                            Master Publikasi
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('users.index') }}"
                           class="block rounded-lg px-3 py-1.5 transition-all duration-300 
                                  {{ request()->routeIs('users.*') 
                                      ? 'bg-blue-100 text-blue-700 font-medium' 
                                      : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' }}">
                            Manajemen Pegawai
                        </a>
                    </li>
                </ul>
            </details>
        </li>
        @endrole

        {{-- Pengajuan (Penyusun, Pemeriksa, Pimpinan) --}}
        @role('Penyusun|Pemeriksa|Pimpinan')
        <li class="mt-3">
            <details open class="group">
                <summary class="flex items-center gap-3 px-3 py-2.5 rounded-lg cursor-pointer 
                                text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4M4 7s0 0 0 0l8 5 8-5" />
                    </svg>
                    <span class="font-medium">Pengajuan</span>
                </summary>
                <ul class="ml-4 mt-1 space-y-1 border-l border-blue-100 pl-3">
                    <li>
                        <a href="{{ route('sprp.index') }}"
                           class="block rounded-lg px-3 py-1.5 transition-all duration-300 
                                  {{ request()->routeIs('sprp.*') 
                                      ? 'bg-blue-100 text-blue-700 font-medium' 
                                      : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' }}">
                            SPRP
                        </a>
                    </li>
                </ul>
                <ul class="ml-4 mt-1 space-y-1 border-l border-blue-100 pl-3">
                    <li>
                        <a href="{{ route('pengajuan_publikasi.index') }}"
                           class="block rounded-lg px-3 py-1.5 transition-all duration-300 
                                  {{ request()->routeIs('sprp.*') 
                                      ? 'bg-blue-100 text-blue-700 font-medium' 
                                      : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' }}">
                            Publikasi
                        </a>
                    </li>
                </ul>
            </details>
        </li>
        @endrole
        
    </ul>

    <!-- Profil Pengguna -->
    <div class="p-5 border-t border-gray-200 bg-gradient-to-r from-blue-50 to-white">
        <a href="{{ route('profile.edit') }}" 
           class="flex items-center gap-3 rounded-xl p-2 hover:bg-blue-100 transition-all duration-300">
            <div class="avatar placeholder">
                <div class="bg-blue-200 text-blue-800 rounded-full w-10 h-10 flex items-center justify-center font-semibold shadow-inner">
                    <span>{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                </div>
            </div>
            <div class="leading-tight">
                <p class="font-semibold text-sm text-gray-800">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500">{{ Auth::user()->getRoleNames()->first() }}</p>
            </div>
        </a>
    </div>
</div>
