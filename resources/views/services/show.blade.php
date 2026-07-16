@extends('layouts.app')

@section('title', $service->name . ' - MediLabs')

@section('content')
    @php
        $serviceImages = [
            'hematologi-lengkap' => 'assets/images/laypophematologipage.jpg',
            'gula-darah-puasa' => 'assets/images/laypopguladarah.jpg',
            'profil-lipid-lengkap' => 'assets/images/laypopkolesterol.jpg',
            'asam-urat' => 'assets/images/laypopasamurat.png',
        ];

        $serviceImage = $serviceImages[$service->slug]
            ?? 'assets/images/laypophematologipage.jpg';
    @endphp

    <section class="ml-service-detail">
        <nav aria-label="Breadcrumb">
            <ol class="ml-public-breadcrumb">
                <li><a href="{{ route('home') }}">Beranda</a></li>
                <li><i class="bi bi-chevron-right" aria-hidden="true"></i></li>
                <li><a href="{{ route('services.index') }}">Layanan</a></li>
                <li><i class="bi bi-chevron-right" aria-hidden="true"></i></li>
                <li aria-current="page">{{ $service->name }}</li>
            </ol>
        </nav>

        <article class="ml-service-detail-hero">
            <div class="ml-service-detail-hero__content">
                <span class="ml-public-eyebrow">
                    <i class="bi bi-check-circle" aria-hidden="true"></i>
                    Layanan aktif
                </span>

                <h1 class="ml-service-detail-hero__title">
                    {{ $service->name }}
                </h1>

                <p class="ml-service-detail-hero__description">
                    {{ $service->description
                        ?: 'Layanan pemeriksaan laboratorium MediLabs.' }}
                </p>

                <div class="ml-service-detail-hero__meta">
                    <strong class="ml-service-price">
                        Rp {{ number_format((float) $service->price, 0, ',', '.') }}
                    </strong>

                    <x-status-badge status="Aktif" />
                </div>

                <div class="ml-public-inline-actions">
                    <a
                        class="ml-public-button ml-public-button--primary"
                        href="{{ route('reservations.create', ['service' => $service->id]) }}"
                    >
                        <i class="bi bi-calendar2-plus" aria-hidden="true"></i>
                        Reservasi Layanan Ini
                    </a>

                    <a
                        class="ml-public-button ml-public-button--outline"
                        href="{{ route('services.index') }}"
                    >
                        Lihat Layanan Lain
                    </a>
                </div>
            </div>

            <div class="ml-service-detail-hero__media">
                <img
                    src="{{ asset($serviceImage) }}"
                    alt="Ilustrasi pemeriksaan {{ $service->name }}"
                >
            </div>
        </article>

        <div class="ml-service-info-grid">
            <article class="ml-service-info-card">
                <span class="ml-public-icon-box" aria-hidden="true">
                    <i class="bi bi-heart-pulse"></i>
                </span>

                <div>
                    <h2>Manfaat Pemeriksaan</h2>
                    <p>
                        {{ $service->benefit
                            ?: 'Membantu memperoleh informasi kesehatan melalui pemeriksaan laboratorium.' }}
                    </p>
                </div>
            </article>

            <article class="ml-service-info-card">
                <span class="ml-public-icon-box" aria-hidden="true">
                    <i class="bi bi-clipboard2-check"></i>
                </span>

                <div>
                    <h2>Persiapan Sebelum Pemeriksaan</h2>
                    <p>
                        {{ $service->preparation
                            ?: 'Ikuti arahan petugas sebelum pemeriksaan dilakukan.' }}
                    </p>
                </div>
            </article>

            <article class="ml-service-info-card">
                <span class="ml-public-icon-box" aria-hidden="true">
                    <i class="bi bi-clock"></i>
                </span>

                <div>
                    <h2>Jam Reservasi</h2>
                    <p>
                        Pilihan waktu reservasi tersedia mulai pukul
                        07.00 sampai 19.00 WIB.
                    </p>
                </div>
            </article>

            <article class="ml-service-info-card">
                <span class="ml-public-icon-box" aria-hidden="true">
                    <i class="bi bi-info-circle"></i>
                </span>

                <div>
                    <h2>Informasi Penting</h2>
                    <p>
                        Pilih tanggal dan jam yang tersedia melalui form reservasi.
                        Detail pemeriksaan dapat dikonfirmasi kembali kepada petugas.
                    </p>
                </div>
            </article>
        </div>

        <aside class="ml-service-cta">
            <div>
                <h2>Siap membuat reservasi?</h2>
                <p>
                    Lengkapi data pasien, pilih jadwal, lalu simpan reservasi Anda.
                </p>
            </div>

            <a
                class="ml-public-button ml-public-button--primary"
                href="{{ route('reservations.create', ['service' => $service->id]) }}"
            >
                Mulai Reservasi
                <i class="bi bi-arrow-right" aria-hidden="true"></i>
            </a>
        </aside>
    </section>
@endsection
