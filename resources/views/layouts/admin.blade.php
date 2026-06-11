<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'MediLabs Admin')</title>
    <link rel="stylesheet" href="{{ asset('assets/css/medilabs.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
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
</body>
</html>
