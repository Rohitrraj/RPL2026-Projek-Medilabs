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
