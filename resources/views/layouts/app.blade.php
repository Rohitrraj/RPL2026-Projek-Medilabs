<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'MediLabs')</title>

    {{-- Legacy public styles: dipertahankan selama migrasi halaman pasien. --}}
    <link rel="stylesheet" href="{{ asset('assets/css/medilabs.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/services.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/reservation.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">

    {{-- Public/patient shell design system. Selector dibatasi oleh .ml-public. --}}
    @vite([
        'resources/css/public.css',
        'resources/js/public.js',
    ])

    {{-- Page-specific Vite styles, termasuk Auth. --}}
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

    <script src="{{ asset('assets/js/medilabs.js') }}"></script>

    @stack('scripts')
</body>
</html>
