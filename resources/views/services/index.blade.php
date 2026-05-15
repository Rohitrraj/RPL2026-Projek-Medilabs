@extends('layouts.app')

@section('title', 'MediLabs - Daftar Layanan')

@section('content')
    <section class="services-index-page">
        <div class="section-heading">
            <h1>Daftar Layanan Pemeriksaan</h1>
            <p>Pilih layanan pemeriksaan laboratorium sesuai kebutuhan pasien.</p>
        </div>

        <div class="services-index-grid">
            @foreach ($services as $service)
                @php
                    $images = [
                        'hematologi-lengkap' => 'assets/images/laypophematologi.jpeg',
                        'gula-darah-puasa' => 'assets/images/laypopguladarah.jpg',
                        'profil-lipid-lengkap' => 'assets/images/laypopkolesterol.jpg',
                        'asam-urat' => 'assets/images/laypopasamurat.png',
                    ];
                    $image = $images[$service->slug] ?? 'assets/images/laypophematologi.jpeg';
                @endphp

                <a
                    class="services-index-card services-index-card-bg"
                    href="{{ route('services.show', $service->slug) }}"
                    style="--service-bg: url('{{ asset($image) }}')"
                >
                    <div class="services-index-overlay">
                        <h2>{{ $service->name }}</h2>
                        <p>{{ $service->description }}</p>

                        <div class="services-index-footer">
                            <strong>Rp{{ number_format($service->price, 0, ',', '.') }}</strong>
                            <span class="button button-primary">Detail</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
@endsection
