@extends('layouts.app')

@section('title', 'Daftar Akun - MediLabs')
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
        class="ml-auth-page ml-auth-page--form"
        aria-labelledby="register-page-title"
    >
        <div class="ml-auth-container">
            <div class="ml-auth-grid">
                <div class="ml-auth-intro">
                    <div class="ml-auth-intro__content">
                        <h1
                            id="register-page-title"
                            class="ml-auth-intro__title"
                        >
                            Buat akun pasien MediLabs.
                        </h1>

                        <p class="ml-auth-intro__description">
                            Daftarkan akun untuk membuat reservasi
                            laboratorium, memantau status pemeriksaan,
                            dan menyimpan riwayat layanan Anda.
                        </p>
                    </div>

                    <div class="ml-auth-intro__body">
                        <ul
                            class="ml-auth-benefits"
                            aria-label="Manfaat akun MediLabs"
                        >
                            <li class="ml-auth-benefit">
                                <span
                                    class="ml-auth-benefit__icon"
                                    aria-hidden="true"
                                >
                                    <i class="bi bi-calendar2-check"></i>
                                </span>

                                <span>
                                    Membuat reservasi laboratorium
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
                                    Memantau status pemeriksaan
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
                                    Menyimpan riwayat reservasi
                                </span>
                            </li>
                        </ul>

                        <div
                            class="ml-auth-visual"
                            aria-hidden="true"
                        >
                            <div class="ml-auth-visual__primary">
                                <i class="bi bi-person-plus"></i>
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
                                <i class="bi bi-clipboard2-pulse"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="ml-auth-form-column">
                    <div class="ml-auth-card">
                        <header class="ml-auth-card__header">
                            <h2 class="ml-auth-card__title">
                                Daftar akun
                            </h2>

                            <p class="ml-auth-card__description">
                                Lengkapi data akun untuk mengakses
                                layanan pasien MediLabs.
                            </p>
                        </header>

                        <form
                            class="ml-auth-form ml-auth-form--dense"
                            action="{{ route('register.store') }}"
                            method="POST"
                            data-auth-form
                        >
                            @csrf

                            <div class="ml-auth-field">
                                <label
                                    class="ml-auth-label"
                                    for="name"
                                >
                                    Nama Lengkap
                                </label>

                                <input
                                    id="name"
                                    class="
                                        ml-auth-input
                                        {{ $errors->has('name') ? 'is-invalid' : '' }}
                                    "
                                    type="text"
                                    name="name"
                                    value="{{ old('name') }}"
                                    placeholder="Masukkan nama lengkap"
                                    autocomplete="name"
                                    required
                                    autofocus
                                    aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}"
                                    @error('name')
                                        aria-describedby="name-error"
                                    @enderror
                                >

                                @error('name')
                                    <p
                                        id="name-error"
                                        class="ml-auth-error"
                                    >
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

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

                            <div class="ml-auth-field">
                                <label
                                    class="ml-auth-label"
                                    for="phone"
                                >
                                    Nomor Telepon

                                    <span class="ml-auth-optional">
                                        (Opsional)
                                    </span>
                                </label>

                                <input
                                    id="phone"
                                    class="
                                        ml-auth-input
                                        {{ $errors->has('phone') ? 'is-invalid' : '' }}
                                    "
                                    type="tel"
                                    name="phone"
                                    value="{{ old('phone') }}"
                                    placeholder="Contoh: 081234567890"
                                    autocomplete="tel"
                                    inputmode="tel"
                                    aria-invalid="{{ $errors->has('phone') ? 'true' : 'false' }}"
                                    @error('phone')
                                        aria-describedby="phone-error"
                                    @enderror
                                >

                                @error('phone')
                                    <p
                                        id="phone-error"
                                        class="ml-auth-error"
                                    >
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="ml-auth-field">
                                <label
                                    class="ml-auth-label"
                                    for="password"
                                >
                                    Password
                                </label>

                                <div class="ml-auth-password">
                                    <input
                                        id="password"
                                        class="
                                            ml-auth-input
                                            {{ $errors->has('password') ? 'is-invalid' : '' }}
                                        "
                                        type="password"
                                        name="password"
                                        placeholder="Minimal 8 karakter"
                                        autocomplete="new-password"
                                        required
                                        aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                                        @error('password')
                                            aria-describedby="password-error"
                                        @enderror
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

                                @error('password')
                                    <p
                                        id="password-error"
                                        class="ml-auth-error"
                                    >
                                        {{ $message }}
                                    </p>
                                @else
                                    <p class="ml-auth-help">
                                        Gunakan minimal 8 karakter.
                                    </p>
                                @enderror
                            </div>

                            <div class="ml-auth-field">
                                <label
                                    class="ml-auth-label"
                                    for="password_confirmation"
                                >
                                    Konfirmasi Password
                                </label>

                                <div class="ml-auth-password">
                                    <input
                                        id="password_confirmation"
                                        class="ml-auth-input"
                                        type="password"
                                        name="password_confirmation"
                                        placeholder="Ulangi password"
                                        autocomplete="new-password"
                                        required
                                    >

                                    <button
                                        class="ml-auth-password-toggle"
                                        type="button"
                                        data-password-toggle="password_confirmation"
                                        aria-label="Tampilkan konfirmasi password"
                                        aria-controls="password_confirmation"
                                        aria-pressed="false"
                                        title="Tampilkan konfirmasi password"
                                    >
                                        <i
                                            class="bi bi-eye"
                                            data-password-icon
                                            aria-hidden="true"
                                        ></i>
                                    </button>
                                </div>
                            </div>

                            <button
                                class="ml-auth-submit"
                                type="submit"
                                data-submit-button
                            >
                                <span data-submit-label>
                                    Daftar
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
                                        Membuat akun...
                                    </span>
                                </span>
                            </button>
                        </form>

                        <p class="ml-auth-register-note">
                            Sudah memiliki akun?

                            <a
                                class="ml-auth-register-link"
                                href="{{ route('login') }}"
                            >
                                Masuk
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection