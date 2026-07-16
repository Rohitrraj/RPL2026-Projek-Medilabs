@props(['variant' => 'site'])

@php
    $isAdminFooter = $variant === 'admin';
    $isAuthRoute = request()->routeIs('login', 'register', 'password.*');
@endphp

@if ($isAdminFooter)
    <footer class="admin-footer">
        2026 MediLabs. Semua hak dilindungi.
    </footer>
@elseif ($isAuthRoute)
    <footer class="site-footer">
        2026 MediLabs. Semua hak dilindungi.
    </footer>
@else
    <footer class="site-footer ml-public-footer">
        <div class="ml-public-footer__inner">
            <span>&copy; 2026 MediLabs. Semua hak dilindungi.</span>

            <nav class="ml-public-footer__links" aria-label="Navigasi footer">
                <a href="{{ route('services.index') }}">Layanan</a>
                <a href="{{ route('reservations.status') }}">Cek Status</a>

                @auth
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}">Dashboard Admin</a>
                    @else
                        <a href="{{ route('profile.show') }}">Profil</a>
                    @endif
                @else
                    <a href="{{ route('login') }}">Masuk</a>
                @endauth
            </nav>
        </div>
    </footer>
@endif
