{{-- sidebar.blade.php --}}
<a href="{{ route('dashboard') }}"
    class="flex items-center gap-3 px-6 py-5 border-b border-gray-200 hover:bg-blue-50 transition-all duration-300">
    <img src="{{ asset('images/logo_bps.png') }}" class="h-10 w-auto" alt="Logo BPS" />
    <div>
        <h1 class="font-bold text-base text-black">Aplikasi Publikasi</h1>
        <p class="text-xs text-gray-700">BPS Kabupaten Tegal</p>
    </div>
</a>

<ul class="menu flex-1 px-4 py-4 text-sm space-y-1">

    {{-- Dashboard --}}
    <li>
        <a href="{{ route('dashboard') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-all duration-300 group
                  {{ request()->routeIs('dashboard') 
                      ? 'bg-blue-600 text-white shadow-md' 
                      : 'text-gray-700 hover:bg-blue-100 hover:text-blue-700' }}">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5 opacity-80 transform transition-transform duration-300 group-hover:scale-125 group-hover:rotate-6"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
            <summary
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg cursor-pointer text-gray-800 hover:bg-blue-50 hover:text-blue-700 transition-all duration-300">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 opacity-75 transform transition-transform duration-300 group-hover:rotate-12"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4M4 7s0 0 0 0l8 5 8-5" />
                </svg>
                <span class="font-medium">Master Data</span>
            </summary>
            <ul class="ml-4 mt-1 space-y-1 border-l border-blue-100 pl-3">
                <li>
                    <a href="{{ route('publications.index') }}"
                        class="block rounded-lg px-3 py-1.5 transition-all duration-300 group
                              {{ request()->routeIs('publications.*') 
                                  ? 'bg-blue-100 text-blue-700 font-medium' 
                                  : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                        <span class="inline-flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 opacity-80 transform transition-transform duration-300 group-hover:scale-125 group-hover:-rotate-6"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-2.21 0-4 1.79-4 4v6h8v-6c0-2.21-1.79-4-4-4z" />
                            </svg>
                            Master Publikasi
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('users.index') }}"
                        class="block rounded-lg px-3 py-1.5 transition-all duration-300 group
                              {{ request()->routeIs('users.*') 
                                  ? 'bg-blue-100 text-blue-700 font-medium' 
                                  : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                        <span class="inline-flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 opacity-80 transform transition-transform duration-300 group-hover:scale-125 group-hover:rotate-6"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m0-4a4 4 0 110-8 4 4 0 010 8zm8 0a4 4 0 100-8 4 4 0 000 8z" />
                            </svg>
                            Manajemen Pegawai
                        </span>
                    </a>
                </li>
            </ul>
        </details>
    </li>
    @endrole

    {{-- Pengajuan --}}
    @role('Penyusun|Pemeriksa|Pimpinan')
    <li class="mt-3">
        <details open class="group">
            <summary
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg cursor-pointer text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-all duration-300">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 opacity-75 transform transition-transform duration-300 group-hover:rotate-12"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4M4 7s0 0 0 0l8 5 8-5" />
                </svg>
                <span class="font-medium">Pengajuan</span>
            </summary>
            <ul class="ml-4 mt-1 space-y-1 border-l border-blue-100 pl-3">
                <li>
                    <a href="{{ route('sprp.index') }}"
                        class="block rounded-lg px-3 py-1.5 transition-all duration-300 group
                              {{ request()->routeIs('sprp.*') 
                                  ? 'bg-blue-100 text-blue-700 font-medium' 
                                  : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                        <span class="inline-flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 transform transition-transform duration-300 group-hover:scale-125 group-hover:-rotate-6"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m2 8H7a2 2 0 01-2-2V6a2 2 0 012-2h7l5 5v9a2 2 0 01-2 2z" />
                            </svg>
                            SPRP
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('pengajuan_publikasi.index') }}"
                        class="block rounded-lg px-3 py-1.5 transition-all duration-300 group
                              {{ request()->routeIs('pengajuan_publikasi.*') 
                                  ? 'bg-blue-100 text-blue-700 font-medium' 
                                  : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                        <span class="inline-flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 transform transition-transform duration-300 group-hover:scale-125 group-hover:rotate-6"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Publikasi
                        </span>
                    </a>
                </li>
            </ul>
        </details>
    </li>
    @endrole

    {{-- Panduan --}}
    <li>
        <a href="{{ route('panduan.index') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-all duration-300 group
                  {{ request()->routeIs('panduan.index') 
                      ? 'bg-blue-600 text-white shadow-md' 
                      : 'text-gray-800 hover:bg-blue-100 hover:text-blue-700' }}">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5 opacity-80 transform transition-transform duration-300 group-hover:scale-125 group-hover:-rotate-6"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6 1a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Panduan
        </a>
    </li>

    {{-- Cetak SPNRS --}}
    <li>
        <a href="{{ route('spnsr.index') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-all duration-300 group
                  {{ request()->routeIs('spnsr.create') 
                      ? 'bg-blue-600 text-white shadow-md' 
                      : 'text-gray-800 hover:bg-blue-100 hover:text-blue-700' }}">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5 opacity-80 transform transition-transform duration-300 group-hover:scale-125 group-hover:rotate-6"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M6 8h12M6 12h12m-9 4h9" />
            </svg>
            Cetak SPNRS
        </a>
    </li>
</ul>

<div class="p-5 border-t border-gray-200 bg-slate-300 from-blue-50 to-white">
    <a href="{{ route('profile.edit') }}"
        class="flex items-center gap-3 rounded-xl p-2 hover:bg-blue-100 transition-all duration-300 group">
        <div
            class="bg-blue-200 text-blue-800 rounded-full w-10 h-10 flex items-center justify-center font-semibold shadow-inner group-hover:scale-110 transform transition-transform duration-300">
            <span>{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
        </div>
        <div class="leading-tight">
            <p class="font-semibold text-sm text-gray-800">{{ Auth::user()->name }}</p>
            <p class="text-xs text-gray-500">{{ Auth::user()->getRoleNames()->first() }}</p>
        </div>
    </a>
</div>
