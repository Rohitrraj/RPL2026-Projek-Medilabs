<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'MediLabs Admin')</title>
    <link rel="stylesheet" href="{{ asset('assets/css/medilabs.css') }}">
</head>
<body>
    <div class="admin-shell">
        <header class="admin-topbar">
            <a class="admin-topbar-brand" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('assets/images/logo.png') }}" alt="MediLabs logo">
                <span>MediLabs Admin Panel</span>
            </a>
            <a href="{{ route('home') }}">Logout</a>
        </header>

        <div class="admin-layout">
            <aside class="admin-sidebar">
                <div class="admin-logo-card">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="MediLabs logo">
                </div>

                <p>Menu Sidebar</p>
                <nav class="admin-nav" aria-label="Navigasi admin">
                    <a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
                    <a class="{{ request()->routeIs('admin.reservations.status') ? 'active' : '' }}" href="{{ route('admin.reservations.status') }}">Cek Status</a>
                    <a class="{{ request()->routeIs('admin.reservations.manage') ? 'active' : '' }}" href="{{ route('admin.reservations.manage') }}">Kelola Reservasi</a>
                    <a href="{{ route('reservations.history') }}">Lihat Semua</a>
                </nav>

                <a class="admin-sidebar-logout" href="{{ route('home') }}">Logout</a>
            </aside>

            <main class="admin-page">
                @yield('content')
            </main>
        </div>

        <footer class="admin-footer">2026 MediLabs. Semua hak dilindungi.</footer>
    </div>
</body>
</html>
