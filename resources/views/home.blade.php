@extends('layouts.app')

@section('title', 'MediLabs - Beranda')

@section('content')
    <section class="home-grid">
        <div class="hero-card">
            <h1>Reservasi Laboratorium Klinik Lebih Mudah</h1>
            <p>Reservasi pemeriksaan laboratorium kini lebih cepat, mudah, dan praktis secara online sesuai kebutuhan anda.</p>
            <div class="hero-actions">
                <a class="button button-primary" href="{{ route('patients.create') }}">Buat Reservasi</a>
                <a class="button button-outline" href="{{ route('services.show') }}">Lihat Layanan</a>
            </div>
        </div>

        <img class="hero-image" src="{{ asset('assets/images/hospital.svg') }}" alt="Gedung klinik MediLabs">
    </section>

    <section class="feature-row" aria-label="Alur layanan">
        @foreach ($features as $feature)
            <article class="feature-card">
                <span class="feature-icon feature-icon-{{ $feature['icon'] }}"></span>
                <div>
                    <h2>{{ $feature['title'] }}</h2>
                    <p>{{ $feature['text'] }}</p>
                </div>
            </article>
        @endforeach
    </section>

    <section class="popular-section">
        <h2>Layanan Populer</h2>
        <div class="service-grid">
            @foreach ($services as $service)
                <article class="service-card">
                    <h3>{{ $service['title'] }}</h3>
                    <p>{{ $service['text'] }}</p>
                </article>
            @endforeach
        </div>
    </section>
@endsection
