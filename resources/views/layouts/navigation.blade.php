{{-- Navbar dengan desain modern dan theme switcher --}}
<nav class="sticky top-0 z-30 bg-white/80 backdrop-blur-md border-b border-gray-200">
    <div class="navbar max-w-7xl mx-auto px-4 md:px-6 lg:px-8 flex justify-between">

        <!-- Kiri: Toggle + Breadcrumb -->
        <div class="flex items-center gap-3 flex-1">
            <!-- Tombol Toggle Sidebar (Mobile) -->
            <label for="my-drawer-2" class="btn btn-ghost btn-circle lg:hidden hover:bg-gray-100 transition">
                <svg class="w-6 h-6 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" 
                     stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </label>

            <!-- Breadcrumbs -->
            <div class="hidden md:block">
                <div class="text-sm breadcrumbs text-gray-600">
                    <ul>
                        <li><a class="hover:text-blue-600 transition-colors">BPS Kab. Tegal</a></li>
                        <li><a class="font-medium text-gray-800">
                            {{ request()->segment(1) ? Str::ucfirst(request()->segment(1)) : 'Dashboard' }}
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Kanan: Tools -->
        <div class="flex items-center gap-2">

            

            <!-- Tombol Notifikasi -->
            <button class="btn btn-ghost btn-circle hover:bg-gray-100 transition">
                <div class="indicator">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700" fill="none" 
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span class="badge badge-xs badge-primary indicator-item"></span>
                </div>
            </button>

            <!-- Dropdown Profil -->
            <div class="dropdown dropdown-end">
                <label tabindex="0" class="btn btn-ghost btn-circle avatar hover:bg-gray-100 transition">
                    <div class="w-10 rounded-full bg-blue-100 flex items-center justify-center font-semibold text-blue-700">
                        <span>{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                </label>
                <ul tabindex="0" 
                    class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow-md bg-white rounded-xl border border-gray-100 w-52">
                    <li><a href="{{ route('profile.edit') }}" class="text-gray-700 hover:text-blue-600">Profil</a></li>
                    <li><hr class="my-1 border-gray-200"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}" 
                               class="text-gray-700 hover:text-red-600"
                               onclick="event.preventDefault(); this.closest('form').submit();">
                                Keluar
                            </a>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
