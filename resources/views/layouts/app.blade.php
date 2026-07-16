<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'MediLabs')</title>

    {{-- Legacy public styles --}}
    <link rel="stylesheet" href="{{ asset('assets/css/medilabs.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/services.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/reservation.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">

    {{-- Page-specific Vite styles --}}
    @stack('styles')
</head>

<body class="@yield('body-class')">
    <div class="app-shell">
        <x-app-navbar />

        <main class="page-content">
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