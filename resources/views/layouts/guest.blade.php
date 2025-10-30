<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SIPINTAR BPS Kab. Tegal') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ========== ANIMASI DAN EFEK HALUS ========== */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes glowPulse {
            0%, 100% { box-shadow: 0 0 15px rgba(59, 130, 246, 0.2); }
            50% { box-shadow: 0 0 25px rgba(59, 130, 246, 0.5); }
        }

        /* Background gradien lembut biru muda */
        body {
            background: linear-gradient(135deg, #93c5fd 0%, #60a5fa 50%, #3b82f6 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }

        /* Kartu login */
        .login-card {
            animation: fadeIn 0.8s ease-out;
            background: white;
            border-radius: 1.2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            overflow: hidden;
            width: 100%;
            max-width: 420px;
            padding: 2rem 2.5rem;
            position: relative;
        }

        /* Logo */
        .login-logo {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin-bottom: 1.5rem;
        }

        .login-logo img {
            width: 300px;
            height: 250px;
            margin-bottom: 0.5rem;
            transition: transform 0.3s ease;
        }

        .login-logo img:hover {
            transform: scale(1.1) rotate(3deg);
        }

        /* Judul dan subjudul */
        .login-title {
            font-weight: 700;
            font-size: 1.75rem;
            background: linear-gradient(90deg, #2563eb, #3b82f6, #60a5fa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-align: center;

        }

        .login-subtitle {
            color: #000000ff;
            text-align: center;
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
        }

        /* Form */
        .form-wrapper {
            animation: fadeIn 1s ease-out 0.2s;
            animation-fill-mode: forwards;
        }

        /* Tombol utama */
        .btn-login {
            background: linear-gradient(90deg, #3b82f6, #60a5fa);
            color: white;
            font-weight: 600;
            border: none;
            border-radius: 0.75rem;
            padding: 0.75rem;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: linear-gradient(90deg, #2563eb, #3b82f6);
            transform: translateY(-2px);
            animation: glowPulse 1.5s infinite;
        }

        /* Responsif kecil */
        @media (max-width: 640px) {
            .login-card {
                padding: 1.5rem;
                max-width: 90%;
            }
        }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="login-logo">
            <img src="{{ asset('images/logo_sipintar.png') }}" alt="Logo BPS">
          
            <p class="login-subtitle">Sistem Informasi Terintegrasi dan Acurat <br> BPS Kab Tegal </p>
           
        </div>

        <div class="form-wrapper">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
