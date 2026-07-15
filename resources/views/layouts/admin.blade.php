<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'MediLabs Admin')</title>

    {{-- Fondasi frontend admin baru --}}
    @vite([
        'resources/css/admin.css',
        'resources/js/admin.js',
    ])

    {{--
        CSS lama dipertahankan sementara selama masa transisi.
        Urutan ini membuat CSS lama tetap dapat menimpa style Bootstrap
        sehingga tampilan baseline tidak langsung rusak pada Tahap 2B.
    --}}
    <link rel="stylesheet" href="{{ asset('assets/css/medilabs.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">

    @stack('styles')
</head>

<body>
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