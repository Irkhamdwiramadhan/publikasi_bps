<aside class="bps-gradient text-white w-64 min-h-screen h-full shadow-2xl flex flex-col justify-between transition-all duration-500 sidebar-scroll overflow-y-auto">

   <a href="{{ route('dashboard') }}"
        class="flex items-center gap-4 px-6 py-6 border-b border-green-200/70 
          hover:bg-green-300/40 hover:shadow-lg hover:scale-[1.02]
          transition-all duration-300 ease-out">

        <!-- Logo lebih besar dengan efek hover -->
        <div class="relative">
            <img src="{{ asset('images/guci.png') }}"
                class="h-20 transition-transform duration-300 hover:rotate-3 hover:scale-110"
                alt="Logo BPS" />
        </div>

        <div>
            <h1 class="font-extrabold text-2xl bg-gradient-to-r from-white via-white  
               bg-clip-text text-transparent tracking-wide">
                GUCI
            </h1>

            <p class="text-sm font-medium bg-gradient-to-r from-white to-white 
                  bg-clip-text text-transparent animate-pulse">
                Guides For Creating Publications
            </p>
        </div>
    </a>
 {{-- HEADER: Logo & Judul --}}
  


    <ul class="menu flex-1 px-4 py-4 text-sm space-y-1">

        {{-- Dashboard --}}
        @hasanyrole('Admin|Pemeriksa|Pimpinan|Tamu|Penyusun')
        <li>
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-all duration-300 group
              {{ request()->routeIs('dashboard') 
                  ? 'bg-white/20 text-white shadow-lg font-bold border border-white/10' 
                  : 'text-teal-50 hover:bg-white/10 hover:text-white hover:translate-x-1' }}">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 opacity-90 transform transition-transform duration-300 group-hover:scale-110"
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
                <summary class="flex items-center gap-3 px-3 py-2.5 rounded-lg cursor-pointer text-teal-50 hover:bg-white/10 hover:text-white transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 opacity-80 transform transition-transform duration-300 group-hover:rotate-12"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4M4 7s0 0 0 0l8 5 8-5" />
                    </svg>
                    <span class="font-medium">Master Data</span>
                </summary>
                
                <ul class="ml-4 mt-1 space-y-1 border-l border-white/20 pl-3">
                    <li>
                        <a href="{{ route('catalog.create') }}"
                            class="block rounded-lg px-3 py-1.5 transition-all duration-300 group
                                  {{ request()->routeIs('publications.*') 
                                      ? 'bg-white/20 text-white font-bold shadow-sm' 
                                      : 'text-teal-100 hover:text-white hover:bg-white/5 hover:translate-x-1' }}">
                            <span class="inline-flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                                      ? 'bg-white/20 text-white font-bold shadow-sm' 
                                      : 'text-teal-100 hover:text-white hover:bg-white/5 hover:translate-x-1' }}">
                            <span class="inline-flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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

        {{-- Pengajuan --}}
        @hasanyrole('Penyusun|Pemeriksa|Pimpinan|Tamu')
        <li class="mt-3">
            <details open class="group">
                <summary class="flex items-center gap-3 px-3 py-2.5 rounded-lg cursor-pointer text-teal-50 hover:bg-white/10 hover:text-white transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 opacity-80 transform transition-transform duration-300 group-hover:rotate-12"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4M4 7s0 0 0 0l8 5 8-5" />
                    </svg>
                    <span class="font-medium">Pengajuan</span>
                </summary>
                
                <ul class="ml-4 mt-1 space-y-1 border-l border-white/20 pl-3">
                    <li>
                        <a href="{{ route('sprp.create') }}"
                            class="block rounded-lg px-3 py-1.5 transition-all duration-300 group
                           {{ request()->routeIs('sprp.*') 
                               ? 'bg-white/20 text-white font-bold shadow-sm' 
                               : 'text-teal-100 hover:text-white hover:bg-white/5 hover:translate-x-1' }}">
                            <span class="inline-flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m2 8H7a2 2 0 01-2-2V6a2 2 0 012-2h7l5 5v9a2 2 0 01-2 2z" />
                                </svg>
                                SPRP
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pengajuan_publikasi.index') }}"
                            class="block rounded-lg px-3 py-1.5 transition-all duration-300 group
                           {{ request()->routeIs('pengajuan_publikasi.*') 
                               ? 'bg-white/20 text-white font-bold shadow-sm' 
                               : 'text-teal-100 hover:text-white hover:bg-white/5 hover:translate-x-1' }}">
                            <span class="inline-flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Publikasi
                            </span>
                        </a>
                    </li>

                    {{-- MENU BRS --}}
                    <li>
                        <a href="{{ route('brs.index') }}"
                            class="block rounded-lg px-3 py-1.5 transition-all duration-300 group
                           {{ request()->routeIs('brs.*') 
                               ? 'bg-white/20 text-white font-bold shadow-sm' 
                               : 'text-teal-100 hover:text-white hover:bg-white/5 hover:translate-x-1' }}">
                            <span class="inline-flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                BRS
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
                          ? 'bg-white/20 text-white shadow-lg font-bold border border-white/10' 
                          : 'text-teal-50 hover:bg-white/10 hover:text-white hover:translate-x-1' }}">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 opacity-80 transform transition-transform duration-300 group-hover:scale-110"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 8h12M6 12h12m-9 4h9" />
                </svg>
                Surat SPNRS
            </a>
        </li>

        {{-- Logo (Link Statis) --}}
        <li>
            <a href="#" target="_blank"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-all duration-300 group
              text-teal-50 hover:bg-white/10 hover:text-white hover:translate-x-1">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 opacity-80 transform transition-transform duration-300 group-hover:scale-110"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Logo
            </a>
        </li>

        {{-- Panduan --}}
        <li>
            <a href="{{ route('panduan.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-all duration-300 group
                      {{ request()->routeIs('panduan.index') 
                          ? 'bg-white/20 text-white shadow-lg font-bold border border-white/10' 
                          : 'text-teal-50 hover:bg-white/10 hover:text-white hover:translate-x-1' }}">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 opacity-80 transform transition-transform duration-300 group-hover:scale-110"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6 1a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Panduan
            </a>
        </li>

    </ul>

    <div class="p-4 border-t border-white/10 bg-black/20 backdrop-blur-sm">
        <a href="{{ route('profile.edit') }}"
            class="flex items-center gap-3 rounded-xl p-2 hover:bg-white/10 transition-all duration-300 group">

            {{-- Inisial Nama User --}}
            <div
                class="bg-white text-teal-700 rounded-full w-10 h-10 flex items-center justify-center font-bold shadow-md group-hover:scale-110 transform transition-transform duration-300">
                <span>{{ Auth::check() ? strtoupper(substr(Auth::user()->name, 0, 1)) : 'T' }}</span>
            </div>

            {{-- Informasi Nama dan Role --}}
            <div class="leading-tight overflow-hidden">
                <p class="font-bold text-sm text-white truncate">{{ Auth::check() ? Auth::user()->name : 'Tamu' }}</p>
                <p class="text-xs text-teal-200 truncate opacity-80">
                    {{ Auth::check() ? Auth::user()->getRoleNames()->implode(', ') : 'Guest' }}
                </p>
            </div>
        </a>
    </div>

</aside>