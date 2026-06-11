<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'MediLabs')</title>
    <link rel="stylesheet" href="{{ asset('assets/css/medilabs.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/services.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/reservation.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">
</head>
<body>
    <div class="app-shell">
        <x-app-navbar />

        <main class="page-content">
            <x-flash-message />
            @yield('content')
        </main>

        <x-app-footer />
    </div>

    <script src="{{ asset('assets/js/medilabs.js') }}"></script>
</body>
</html>
