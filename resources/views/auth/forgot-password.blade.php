@extends('layouts.app')

@section('title', 'Lupa Password | MediLabs')
@section('body-class', 'ml-auth')
@section('suppress-global-flash', 'true')

@push('styles')
    @vite('resources/css/auth.css')
@endpush

@push('scripts')
    @vite('resources/js/auth.js')
@endpush

@section('content')
    <section
        class="
            ml-auth-page
            ml-auth-page--compact
        "
        aria-labelledby="forgot-password-title"
    >
        <div class="ml-auth-container">
            <div
                class="
                    ml-auth-grid
                    ml-auth-grid--compact
                "
            >
                <div class="ml-auth-intro">
                    <div class="ml-auth-intro__content">
                        <h1
                            id="forgot-password-title"
                            class="ml-auth-intro__title"
                        >
                            Pulihkan akses akun Anda.
                        </h1>

                        <p class="ml-auth-intro__description">
                            Masukkan email akun pasien MediLabs.
                            Kami akan mengirimkan tautan untuk
                            membuat password baru.
                        </p>
                    </div>

                    <ul
                        class="ml-auth-benefits"
                        aria-label="Informasi reset password"
                    >
                        <li class="ml-auth-benefit">
                            <span
                                class="ml-auth-benefit__icon"
                                aria-hidden="true"
                            >
                                <i class="bi bi-envelope-check"></i>
                            </span>

                            <span>
                                Tautan reset dikirim melalui email
                            </span>
                        </li>

                        <li class="ml-auth-benefit">
                            <span
                                class="ml-auth-benefit__icon"
                                aria-hidden="true"
                            >
                                <i class="bi bi-person-check"></i>
                            </span>

                            <span>
                                Berlaku untuk akun pasien MediLabs
                            </span>
                        </li>

                        <li class="ml-auth-benefit">
                            <span
                                class="ml-auth-benefit__icon"
                                aria-hidden="true"
                            >
                                <i class="bi bi-shield-lock"></i>
                            </span>

                            <span>
                                Email akun tetap terlindungi
                            </span>
                        </li>
                    </ul>
                </div>

                <div
                    class="
                        ml-auth-form-column
                        ml-auth-form-column--compact
                    "
                >
                    <div
                        class="
                            ml-auth-card
                            ml-auth-card--compact
                        "
                    >
                        <header class="ml-auth-card__header">
                            <h2 class="ml-auth-card__title">
                                Lupa password
                            </h2>

                            <p class="ml-auth-card__description">
                                Masukkan email yang digunakan
                                saat membuat akun pasien.
                            </p>
                        </header>

                        @if (session('status') || session('success'))
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
                                    {{ session('status') ?? session('success') }}
                                </span>
                            </div>
                        @endif

                        <form
                            class="ml-auth-form"
                            action="{{ route('password.email') }}"
                            method="POST"
                            data-auth-form
                        >
                            @csrf

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
                                        {{ $errors->has('email') ? 'is-invalid' : '' }}
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
                                    aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                                    @error('email')
                                        aria-describedby="email-error"
                                    @enderror
                                >

                                @error('email')
                                    <p
                                        id="email-error"
                                        class="ml-auth-error"
                                    >
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <button
                                class="ml-auth-submit"
                                type="submit"
                                data-submit-button
                            >
                                <span data-submit-label>
                                    Kirim tautan reset
                                </span>

                                <span
                                    class="d-none align-items-center gap-2"
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
                                        Mengirim...
                                    </span>
                                </span>
                            </button>
                        </form>

                        <a
                            class="ml-auth-back-link"
                            href="{{ route('login') }}"
                        >
                            <i
                                class="bi bi-arrow-left"
                                aria-hidden="true"
                            ></i>

                            Kembali ke halaman Masuk
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection