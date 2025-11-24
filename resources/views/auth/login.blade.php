<x-guest-layout>

<style>
    body {
        font-family: 'Inter', sans-serif;
        background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 50%, #2563eb 100%);
        min-height: 100vh;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .login-wrapper {
        width: 100%;
        max-width: 1200px;
        background: #ffffff;
        border-radius: 22px;
        overflow: hidden;
        box-shadow: 0 25px 35px rgba(0,0,0,0.15);
        display: flex;
        min-height: 650px;
    }

    /* LEFT SIDE */
    .login-brand {
        flex: 1;
        background: linear-gradient(135deg, #0f766e 0%, #0e7490 100%);
        color: #fff;
        padding: 3rem 2.5rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        position: relative;
        text-align: center;
    }

    .login-brand img {
        width: 300px;
        background: #fff;
     
        border-radius: 300px;
        margin-bottom: 20px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.15);
    }

    .login-brand::before {
        content: "";
        position: absolute;
        inset: 0;
        opacity: 0.07;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none'%3E%3Cg fill='%23ffffff'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zM36 4V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    /* RIGHT SIDE */
    .login-form {
        flex: 1;
        padding: 3rem 3rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .btn-bps {
        background: #0e7490;
        color: #fff;
        font-weight: bold;
        border-radius: 12px;
        padding: 12px;
        transition: .2s;
    }

    .btn-bps:hover {
        background: #155e75;
        transform: translateY(-2px);
        box-shadow: 0 5px 12px rgba(0,0,0,0.15);
    }

    /* Responsive */
    @media (max-width: 900px) {
        .login-wrapper {
            flex-direction: column;
            max-width: 95%;
        }

        .login-brand {
            padding: 2rem;
        }

        .login-form {
            padding: 2rem;
        }
    }
    .login-brand {
    position: relative;
}

.login-brand::before {
    content: "";
    position: absolute;
    inset: 0;
    opacity: 0.07;
    z-index: 1;
}

.login-brand * {
    position: relative;
    z-index: 2;
}

</style>

<div class="login-wrapper">

    {{-- LEFT SIDE --}}
    <div class="login-brand">
            <img src="{{ asset('images/guci.png') }}" alt="Logo BPS">

        <h1 class="text-4xl font-bold">GUCI</h1>
        <p class="text-teal-100 tracking-wide mt-2">
            Guides for Creating Publications
        </p>

        <p class="text-white/80 mt-8 text-sm">Pegawai BPS kab tegal? Masuk via SSO.</p>

        <a href="https://sso.bps.go.id"
           class="w-full max-w-xs btn bg-white text-teal-700 hover:bg-gray-100 border-0 mt-2 font-bold py-2 rounded-lg shadow">
           Login via SSO BPS
        </a>

        <div class="mt-auto pt-10 text-xs text-teal-200/70">
            &copy; {{ date('Y') }} BPS Kabupaten Tegal
        </div>
    </div>

    {{-- RIGHT SIDE --}}
    <div class="login-form">
        <h2 class="text-3xl font-bold text-gray-800">Selamat Datang</h2>
        <p class="text-gray-500 mb-6">Silakan masuk untuk melanjutkan.</p>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- EMAIL --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Username Tamu/Admin</label>
                <input id="name" type="text" name="name" required
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-teal-500 focus:border-teal-500 text-black"
                    placeholder="username Anda" autofocus>
                   
            </div>

            {{-- PASSWORD --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-teal-500 focus:border-teal-500 text-black"
                    placeholder="Password Anda" autofocus>
         
            </div>

            {{-- REMEMBER --}}
            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center text-sm">
                    <input type="checkbox" class="rounded border-gray-300 text-teal-600 focus:ring-teal-500" name="remember">
                    <span class="ml-2 text-gray-600">Ingat saya</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-teal-600 hover:underline">
                        Lupa password?
                    </a>
                @endif
            </div>

            <button type="submit" class="btn-bps w-full text-lg flex items-center justify-center gap-2">
                Masuk
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </form>

        <div class="mt-8 text-center">
            <p class="text-sm text-gray-500 text-center">
                Lupa akun tamu? <br>
                coba hubungi admin IT BPS Kabupaten Tegal. 
              
            </p>
        </div>
    </div>

</div>

</x-guest-layout>
