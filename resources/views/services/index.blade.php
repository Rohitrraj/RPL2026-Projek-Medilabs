@extends('layouts.app')

@section('title', 'Layanan Laboratorium - MediLabs')

@section('content')
    @php
        $serviceImages = [
            'hematologi-lengkap' => 'assets/images/laypophematologi.jpeg',
            'gula-darah-puasa' => 'assets/images/laypopguladarah.jpg',
            'profil-lipid-lengkap' => 'assets/images/laypopkolesterol.jpg',
            'asam-urat' => 'assets/images/laypopasamurat.png',
        ];
    @endphp

    <section class="ml-public-page">
        <header class="ml-public-page-header">
            <div class="ml-public-page-header__copy">
                <span class="ml-public-eyebrow">
                    <i class="bi bi-activity" aria-hidden="true"></i>
                    Layanan laboratorium
                </span>

                <h1 class="ml-public-page-title">Pilih Pemeriksaan yang Dibutuhkan</h1>

                <p class="ml-public-page-description">
                    Seluruh layanan yang tampil sedang aktif dan dapat dipilih
                    pada proses reservasi MediLabs.
                </p>
            </div>

            <div class="ml-public-page-actions">
                <a
                    class="ml-public-button ml-public-button--outline"
                    href="{{ route('reservations.status') }}"
                >
                    <i class="bi bi-search" aria-hidden="true"></i>
                    Cek Status
                </a>

                <a
                    class="ml-public-button ml-public-button--primary"
                    href="{{ route('reservations.create') }}"
                >
                    <i class="bi bi-calendar2-plus" aria-hidden="true"></i>
                    Buat Reservasi
                </a>
            </div>
        </header>

        @if ($services->isEmpty())
            <div class="ml-public-empty-state">
                <span class="ml-public-empty-state__icon" aria-hidden="true">
                    <i class="bi bi-activity"></i>
                </span>

                <h2>Belum ada layanan aktif</h2>
                <p>
                    Layanan laboratorium akan ditampilkan kembali setelah tersedia.
                </p>
            </div>
        @else
            <div class="ml-services-grid">
                @foreach ($services as $service)
                    @php
                        $serviceImage = $serviceImages[$service->slug]
                            ?? 'assets/images/laypophematologi.jpeg';
                    @endphp

                    <article class="ml-service-card">
                        <div class="ml-service-card__media">
                            <img
                                src="{{ asset($serviceImage) }}"
                                alt="Ilustrasi layanan {{ $service->name }}"
                            >

                            <span class="ml-service-card__availability">
                                <i class="bi bi-check-circle" aria-hidden="true"></i>
                                Tersedia
                            </span>
                        </div>

                        <div class="ml-service-card__body">
                            <h2 class="ml-service-card__title">
                                {{ $service->name }}
                            </h2>

                            <p class="ml-service-card__description">
                                {{ $service->description
                                    ?: 'Layanan pemeriksaan laboratorium MediLabs.' }}
                            </p>
                        </div>

                        <footer class="ml-service-card__footer">
                            <strong class="ml-service-price">
                                Rp {{ number_format((float) $service->price, 0, ',', '.') }}
                            </strong>

                            <a
                                class="ml-public-button ml-public-button--outline ml-public-button--sm"
                                href="{{ route('services.show', $service->slug) }}"
                            >
                                Lihat Detail
                                <i class="bi bi-arrow-right" aria-hidden="true"></i>
                            </a>
                        </footer>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
@endsection
