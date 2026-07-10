@extends('layouts.app')

@section('title', 'MediLabs - Lupa Password')

@section('content')
    <section class="auth-layout auth-layout-login">
        <form
            class="dark-panel auth-panel"
            action="{{ route('password.email') }}"
            method="POST"
        >
            @csrf

            <h1>Lupa Password</h1>

            <p class="form-note">
                Masukkan email akun pasien. Sistem akan mengirimkan tautan untuk membuat password baru.
            </p>

            <label>
                <span>Email</span>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="nama@gmail.com"
                    required
                    autofocus
                >
            </label>

            @error('email')
                <p class="form-note">{{ $message }}</p>
            @enderror

            <button class="button button-primary full-button" type="submit">
                Kirim Tautan Reset
            </button>

            <p class="form-note">
                <a href="{{ route('login') }}">Kembali ke login</a>
            </p>
        </form>

        <aside class="illustration-card">
            <img
                src="{{ asset('assets/images/Loginpage.png') }}"
                alt="Ilustrasi dokter MediLabs"
            >

            <h2>Pulihkan akun pasien</h2>

            <p>
                Gunakan email yang terdaftar pada akun MediLabs untuk mengatur password baru.
            </p>
        </aside>
    </section>
@endsection