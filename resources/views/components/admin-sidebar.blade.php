<aside class="admin-sidebar">
    <div class="admin-logo-card">
        <img src="{{ asset('assets/images/logo.png') }}" alt="MediLabs">
    </div>

    <p>Menu Sidebar</p>

    <nav class="admin-nav">
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            Dashboard
        </a>

        <a href="{{ route('admin.reservations.status') }}" class="{{ request()->routeIs('admin.reservations.status') ? 'active' : '' }}">
            Cek Status
        </a>

        <a href="{{ route('admin.reservations.manage') }}" class="{{ request()->routeIs('admin.reservations.manage') ? 'active' : '' }}">
            Kelola Reservasi
        </a>

        <a href="{{ route('admin.services.index') }}" class="{{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
            Kelola Layanan
        </a>
    </nav>

    <form action="{{ route('logout') }}" method="POST" class="admin-sidebar-logout-form admin-sidebar-logout">
        @csrf
        <button type="submit">Logout</button>
    </form>
</aside>