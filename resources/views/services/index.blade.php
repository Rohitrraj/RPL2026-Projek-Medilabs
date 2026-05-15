@extends('layouts.app')

@section('title', 'MediLabs - Daftar Layanan')

@section('content')
    <section class="services-list-page">
        <div class="section-heading">
            <h1>Daftar Layanan Pemeriksaan</h1>
            <p>Pilih layanan pemeriksaan laboratorium sesuai kebutuhan pasien.</p>
        </div>

        <div class="services-list-grid">
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

                <a class="services-list-card" href="{{ route('services.show', $service->slug) }}">
                    <img class="services-list-image" src="{{ asset($image) }}" alt="{{ $service->name }}">

                    <div class="services-list-body">
                        <h2>{{ $service->name }}</h2>
                        <p>{{ $service->description }}</p>

                        <div class="services-list-footer">
                            <strong>Rp{{ number_format($service->price, 0, ',', '.') }}</strong>
                            <span class="button button-primary">Detail</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
@endsection
