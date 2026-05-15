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
                <a class="{{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a>
                <a class="{{ request()->routeIs('services.*') ? 'active' : '' }}" href="{{ route('services.index') }}">Layanan</a>
                <a class="{{ request()->routeIs('patients.*') || request()->routeIs('reservations.create') ? 'active' : '' }}" href="{{ route('reservations.create') }}">Reservasi</a>
                <a class="{{ request()->routeIs('reservations.status') || request()->routeIs('reservations.history') ? 'active' : '' }}" href="{{ route('reservations.status') }}">Cek Status</a>

                @auth
                    <a class="{{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.show') }}">Profil</a>
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}">Admin</a>
                    @endif
                    <form class="nav-logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                @else
                    <a class="{{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">Login</a>
                @endauth
            </nav>
        </header>

        <main class="page-content">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">
                    <strong>Data belum valid.</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="site-footer">
            2026 MediLabs. Semua hak dilindungi.
        </footer>
    </div>

    <script src="{{ asset('assets/js/medilabs.js') }}"></script>
</body>
</html>
