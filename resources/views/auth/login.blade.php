@extends('layouts.app')

@section('title', 'MediLabs - Login')

@section('content')
    <section class="auth-layout auth-layout-login">
        <form class="dark-panel auth-panel" action="{{ route('home') }}" method="get">
            <h1>Login</h1>

            <label>
                <span>Email</span>
                <input type="email" name="email" placeholder="nama@gmail.com">
            </label>

            <label>
                <span>Password</span>
                <input type="password" name="password" placeholder="Minimal 8 karakter">
            </label>

            <button class="button button-primary full-button" type="submit">Login</button>

            <p class="form-note">Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
        </form>

        <aside class="illustration-card">
            <img src="{{ asset('assets/images/Loginpage.png') }}" alt="Ilustrasi dokter MediLabs">
            <h2>Bergabung bersama MediLabs</h2>
            <p>Daftarkan diri Anda untuk menikmati kemudahan reservasi dan layanan laboratorium klinik terpercaya.</p>
        </aside>
    </section>
@endsection
