@extends('layouts.app')

@section('title', 'Reset Password | MediLabs')
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
            ml-auth-page--form
        "
        aria-labelledby="reset-password-title"
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
                            id="reset-password-title"
                            class="ml-auth-intro__title"
                        >
                            Buat password baru.
                        </h1>

                        <p class="ml-auth-intro__description">
                            Gunakan password baru untuk memulihkan
                            akses ke akun pasien MediLabs Anda.
                        </p>
                    </div>

                    <ul
                        class="ml-auth-benefits"
                        aria-label="Ketentuan password baru"
                    >
                        <li class="ml-auth-benefit">
                            <span
                                class="ml-auth-benefit__icon"
                                aria-hidden="true"
                            >
                                <i class="bi bi-shield-lock"></i>
                            </span>

                            <span>
                                Password minimal 8 karakter
                            </span>
                        </li>

                        <li class="ml-auth-benefit">
                            <span
                                class="ml-auth-benefit__icon"
                                aria-hidden="true"
                            >
                                <i class="bi bi-check2-circle"></i>
                            </span>

                            <span>
                                Konfirmasi password harus sesuai
                            </span>
                        </li>

                        <li class="ml-auth-benefit">
                            <span
                                class="ml-auth-benefit__icon"
                                aria-hidden="true"
                            >
                                <i class="bi bi-box-arrow-in-right"></i>
                            </span>

                            <span>
                                Masuk kembali setelah password diperbarui
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
                    <div class="ml-auth-card">
                        <header class="ml-auth-card__header">
                            <h2 class="ml-auth-card__title">
                                Reset password
                            </h2>

                            <p class="ml-auth-card__description">
                                Masukkan email dan password baru Anda.
                            </p>
                        </header>

                        <form
                            class="ml-auth-form"
                            action="{{ route('password.update') }}"
                            method="POST"
                            data-auth-form
                        >
                            @csrf

                            <input
                                type="hidden"
                                name="token"
                                value="{{ $token ?? request()->route('token') }}"
                            >

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
                                    value="{{ old('email', $email ?? request('email')) }}"
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

                            <div class="ml-auth-field">
                                <label
                                    class="ml-auth-label"
                                    for="password"
                                >
                                    Password Baru
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
                                        aria-label="Tampilkan password baru"
                                        aria-controls="password"
                                        aria-pressed="false"
                                        title="Tampilkan password baru"
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
                                    Konfirmasi Password Baru
                                </label>

                                <div class="ml-auth-password">
                                    <input
                                        id="password_confirmation"
                                        class="ml-auth-input"
                                        type="password"
                                        name="password_confirmation"
                                        placeholder="Ulangi password baru"
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
                                    Simpan password baru
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
                                        Menyimpan...
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