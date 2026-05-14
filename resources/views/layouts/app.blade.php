<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'MediLabs')</title>
    <link rel="stylesheet" href="{{ asset('assets/css/medilabs.css') }}">
</head>
<body>
    <div class="app-shell">
        <header class="site-header">
            <a class="brand" href="{{ route('home') }}" aria-label="MediLabs">
                <img src="{{ asset('assets/images/logo.svg') }}" alt="MediLabs logo">
                <span>
                    <strong>MediLabs</strong>
                    <small>Sistem Reservasi dan Pendaftaran Laboratorium Klinik</small>
                </span>
            </a>

            <nav class="main-nav" aria-label="Navigasi utama">
                <a class="{{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a>
                <a class="{{ request()->routeIs('services.show') ? 'active' : '' }}" href="{{ route('services.show') }}">Layanan</a>
                <a class="{{ request()->routeIs('reservations.create') ? 'active' : '' }}" href="{{ route('reservations.create') }}">Reservasi</a>
                <a class="{{ request()->routeIs('reservations.result') ? 'active' : '' }}" href="{{ route('reservations.result') }}">Cek Status</a>
            </nav>
        </header>

        <main class="page-content">
            @yield('content')
        </main>

        <footer class="site-footer">
            2026 MediLabs. Semua hak dilindungi.
        </footer>
    </div>

    <script src="{{ asset('assets/js/medilabs.js') }}"></script>
</body>
</html>
