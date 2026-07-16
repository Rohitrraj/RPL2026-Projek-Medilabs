@php
    $brandLogo = 'assets/images/logo.png';
    $isAuthRoute = request()->routeIs('login', 'register', 'password.*');
    $currentUser = auth()->user();
    $isAdmin = $currentUser?->role === 'admin';
    $isPatient = $currentUser && ! $isAdmin;

    $isHome = request()->routeIs('home');
    $isServices = request()->routeIs('services.*');
    $isReservation = request()->routeIs('reservations.create', 'reservations.result');
    $isHistory = request()->routeIs('reservations.history');
    $isStatus = request()->routeIs('reservations.status');
    $isProfile = request()->routeIs('profile.show');
    $isPatientData = request()->routeIs('patients.*');

    $accountName = $currentUser?->name ?? 'Pengguna';
    $accountInitial = \Illuminate\Support\Str::upper(
        \Illuminate\Support\Str::substr($accountName, 0, 1)
    );
@endphp

@if ($isAuthRoute)
    {{-- Auth mempertahankan struktur navbar yang sudah disetujui. --}}
    <header class="site-header">
        <a class="brand" href="{{ route('home') }}" aria-label="MediLabs">
            <img src="{{ asset($brandLogo) }}" alt="Logo MediLabs">
            <span>
                <strong>MediLabs</strong>
                <small>Sistem Reservasi dan Pendaftaran Laboratorium Klinik</small>
            </span>
        </a>

        <nav class="main-nav" aria-label="Navigasi utama">
            <a class="{{ $isHome ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a>
            <a class="{{ $isServices ? 'active' : '' }}" href="{{ route('services.index') }}">Layanan</a>
            <a href="{{ route('reservations.create') }}">Reservasi</a>
            <a class="{{ $isStatus ? 'active' : '' }}" href="{{ route('reservations.status') }}">Cek Status</a>
            <a class="{{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">Masuk</a>
        </nav>
    </header>
@else
    <header class="site-header ml-public-header" data-public-header>
        <div class="ml-public-header__inner">
            <a class="brand ml-public-brand" href="{{ route('home') }}" aria-label="MediLabs">
                <img src="{{ asset($brandLogo) }}" alt="Logo MediLabs">
                <span class="ml-public-brand__copy">
                    <strong>MediLabs</strong>
                    <small>Sistem Reservasi dan Pendaftaran Laboratorium Klinik</small>
                </span>
            </a>

            <nav class="main-nav ml-public-nav" aria-label="Navigasi utama desktop">
                @if ($isAdmin)
                    <a
                        class="ml-public-nav__link {{ $isServices ? 'is-active' : '' }}"
                        href="{{ route('services.index') }}"
                        @if ($isServices) aria-current="page" @endif
                    >
                        Layanan
                    </a>

                    <a
                        class="ml-public-nav__link {{ $isStatus ? 'is-active' : '' }}"
                        href="{{ route('reservations.status') }}"
                        @if ($isStatus) aria-current="page" @endif
                    >
                        Cek Status
                    </a>
                @else
                    <a
                        class="ml-public-nav__link {{ $isHome ? 'is-active' : '' }}"
                        href="{{ route('home') }}"
                        @if ($isHome) aria-current="page" @endif
                    >
                        Beranda
                    </a>

                    <a
                        class="ml-public-nav__link {{ $isServices ? 'is-active' : '' }}"
                        href="{{ route('services.index') }}"
                        @if ($isServices) aria-current="page" @endif
                    >
                        Layanan
                    </a>

                    <a
                        class="ml-public-nav__link {{ $isReservation ? 'is-active' : '' }}"
                        href="{{ route('reservations.create') }}"
                        @if ($isReservation) aria-current="page" @endif
                    >
                        Reservasi
                    </a>

                    @if ($isPatient)
                        <a
                            class="ml-public-nav__link {{ $isHistory ? 'is-active' : '' }}"
                            href="{{ route('reservations.history') }}"
                            @if ($isHistory) aria-current="page" @endif
                        >
                            Riwayat
                        </a>
                    @endif

                    <a
                        class="ml-public-nav__link {{ $isStatus ? 'is-active' : '' }}"
                        href="{{ route('reservations.status') }}"
                        @if ($isStatus) aria-current="page" @endif
                    >
                        Cek Status
                    </a>
                @endif
            </nav>

            <div class="ml-public-header__actions">
                @guest
                    <a class="ml-public-login-link" href="{{ route('login') }}">
                        Masuk
                    </a>
                @else
                    <div class="ml-public-account" data-public-account>
                        <button
                            class="ml-public-account__trigger"
                            type="button"
                            aria-expanded="false"
                            aria-controls="public-account-menu"
                            data-public-account-trigger
                        >
                            <span class="ml-public-account__avatar" aria-hidden="true">
                                {{ $accountInitial }}
                            </span>

                            <span class="ml-public-account__copy">
                                <strong>{{ $accountName }}</strong>
                                <small>{{ $isAdmin ? 'Admin' : 'Pasien' }}</small>
                            </span>

                            <i
                                class="bi bi-chevron-down ml-public-account__chevron"
                                aria-hidden="true"
                            ></i>
                        </button>

                        <div
                            id="public-account-menu"
                            class="ml-public-account__menu"
                            data-public-account-menu
                            hidden
                        >
                            @if ($isAdmin)
                                <a
                                    class="ml-public-account__link"
                                    href="{{ route('admin.dashboard') }}"
                                >
                                    <i class="bi bi-speedometer2" aria-hidden="true"></i>
                                    Dashboard Admin
                                </a>
                            @else
                                <a
                                    class="ml-public-account__link {{ $isProfile ? 'is-active' : '' }}"
                                    href="{{ route('profile.show') }}"
                                >
                                    <i class="bi bi-person" aria-hidden="true"></i>
                                    Profil Saya
                                </a>

                                <a
                                    class="ml-public-account__link {{ $isPatientData ? 'is-active' : '' }}"
                                    href="{{ route('patients.create', ['edit' => 1]) }}"
                                >
                                    <i class="bi bi-person-vcard" aria-hidden="true"></i>
                                    Data Pasien
                                </a>
                            @endif

                            <div class="ml-public-account__divider" aria-hidden="true"></div>

                            <form
                                class="ml-public-account__form"
                                action="{{ route('logout') }}"
                                method="POST"
                            >
                                @csrf
                                <button class="ml-public-account__logout" type="submit">
                                    <i class="bi bi-box-arrow-right" aria-hidden="true"></i>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>

            <button
                class="ml-public-nav-toggle"
                type="button"
                aria-label="Buka navigasi"
                aria-expanded="false"
                aria-controls="public-mobile-nav"
                data-public-nav-toggle
            >
                <i class="bi bi-list" aria-hidden="true"></i>
            </button>
        </div>

        <nav
            id="public-mobile-nav"
            class="ml-public-mobile-nav"
            aria-label="Navigasi utama mobile"
            data-public-mobile-nav
            hidden
        >
            <div class="ml-public-mobile-nav__inner">
                @if ($isAdmin)
                    <a class="ml-public-mobile-nav__link" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2" aria-hidden="true"></i>
                        Dashboard Admin
                    </a>

                    <a
                        class="ml-public-mobile-nav__link {{ $isServices ? 'is-active' : '' }}"
                        href="{{ route('services.index') }}"
                    >
                        <i class="bi bi-activity" aria-hidden="true"></i>
                        Layanan
                    </a>

                    <a
                        class="ml-public-mobile-nav__link {{ $isStatus ? 'is-active' : '' }}"
                        href="{{ route('reservations.status') }}"
                    >
                        <i class="bi bi-search" aria-hidden="true"></i>
                        Cek Status
                    </a>
                @else
                    <a
                        class="ml-public-mobile-nav__link {{ $isHome ? 'is-active' : '' }}"
                        href="{{ route('home') }}"
                    >
                        <i class="bi bi-house" aria-hidden="true"></i>
                        Beranda
                    </a>

                    <a
                        class="ml-public-mobile-nav__link {{ $isServices ? 'is-active' : '' }}"
                        href="{{ route('services.index') }}"
                    >
                        <i class="bi bi-activity" aria-hidden="true"></i>
                        Layanan
                    </a>

                    <a
                        class="ml-public-mobile-nav__link {{ $isReservation ? 'is-active' : '' }}"
                        href="{{ route('reservations.create') }}"
                    >
                        <i class="bi bi-calendar2-check" aria-hidden="true"></i>
                        Reservasi
                    </a>

                    @if ($isPatient)
                        <a
                            class="ml-public-mobile-nav__link {{ $isHistory ? 'is-active' : '' }}"
                            href="{{ route('reservations.history') }}"
                        >
                            <i class="bi bi-clock-history" aria-hidden="true"></i>
                            Riwayat
                        </a>
                    @endif

                    <a
                        class="ml-public-mobile-nav__link {{ $isStatus ? 'is-active' : '' }}"
                        href="{{ route('reservations.status') }}"
                    >
                        <i class="bi bi-search" aria-hidden="true"></i>
                        Cek Status
                    </a>
                @endif

                @guest
                    <div class="ml-public-mobile-nav__account">
                        <a class="ml-public-mobile-nav__link" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right" aria-hidden="true"></i>
                            Masuk
                        </a>
                    </div>
                @else
                    <div class="ml-public-mobile-nav__account">
                        <div class="ml-public-mobile-nav__identity">
                            <span class="ml-public-account__avatar" aria-hidden="true">
                                {{ $accountInitial }}
                            </span>

                            <span class="ml-public-mobile-nav__identity-copy">
                                <strong>{{ $accountName }}</strong>
                                <small>{{ $isAdmin ? 'Admin' : 'Pasien' }}</small>
                            </span>
                        </div>

                        @unless ($isAdmin)
                            <a
                                class="ml-public-mobile-nav__link {{ $isProfile ? 'is-active' : '' }}"
                                href="{{ route('profile.show') }}"
                            >
                                <i class="bi bi-person" aria-hidden="true"></i>
                                Profil Saya
                            </a>

                            <a
                                class="ml-public-mobile-nav__link {{ $isPatientData ? 'is-active' : '' }}"
                                href="{{ route('patients.create', ['edit' => 1]) }}"
                            >
                                <i class="bi bi-person-vcard" aria-hidden="true"></i>
                                Data Pasien
                            </a>
                        @endunless

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="ml-public-mobile-nav__logout" type="submit">
                                <i class="bi bi-box-arrow-right" aria-hidden="true"></i>
                                Keluar
                            </button>
                        </form>
                    </div>
                @endguest
            </div>
        </nav>
    </header>
@endif
