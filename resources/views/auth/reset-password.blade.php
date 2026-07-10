@extends('layouts.app')

@section('title', 'MediLabs - Reset Password')

@section('content')
    <section class="auth-layout auth-layout-login">
        <form
            class="dark-panel auth-panel"
            action="{{ route('password.update') }}"
            method="POST"
        >
            @csrf

            <h1>Reset Password</h1>

            <input type="hidden" name="token" value="{{ $token }}">

            <label>
                <span>Email</span>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email', $email) }}"
                    placeholder="nama@gmail.com"
                    required
                    autofocus
                >
            </label>

            @error('email')
                <p class="form-note">{{ $message }}</p>
            @enderror

            <label>
                <span>Password Baru</span>
                <input
                    type="password"
                    name="password"
                    placeholder="Minimal 8 karakter"
                    required
                >
            </label>

            @error('password')
                <p class="form-note">{{ $message }}</p>
            @enderror

            <label>
                <span>Konfirmasi Password Baru</span>
                <input
                    type="password"
                    name="password_confirmation"
                    placeholder="Ulangi password baru"
                    required
                >
            </label>

            <button class="button button-primary full-button" type="submit">
                Simpan Password Baru
            </button>
        </form>

        <aside class="illustration-card">
            <img
                src="{{ asset('assets/images/Loginpage.png') }}"
                alt="Ilustrasi dokter MediLabs"
            >

            <h2>Buat password baru</h2>

            <p>
                Gunakan password yang kuat dan tidak sama dengan password akun lain.
            </p>
        </aside>
    </section>
@endsection