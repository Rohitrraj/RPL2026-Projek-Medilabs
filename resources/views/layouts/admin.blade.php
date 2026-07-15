<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'MediLabs Admin')</title>

    {{--
        Legacy stylesheet sementara.
        Akan dihapus setelah seluruh halaman admin selesai dimigrasikan.
    --}}
    <link
        rel="stylesheet"
        href="{{ asset('assets/css/medilabs.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('assets/css/admin.css') }}"
    >

    {{--
        Bootstrap, Bootstrap Icons, dan MediLabs design system.
        Sengaja dimuat setelah stylesheet legacy.
    --}}
    @vite([
        'resources/css/admin.css',
        'resources/js/admin.js',
    ])

    @stack('styles')
</head>

<body class="ml-admin">
    <div class="admin-shell">
        <x-admin-topbar />

        <div class="admin-layout">
            <x-admin-sidebar />

            <main class="admin-page">
                <x-flash-message />

                @yield('content')
            </main>
        </div>

        <x-app-footer variant="admin" />
    </div>

    @stack('scripts')
</body>
</html>