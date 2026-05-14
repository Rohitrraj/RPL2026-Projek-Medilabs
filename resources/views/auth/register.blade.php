@extends('layouts.app')

@section('title', 'MediLabs - Daftar Akun')

@section('content')
    <section class="auth-layout">
        <form class="dark-panel auth-panel" action="{{ route('login') }}" method="get">
            <h1>Daftar Akun</h1>

            <label>
                <span>Nama Lengkap</span>
                <input type="text" name="name" placeholder="Masukkan nama lengkap">
            </label>

            <label>
                <span>Email</span>
                <input type="email" name="email" placeholder="nama@gmail.com">
            </label>

            <label>
                <span>No. Telepon</span>
                <input type="tel" name="phone" placeholder="08xxxxxxxxxx">
            </label>

            <label>
                <span>Password</span>
                <input type="password" name="password" placeholder="Minimal 8 karakter">
            </label>

            <label>
                <span>Konfirmasi Password</span>
                <input type="password" name="password_confirmation" placeholder="Ulangi Password">
            </label>

            <button class="button button-primary" type="submit">Daftar</button>

            <p class="form-note">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
        </form>

        <aside class="illustration-card">
            <img src="{{ asset('assets/images/doctor-card.svg') }}" alt="Ilustrasi dokter MediLabs">
            <h2>Bergabung bersama MediLabs</h2>
            <p>Daftarkan diri Anda untuk menikmati kemudahan reservasi dan layanan laboratorium klinik terpercaya.</p>
        </aside>
    </section>
@endsection
