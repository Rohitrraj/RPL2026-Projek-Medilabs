@extends('layouts.app')

@section('title', 'Masuk | MediLabs')
@section('body-class', 'ml-auth ml-auth--fixed-footer')
@section('suppress-global-flash', 'true')

@push('styles')
    @vite('resources/css/auth.css')
@endpush

@push('scripts')
    @vite('resources/js/auth.js')
@endpush

@php
    $credentialError = in_array(
        'Email atau password tidak sesuai.',
        $errors->get('email'),
        true,
    );

    $emailFieldInvalid =
        $errors->has('email')
        && ! $credentialError;

    $passwordFieldInvalid =
        $errors->has('password');

    $intendedPath = parse_url(
        (string) session('url.intended', ''),
        PHP_URL_PATH,
    );
    $statusPath = parse_url(route('reservations.status'), PHP_URL_PATH);
    $statusLoginRequired = request('reason') === 'status'
        || $intendedPath === $statusPath;
@endphp

@section('content')
    <section
        class="ml-auth-page"
        aria-labelledby="login-page-title"
    >
        <div class="ml-auth-container">
            <div class="ml-auth-grid">
                {{-- Introduction --}}
                <div class="ml-auth-intro">
                    <div class="ml-auth-intro__content">
                        <h1
                            id="login-page-title"
                            class="ml-auth-intro__title"
                        >
                            Kelola reservasi laboratorium
                            dengan lebih mudah.
                        </h1>

                        <p class="ml-auth-intro__description">
                            Masuk untuk membuat reservasi,
                            memantau status pemeriksaan,
                            dan melihat riwayat layanan Anda.
                        </p>
                    </div>

                    <div class="ml-auth-intro__body">
                        <ul
                            class="ml-auth-benefits"
                            aria-label="Fitur akun pasien"
                        >
                            <li class="ml-auth-benefit">
                                <span
                                    class="ml-auth-benefit__icon"
                                    aria-hidden="true"
                                >
                                    <i class="bi bi-calendar2-check"></i>
                                </span>

                                <span>
                                    Reservasi layanan laboratorium
                                </span>
                            </li>

                            <li class="ml-auth-benefit">
                                <span
                                    class="ml-auth-benefit__icon"
                                    aria-hidden="true"
                                >
                                    <i class="bi bi-clock-history"></i>
                                </span>

                                <span>
                                    Pemantauan status pemeriksaan
                                </span>
                            </li>

                            <li class="ml-auth-benefit">
                                <span
                                    class="ml-auth-benefit__icon"
                                    aria-hidden="true"
                                >
                                    <i class="bi bi-folder-check"></i>
                                </span>

                                <span>
                                    Riwayat reservasi tersimpan
                                </span>
                            </li>
                        </ul>

                        <div
                            class="ml-auth-visual"
                            aria-hidden="true"
                        >
                            <div class="ml-auth-visual__primary">
                                <i class="bi bi-clipboard2-pulse"></i>
                            </div>

                            <span
                                class="
                                    ml-auth-visual__badge
                                    ml-auth-visual__badge--top
                                "
                            >
                                <i class="bi bi-shield-check"></i>
                            </span>

                            <span
                                class="
                                    ml-auth-visual__badge
                                    ml-auth-visual__badge--bottom
                                "
                            >
                                <i class="bi bi-heart-pulse"></i>
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Login Form --}}
                <div class="ml-auth-form-column">
                    <div class="ml-auth-card">
                        <header class="ml-auth-card__header">
                            <h2 class="ml-auth-card__title">
                                Masuk ke MediLabs
                            </h2>

                            <p class="ml-auth-card__description">
                                Gunakan akun Anda untuk melanjutkan
                                ke layanan pasien.
                            </p>
                        </header>

                        @if (session('success'))
                            <div
                                class="
                                    ml-auth-alert
                                    ml-auth-alert--success
                                    mb-4
                                "
                                role="status"
                            >
                                <span
                                    class="ml-auth-alert__icon"
                                    aria-hidden="true"
                                >
                                    <i class="bi bi-check-circle"></i>
                                </span>

                                <span>
                                    {{ session('success') }}
                                </span>
                            </div>
                        @endif

                        @if ($statusLoginRequired)
                            <div
                                class="ml-auth-alert ml-auth-alert--info mb-4"
                                role="status"
                            >
                                <span
                                    class="ml-auth-alert__icon"
                                    aria-hidden="true"
                                >
                                    <i class="bi bi-info-circle"></i>
                                </span>

                                <span>
                                    Anda belum login. Silakan masuk untuk melihat
                                    status pemeriksaan pada akun Anda.
                                </span>
                            </div>
                        @endif

                        @if ($credentialError)
                            <div
                                class="ml-auth-alert mb-4"
                                role="alert"
                            >
                                <span
                                    class="ml-auth-alert__icon"
                                    aria-hidden="true"
                                >
                                    <i class="bi bi-exclamation-circle"></i>
                                </span>

                                <span>
                                    Email atau password tidak sesuai.
                                </span>
                            </div>
                        @endif

                        <form
                            class="ml-auth-form"
                            action="{{ route('login.store') }}"
                            method="POST"
                            data-auth-form
                        >
                            @csrf

                            {{-- Email --}}
                            <div class="ml-auth-field">
                                <label
                                    class="ml-auth-label"
                                    for="email"
                                >
                                    Email
                                </label>

                                <input
                                    id="email"
                                    class="
                                        ml-auth-input
                                        {{ $emailFieldInvalid ? 'is-invalid' : '' }}
                                    "
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="nama@email.com"
                                    autocomplete="email"
                                    inputmode="email"
                                    autocapitalize="none"
                                    spellcheck="false"
                                    required
                                    autofocus
                                    aria-invalid="{{ $emailFieldInvalid ? 'true' : 'false' }}"
                                    @if ($emailFieldInvalid)
                                        aria-describedby="email-error"
                                    @endif
                                >

                                @if ($emailFieldInvalid)
                                    <p
                                        id="email-error"
                                        class="ml-auth-error"
                                    >
                                        {{ filled(old('email'))
                                            ? 'Format email tidak valid.'
                                            : 'Email wajib diisi.' }}
                                    </p>
                                @endif
                            </div>

                            {{-- Password --}}
                            <div class="ml-auth-field">
                                <div class="ml-auth-field__header">
                                    <label
                                        class="ml-auth-label"
                                        for="password"
                                    >
                                        Password
                                    </label>

                                    <a
                                        class="ml-auth-forgot-link"
                                        href="{{ route('password.request') }}"
                                    >
                                        Lupa password?
                                    </a>
                                </div>

                                <div class="ml-auth-password">
                                    <input
                                        id="password"
                                        class="
                                            ml-auth-input
                                            {{ $passwordFieldInvalid ? 'is-invalid' : '' }}
                                        "
                                        type="password"
                                        name="password"
                                        placeholder="Masukkan password"
                                        autocomplete="current-password"
                                        required
                                        aria-invalid="{{ $passwordFieldInvalid ? 'true' : 'false' }}"
                                        @if ($passwordFieldInvalid)
                                            aria-describedby="password-error"
                                        @endif
                                    >

                                    <button
                                        class="ml-auth-password-toggle"
                                        type="button"
                                        data-password-toggle="password"
                                        aria-label="Tampilkan password"
                                        aria-controls="password"
                                        aria-pressed="false"
                                        title="Tampilkan password"
                                    >
                                        <i
                                            class="bi bi-eye"
                                            data-password-icon
                                            aria-hidden="true"
                                        ></i>
                                    </button>
                                </div>

                                @if ($passwordFieldInvalid)
                                    <p
                                        id="password-error"
                                        class="ml-auth-error"
                                    >
                                        Password wajib diisi.
                                    </p>
                                @endif
                            </div>

                            {{-- Submit --}}
                            <button
                                class="ml-auth-submit"
                                type="submit"
                                data-submit-button
                            >
                                <span data-submit-label>
                                    Masuk
                                </span>

                                <span
                                    class="
                                        d-none
                                        align-items-center
                                        gap-2
                                    "
                                    data-submit-loading
                                >
                                    <span
                                        class="
                                            spinner-border
                                            spinner-border-sm
                                        "
                                        aria-hidden="true"
                                    ></span>

                                    <span>
                                        Memproses...
                                    </span>
                                </span>
                            </button>
                        </form>

                        <p class="ml-auth-register-note">
                            Belum memiliki akun?

                            <a
                                class="ml-auth-register-link"
                                href="{{ route('register') }}"
                            >
                                Daftar sekarang
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection