<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>publikasi</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gradient-to-br from-gray-50 via-white to-gray-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300">
    <div class="min-h-screen flex flex-col justify-center items-center px-4 sm:px-0">
        
        <!-- Logo / Branding -->
        <div class="mb-6 text-center">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-600 dark:text-gray-300 mx-auto" />
            </a>
            <h1 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mt-4 tracking-tight">
                {{ config('app.name', 'Laravel') }}
            </h1>
        </div>

        <!-- Card Container -->
        <div class="w-full sm:max-w-md bg-white/80 dark:bg-gray-800/80 backdrop-blur-md shadow-lg ring-1 ring-gray-200 dark:ring-gray-700 rounded-2xl p-8 transition-all duration-300 hover:shadow-xl">
            {{ $slot }}
        </div>

        <!-- Footer kecil -->
        <div class="mt-6 text-xs text-gray-500 dark:text-gray-400 text-center">
            &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }} — All rights reserved.
        </div>
    </div>
</body>
</html>
