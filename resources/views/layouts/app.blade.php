<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'MediLabs')</title>
    <link rel="stylesheet" href="{{ asset('assets/css/medilabs.css') }}">
</head>
<body>
    @php
        /*
         | Ganti logo kecil MediLabs dari sini.
         | Contoh:
         | $brandLogo = 'assets/images/logo-baru.png';
         */
        $brandLogo = 'assets/images/logo.png';
    @endphp

    <div class="app-shell">
        <header class="site-header">
            <a class="brand" href="{{ route('home') }}" aria-label="MediLabs">
                <img src="{{ asset($brandLogo) }}" alt="MediLabs logo">
                <span>
                    <strong>MediLabs</strong>
                    <small>Sistem Reservasi dan Pendaftaran Laboratorium Klinik</small>
                </span>
            </a>

            <nav class="main-nav" aria-label="Navigasi utama">
                <a class="{{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                    Beranda
                </a>

                <a class="{{ request()->routeIs('services.*') ? 'active' : '' }}" href="{{ route('services.index') }}">
                    Layanan
                </a>

                <a class="{{ request()->routeIs('patients.create') || request()->routeIs('reservations.create') ? 'active' : '' }}" href="{{ route('patients.create') }}">
                    Reservasi
                </a>

                <a class="{{ request()->routeIs('reservations.status') || request()->routeIs('reservations.history') ? 'active' : '' }}" href="{{ route('reservations.status') }}">
                    Cek Status
                </a>
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