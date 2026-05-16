<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'MediLabs Admin')</title>
    <link rel="stylesheet" href="{{ asset('assets/css/medilabs.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin-75.css') }}">
</head>

<body>
    <div class="admin-shell admin-75-shell">
        <header class="admin-topbar admin-75-topbar">
            <a class="admin-topbar-brand" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('assets/images/logo.png') }}" alt="MediLabs logo">
                <span>MediLabs Admin Panel</span>
            </a>

            <div class="admin-topbar-actions">
                <span>{{ auth()->user()->name ?? 'Admin Lab' }}</span>
                <form class="admin-logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </div>
        </header>

        <div class="admin-layout admin-75-layout">
            <aside class="admin-sidebar admin-75-sidebar">
                <div class="admin-logo-card">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="MediLabs logo">
                    <strong>MediLabs</strong>
                    <small>Laboratory Admin</small>
                </div>

                <p>Menu Sidebar</p>
                <nav class="admin-nav" aria-label="Navigasi admin">
                    <a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
                    <a class="{{ request()->routeIs('admin.reservations.status') ? 'active' : '' }}" href="{{ route('admin.reservations.status') }}">Cek Status</a>
                    <a class="{{ request()->routeIs('admin.reservations.manage') || request()->routeIs('admin.reservations.show') ? 'active' : '' }}" href="{{ route('admin.reservations.manage') }}">Kelola Reservasi</a>
                    <a href="{{ route('home') }}">Kembali ke Website</a>
                </nav>
            </aside>

            <main class="admin-page admin-75-page">
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
