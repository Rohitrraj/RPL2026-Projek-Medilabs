@props(['variant' => 'site'])

@php
    $isAdminFooter = $variant === 'admin';
    $isAuthRoute = request()->routeIs('login', 'register', 'password.*');
@endphp

@if ($isAdminFooter)
    <footer class="admin-footer">
        &copy; 2026 MediLabs. Semua hak dilindungi.
    </footer>
@elseif ($isAuthRoute)
    <footer class="site-footer">
        &copy; 2026 MediLabs. Semua hak dilindungi.
    </footer>
@else
    <footer class="site-footer ml-public-footer">
        <div class="ml-public-footer__inner">
            <span>&copy; 2026 MediLabs. Semua hak dilindungi.</span>
        </div>
    </footer>
@endif
