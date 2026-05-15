@extends('layouts.app')

@section('title', 'MediLabs - Beranda')

@section('content')
    <section class="home-grid">
        <div class="hero-card">
            <h1>Reservasi Laboratorium Klinik Lebih Mudah</h1>
            <p>Reservasi pemeriksaan laboratorium kini lebih cepat, mudah, dan praktis secara online sesuai kebutuhan anda.</p>
            <div class="hero-actions">
                <a class="button button-primary" href="{{ route('reservations.create') }}">Buat Reservasi</a>
                <a class="button button-outline" href="{{ route('services.index') }}">Lihat Layanan</a>
            </div>
        </div>

        <img class="hero-image" src="{{ asset('assets/images/lanpagehospital.png') }}" alt="Gedung klinik MediLabs">
    </section>

    <h2 class="section-title">Alur Penggunaan MediLabs</h2>

    <section class="feature-row" aria-label="Alur penggunaan MediLabs">
        @foreach ($features as $feature)
            <a href="{{ $feature['route'] }}" class="feature-card feature-card-link">
                <img
                    class="feature-image-icon"
                    src="{{ asset($feature['image']) }}"
                    alt="{{ $feature['title'] }}"
                >

                <div>
                    <h2>{{ $feature['title'] }}</h2>
                    <p>{{ $feature['text'] }}</p>
                </div>
            </a>
        @endforeach
    </section>

    <section class="popular-section">
        <h2>Layanan Populer</h2>

        <div class="service-grid">
            @foreach ($services as $service)
                <a
                    href="{{ $service['route'] }}"
                    class="service-card service-card-popular-bg"
                    style="--service-bg: url('{{ asset($service['image']) }}')"
                >
                    <div class="service-card-overlay">
                        <h3>{{ $service['title'] }}</h3>
                        <p>{{ $service['text'] }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
@endsection
