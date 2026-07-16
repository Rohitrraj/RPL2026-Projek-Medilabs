<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'MediLabs | Reservasi Laboratorium Klinik')</title>

    <link
        rel="icon"
        type="image/png"
        href="{{ asset('assets/images/logo.png') }}"
    >
    <link
        rel="apple-touch-icon"
        href="{{ asset('assets/images/logo.png') }}"
    >
    <meta name="theme-color" content="#0284c7">

    {{-- Public/patient design system. Selector dibatasi oleh .ml-public. --}}
    @vite([
        'resources/css/public.css',
        'resources/js/public.js',
    ])

    {{-- Page-specific Vite assets, termasuk halaman Auth. --}}
    @stack('styles')
</head>

<body class="@yield('body-class', 'ml-public')">
    <div class="app-shell">
        <x-app-navbar />

        <main id="main-content" class="page-content">
            @if (trim($__env->yieldContent('suppress-global-flash')) !== 'true')
                <x-flash-message />
            @endif

            @yield('content')
        </main>

        <x-app-footer />
    </div>

    @stack('scripts')
</body>
</html>
