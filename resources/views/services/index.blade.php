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
                <a
                    class="dark-panel services-index-card"
                    href="{{ $service['route'] }}"
                    style="--service-bg: url('{{ asset($service['image']) }}')"
                >
                    <div class="services-index-content">
                        <h2>{{ $service['title'] }}</h2>
                        <p>{{ $service['text'] }}</p>

                        <div class="services-index-footer">
                            <strong>{{ $service['price'] }}</strong>
                            <span class="button button-primary">Detail</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
@endsection
