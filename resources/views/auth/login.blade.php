<x-guest-layout>
    {{-- CSS Kustom untuk Layout Split --}}
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
        
        .login-wrapper {
            display: flex;
            width: 100%;
            max-width: 1000px; /* Lebar maksimal agar proporsional */
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            min-height: 600px;
        }

        /* BAGIAN KIRI: SSO & BRANDING */
        .login-brand-section {
            flex: 1;
            background: linear-gradient(135deg, #0f766e 0%, #0e7490 100%); /* Warna Teal BPS */
            color: white;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
        }

        /* BAGIAN KANAN: FORM MANUAL */
        .login-form-section {
            flex: 1;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background-color: white;
        }

        .btn-bps {
            background-color: #0f766e;
            color: white;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-bps:hover {
            background-color: #115e59;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        /* Responsif Mobile */
        @media (max-width: 768px) {
            .login-wrapper { flex-direction: column; min-height: auto; margin: 1rem; }
            .login-brand-section { padding: 2rem; }
            .login-form-section { padding: 2rem; }
        }
    </style>

    <div class="flex items-center justify-center min-h-screen p-4">
        
        <div class="login-wrapper">
            
            {{-- 1. BAGIAN KIRI: SSO --}}
            <div class="login-brand-section">
                <div class="mb-6 bg-white p-4 rounded-full shadow-lg w-32 h-32 flex items-center justify-center">
                    {{-- Logo --}}
                    <img src="{{ asset('images/guci.png') }}" alt="Logo BPS" class="w-24 h-auto" 
                         onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/thumb/2/28/Badan_Pusat_Statistik.svg/1200px-Badan_Pusat_Statistik.svg.png'">
                </div>
                
                <h1 class="text-4xl font-bold mb-2 tracking-tight">GUCI</h1>
                <p class="text-cyan-100 font-medium mb-8 text-sm tracking-wide uppercase">Guides For Creating Publications</p>
                
                <div class="w-full max-w-xs">
                    <p class="text-sm text-white/90 mb-3 font-medium">Pegawai BPS? Masuk via SSO</p>
                    {{-- Tombol SSO yang mengarah ke route yang sudah kita buat --}}
                    <a href="{{ route('sso.login') }}" class="btn w-full bg-white text-teal-700 hover:bg-gray-100 border-0 shadow-md flex items-center justify-center gap-2 rounded-lg font-bold h-12">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Login SSO BPS
                    </a>
                </div>

                <div class="mt-auto pt-10 text-xs text-cyan-200/60">
                    &copy; {{ date('Y') }} BPS Kabupaten Tegal
                </div>
            </div>

            {{-- 2. BAGIAN KANAN: FORM MANUAL --}}
            <div class="login-form-section">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800">Selamat Datang!</h2>
                    <p class="text-gray-500 text-sm mt-1">Silakan masuk untuk melanjutkan.</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Input Username/Email (Sesuai kode Anda: name) -->
                    <div class="mb-5">
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Username Tamu/Admin</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                            </div>
                            {{-- Saya gunakan input 'name' sesuai permintaan Anda --}}
                            <input id="name" type="text" name="name" :value="old('name')" required autofocus 
                                class="pl-10 w-full border-gray-300 rounded-lg shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50 py-3" 
                                placeholder="username Anda">
                        </div>
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mb-5">
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input id="password" type="password" name="password" required autocomplete="current-password"
                                class="pl-10 w-full border-gray-300 rounded-lg shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50 py-3" 
                                placeholder="••••••••">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between mb-8">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-teal-600 shadow-sm focus:ring-teal-500" name="remember">
                            <span class="ml-2 text-sm text-gray-600">{{ __('Ingat saya') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-sm text-teal-600 hover:text-teal-800 hover:underline font-semibold" href="{{ route('password.request') }}">
                                {{ __('Lupa password?') }}
                            </a>
                        @endif
                    </div>

                    <!-- Tombol Login -->
                    <button type="submit" class="btn btn-bps w-full py-3 rounded-lg text-white font-bold text-lg shadow-md flex justify-center items-center gap-2 h-12 uppercase tracking-wide">
                        Masuk
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </form>

                {{-- Info Tambahan --}}
                <div class="mt-8 flex items-center justify-center">
                    <div class="border-t border-gray-200 w-full"></div>
                    <span class="px-3 text-gray-400 text-xs uppercase font-semibold bg-white">Info</span>
                    <div class="border-t border-gray-200 w-full"></div>
                </div>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-500">Lupa akun tamu? <br> Silakan hubungi <span class="text-teal-600 font-bold">Admin IT BPS Kab. Tegal</span>.</p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>