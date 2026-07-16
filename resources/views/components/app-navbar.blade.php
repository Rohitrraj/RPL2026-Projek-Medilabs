@php
    $brandLogo = 'assets/images/logo.png';
@endphp

<header class="site-header">
    <a class="brand" href="{{ route('home') }}" aria-label="MediLabs">
        <img src="{{ asset($brandLogo) }}" alt="MediLabs logo">
        <span>
            <strong>MediLabs</strong>
            <small>Sistem Reservasi dan Pendaftaran Laboratorium Klinik</small>
        </span>
    </a>

    <nav class="main-nav" aria-label="Navigasi utama">
        @auth
            @if (auth()->user()->role === 'admin')
                <a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    Dashboard
                </a>
                <a class="{{ request()->routeIs('admin.reservations.manage') ? 'active' : '' }}" href="{{ route('admin.reservations.manage') }}">
                    Kelola Reservasi
                </a>
                <a class="{{ request()->routeIs('admin.reservations.status') ? 'active' : '' }}" href="{{ route('admin.reservations.status') }}">
                    Cek Status
                </a>

                <form class="nav-logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            @else
                <a class="{{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a>
                <a class="{{ request()->routeIs('services.*') ? 'active' : '' }}" href="{{ route('services.index') }}">Layanan</a>
                <a class="{{ request()->routeIs('patients.*') || request()->routeIs('reservations.*') ? 'active' : '' }}" href="{{ route('reservations.create') }}">Reservasi</a>
                <a class="{{ request()->routeIs('reservations.status') || request()->routeIs('reservations.history') ? 'active' : '' }}" href="{{ route('reservations.status') }}">Cek Status</a>
                <a class="{{ request()->routeIs('profile.show') ? 'active' : '' }}" href="{{ route('profile.show') }}">Profile</a>

                <form class="nav-logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            @endif
        @else
            <a class="{{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a>
            <a class="{{ request()->routeIs('services.*') ? 'active' : '' }}" href="{{ route('services.index') }}">Layanan</a>
            <a class="{{ request()->routeIs('patients.*') || request()->routeIs('reservations.*') ? 'active' : '' }}" href="{{ route('reservations.create') }}">Reservasi</a>
            <a class="{{ request()->routeIs('reservations.status') || request()->routeIs('reservations.history') ? 'active' : '' }}" href="{{ route('reservations.status') }}">Cek Status</a>
            <a class="{{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}" > Masuk </a>
        @endauth
    </nav>
</header>