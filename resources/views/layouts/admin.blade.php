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
            <form class="admin-logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit">Logout</button>
            </form>
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

                <form class="admin-sidebar-logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="admin-sidebar-logout" type="submit">Logout</button>
                </form>
            </aside>

            <main class="admin-page">
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
        </div>

        <footer class="admin-footer">2026 MediLabs. Semua hak dilindungi.</footer>
    </div>
</body>
</html>
