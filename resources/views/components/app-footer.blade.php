@props(['variant' => 'site'])

@php
    $footerClass = $variant === 'admin' ? 'admin-footer' : 'site-footer';
@endphp

<footer class="{{ $footerClass }}">
    2026 MediLabs. Semua hak dilindungi.
</footer>
