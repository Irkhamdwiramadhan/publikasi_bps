{{-- Sidebar Modern Biru Muda --}}
<aside class="bg-gradient-to-b from-blue-400 via-blue-300 to-blue-100 text-gray-800 min-h-screen shadow-lg flex flex-col justify-between transition-all duration-500">

    <a href="{{ route('dashboard') }}"
        class="flex items-center gap-4 px-6 py-6 border-b border-blue-200/70 
          hover:bg-blue-300/40 hover:shadow-lg hover:scale-[1.02]
          transition-all duration-300 ease-out">

        <!-- Logo lebih besar dengan efek hover -->
        <div class="relative">
            <img src="{{ asset('images/logo_sipintar.png') }}"
                class="h-20 w-auto drop-shadow-md transition-transform duration-300 hover:rotate-3 hover:scale-110"
                alt="Logo BPS" />
        </div>

        <div>
            <h1 class="font-extrabold text-2xl bg-gradient-to-r from-white via-white  
               bg-clip-text text-transparent tracking-wide">
                SIPINTAR
            </h1>

            <p class="text-sm font-medium bg-gradient-to-r from-white to-white 
                  bg-clip-text text-transparent animate-pulse">
                BPS Kabupaten Tegal
            </p>
        </div>
    </a>


    <!-- Menu Utama -->
    <ul class="menu flex-1 px-4 py-4 text-sm space-y-1">

        {{-- Dashboard --}}
        @hasanyrole('Admin|Pemeriksa|Pimpinan')
        <li>
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-all duration-300 group
              {{ request()->routeIs('dashboard') 
                  ? 'bg-blue-600 text-white shadow-md' 
                  : 'text-gray-800 hover:bg-blue-200/60 hover:text-blue-800' }}">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 opacity-80 transform transition-transform duration-300 group-hover:scale-125 group-hover:rotate-6"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                Dashboard
            </a>
        </li>
        @endhasanyrole


        {{-- Master Data (Admin saja) --}}
        @hasrole('Admin')
        <li class="mt-3">
            <details open class="group">
                <summary
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg cursor-pointer text-gray-800 hover:bg-blue-200/60 hover:text-blue-800 transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 opacity-75 transform transition-transform duration-300 group-hover:rotate-12"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4M4 7s0 0 0 0l8 5 8-5" />
                    </svg>
                    <span class="font-medium">Master Data</span>
                </summary>
                <ul class="ml-4 mt-1 space-y-1 border-l border-blue-200 pl-3">
                    <li>
                        <a href="{{ route('publications.index') }}"
                            class="block rounded-lg px-3 py-1.5 transition-all duration-300 group
                                  {{ request()->routeIs('publications.*') 
                                      ? 'bg-blue-600 text-white font-medium' 
                                      : 'text-gray-800 hover:bg-blue-200/60 hover:text-blue-800' }}">
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
                                      ? 'bg-blue-600 text-white font-medium' 
                                      : 'text-gray-800 hover:bg-blue-200/60 hover:text-blue-800' }}">
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
        @endhasrole

        {{-- Pengajuan (bisa diakses oleh Penyusun, Pemeriksa, dan Pimpinan) --}}
        @hasanyrole('Penyusun|Pemeriksa|Pimpinan')
        <li class="mt-3">
            <details open class="group">
                <summary
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg cursor-pointer text-gray-800 hover:bg-blue-200/60 hover:text-blue-800 transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 opacity-75 transform transition-transform duration-300 group-hover:rotate-12"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4M4 7s0 0 0 0l8 5 8-5" />
                    </svg>
                    <span class="font-medium">Pengajuan</span>
                </summary>
                <ul class="ml-4 mt-1 space-y-1 border-l border-blue-200 pl-3">
                    <li>
                        <a href="{{ route('sprp.index') }}"
                            class="block rounded-lg px-3 py-1.5 transition-all duration-300 group
                                  {{ request()->routeIs('sprp.*') 
                                      ? 'bg-blue-600 text-white font-medium' 
                                      : 'text-gray-800 hover:bg-blue-200/60 hover:text-blue-800' }}">
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
                                      ? 'bg-blue-600 text-white font-medium' 
                                      : 'text-gray-800 hover:bg-blue-200/60 hover:text-blue-800' }}">
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
        @endhasanyrole

        {{-- Cetak SPNRS --}}
        <li>
            <a href="{{ route('spnsr.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-all duration-300 group
                      {{ request()->routeIs('spnsr.index') 
                          ? 'bg-blue-600 text-white shadow-md' 
                          : 'text-gray-800 hover:bg-blue-200/60 hover:text-blue-800' }}">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 opacity-80 transform transition-transform duration-300 group-hover:scale-125 group-hover:rotate-6"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 8h12M6 12h12m-9 4h9" />
                </svg>
                Cetak SPNRS
            </a>
        </li>
        {{-- Panduan --}}
        <li>
            <a href="{{ route('panduan.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-all duration-300 group
                      {{ request()->routeIs('panduan.index') 
                          ? 'bg-blue-600 text-white shadow-md' 
                          : 'text-gray-800 hover:bg-blue-200/60 hover:text-blue-800' }}">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 opacity-80 transform transition-transform duration-300 group-hover:scale-125 group-hover:-rotate-6"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6 1a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Panduan
            </a>
        </li>

    </ul>

    <!-- Footer User -->
    <div class="p-5 border-t border-blue-200 bg-blue-100/70">
    <a href="{{ route('profile.edit') }}"
        class="flex items-center gap-3 rounded-xl p-2 hover:bg-blue-200 transition-all duration-300 group">

        {{-- Inisial Nama User --}}
        <div
            class="bg-gradient-to-br from-blue-400 to-blue-600 text-white rounded-full w-10 h-10 flex items-center justify-center font-semibold shadow-inner group-hover:scale-110 transform transition-transform duration-300">
            <span>{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
        </div>

        {{-- Informasi Nama dan Role --}}
        <div class="leading-tight">
            <p class="font-semibold text-sm text-gray-800">{{ Auth::user()->name }}</p>

            {{-- Tampilkan semua role user, dipisahkan koma --}}
            <p class="text-xs text-gray-600">
                {{ Auth::user()->getRoleNames()->implode(', ') }}
            </p>
        </div>
    </a>
</div>

</aside>