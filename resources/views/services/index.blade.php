@extends('layouts.app')

@section('title', 'MediLabs - Layanan')

@section('content')
    <section class="services-index-page">
        <x-page-header
            title="Daftar Layanan Pemeriksaan"
            description="Pilih layanan pemeriksaan laboratorium sesuai kebutuhan pasien."
            wrapper-class="section-heading"
        />

        <div class="services-index-grid">
            @forelse ($services as $service)
                <a
                    href="{{ route('services.show', $service->slug) }}"
                    class="services-index-card"
                    style="--service-bg: url('{{ asset(match ($service->slug) {
                        'hematologi-lengkap' => 'assets/images/laypophematologi.jpeg',
                        'gula-darah-puasa' => 'assets/images/laypopguladarah.jpg',
                        'profil-lipid-lengkap' => 'assets/images/laypopkolesterol.jpg',
                        'asam-urat' => 'assets/images/laypopasamurat.png',
                        default => 'assets/images/laypophematologi.jpeg',
                    }) }}');"
                >
                    <div class="services-index-content">
                        <h2>{{ $service->name }}</h2>
                        <p>{{ $service->description ?? 'Layanan pemeriksaan laboratorium MediLabs.' }}</p>

                        <div class="services-index-footer">
                            <strong>Rp {{ number_format((float) $service->price, 0, ',', '.') }}</strong>
                            <span class="button button-primary">Detail</span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="dark-panel" style="padding: 24px;">
                    <p>Belum ada layanan yang tersedia.</p>
                </div>
            @endforelse
        </div>
    </section>
@endsection