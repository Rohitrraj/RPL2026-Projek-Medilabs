@extends('layouts.app')

@section('title', 'Beranda | MediLabs')
@section('body-class', 'ml-public ml-public--home')

@section('content')
    <div class="ml-home">
        <section class="ml-home-hero" aria-labelledby="home-hero-title">
            <div class="ml-home-hero__content">
                <span class="ml-public-eyebrow">
                    <i class="bi bi-shield-check" aria-hidden="true"></i>
                    Reservasi laboratorium klinik
                </span>

                <h1 id="home-hero-title" class="ml-home-hero__title">
                    Reservasi pemeriksaan laboratorium lebih teratur.
                </h1>

                <p class="ml-home-hero__description">
                    Pilih layanan, tentukan jadwal, dan pantau status reservasi
                    dalam satu sistem MediLabs yang mudah digunakan.
                </p>

                <div class="ml-public-inline-actions">
                    <a
                        class="ml-public-button ml-public-button--primary"
                        href="{{ route('reservations.create') }}"
                    >
                        <i class="bi bi-calendar2-plus" aria-hidden="true"></i>
                        Buat Reservasi
                    </a>

                    <a
                        class="ml-public-button ml-public-button--outline"
                        href="{{ route('services.index') }}"
                    >
                        Lihat Layanan
                        <i class="bi bi-arrow-right" aria-hidden="true"></i>
                    </a>
                </div>

                <dl class="ml-home-hero__facts" aria-label="Keunggulan MediLabs">
                    <div>
                        <dt>Online</dt>
                        <dd>Reservasi tanpa antre pendaftaran awal.</dd>
                    </div>
                    <div>
                        <dt>Terpantau</dt>
                        <dd>Status reservasi dapat diperiksa kembali.</dd>
                    </div>
                    <div>
                        <dt>Terintegrasi</dt>
                        <dd>Data pasien dan riwayat tersimpan dalam akun.</dd>
                    </div>
                </dl>
            </div>

            <div class="ml-home-hero__media">
                <img
                    src="{{ asset('assets/images/lanpagehospital.png') }}"
                    alt="Gedung laboratorium klinik MediLabs"
                >

            </div>
        </section>

        <section class="ml-home-section" aria-labelledby="home-flow-title">
            <header class="ml-home-section__header">
                <div>
                    <span class="ml-public-eyebrow">Alur penggunaan</span>
                    <h2 id="home-flow-title">Empat langkah menggunakan MediLabs</h2>
                </div>
            </header>

            <div class="ml-home-flow-grid">
                @foreach ($features as $feature)
                    <a href="{{ $feature['route'] }}" class="ml-home-flow-card">
                        <span class="ml-home-flow-card__number">
                            {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}
                        </span>

                        <img
                            class="ml-home-flow-card__icon"
                            src="{{ asset($feature['image']) }}"
                            alt=""
                            aria-hidden="true"
                        >

                        <div>
                            <h3>{{ $feature['title'] }}</h3>
                            <p>{{ $feature['text'] }}</p>
                        </div>

                        <i class="bi bi-arrow-up-right" aria-hidden="true"></i>
                    </a>
                @endforeach
            </div>
        </section>

        <section class="ml-home-section" aria-labelledby="home-services-title">
            <header class="ml-home-section__header">
                <div>
                    <span class="ml-public-eyebrow">Layanan aktif</span>
                    <h2 id="home-services-title">Layanan laboratorium populer</h2>
                </div>

                <a
                    class="ml-public-button ml-public-button--outline ml-public-button--sm"
                    href="{{ route('services.index') }}"
                >
                    Semua Layanan
                    <i class="bi bi-arrow-right" aria-hidden="true"></i>
                </a>
            </header>

            @if (empty($services))
                <div class="ml-public-empty-state">
                    <span class="ml-public-empty-state__icon" aria-hidden="true">
                        <i class="bi bi-activity"></i>
                    </span>
                    <h2>Belum ada layanan aktif</h2>
                    <p>Layanan akan ditampilkan kembali setelah tersedia.</p>
                </div>
            @else
                <div class="ml-home-services-grid">
                    @foreach ($services as $service)
                        <a href="{{ $service['route'] }}" class="ml-home-service-card">
                            <span class="ml-home-service-card__visual">
                                <img
                                    src="{{ asset($service['image']) }}"
                                    alt="Ilustrasi layanan {{ $service['title'] }}"
                                >
                            </span>

                            <div class="ml-home-service-card__content">
                                <h3>{{ $service['title'] }}</h3>
                                <p>{{ $service['text'] }}</p>
                            </div>

                            <span class="ml-home-service-card__link">
                                Detail
                                <i class="bi bi-arrow-right" aria-hidden="true"></i>
                            </span>
                        </a>
                    @endforeach
                </div>
            @endif
        </section>

        <aside class="ml-home-status-cta">
            <div>
                <span class="ml-public-eyebrow">
                    <i class="bi bi-search" aria-hidden="true"></i>
                    Sudah memiliki kode reservasi?
                </span>
                <h2>Pantau status pemeriksaan Anda.</h2>
                <p>
                    Masukkan kode reservasi untuk melihat jadwal dan status terbaru.
                </p>
            </div>

            <a
                class="ml-public-button ml-public-button--primary"
                href="{{ auth()->check()
                    ? route('reservations.status')
                    : route('login', ['reason' => 'status']) }}"
            >
                {{ auth()->check()
                    ? 'Cek Status Reservasi'
                    : 'Masuk untuk Cek Status' }}
                <i class="bi bi-arrow-right" aria-hidden="true"></i>
            </a>
        </aside>
    </div>
@endsection
