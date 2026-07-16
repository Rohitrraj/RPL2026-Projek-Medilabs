@props([
    'service',
    'variant' => 'card',
])

@php
    $serviceImages = [
        'hematologi-lengkap' => [
            'card' => 'assets/images/laypophematologi.jpeg',
            'detail' => 'assets/images/laypophematologipage.jpg',
            'admin' => 'assets/images/laypophematologi.jpeg',
        ],
        'gula-darah-puasa' => [
            'card' => 'assets/images/laypopguladarah.jpg',
            'detail' => 'assets/images/laypopguladarah.jpg',
            'admin' => 'assets/images/laypopguladarah.jpg',
        ],
        'profil-lipid-lengkap' => [
            'card' => 'assets/images/laypopkolesterol.jpg',
            'detail' => 'assets/images/laypopkolesterol.jpg',
            'admin' => 'assets/images/laypopkolesterol.jpg',
        ],
        'asam-urat' => [
            'card' => 'assets/images/laypopasamurat.png',
            'detail' => 'assets/images/laypopasamurat.png',
            'admin' => 'assets/images/laypopasamurat.png',
        ],
    ];

    $serviceImage = $serviceImages[$service->slug][$variant]
        ?? $serviceImages[$service->slug]['card']
        ?? null;

    $isAdminVariant = $variant === 'admin';
@endphp

@if ($serviceImage)
    <img
        src="{{ asset($serviceImage) }}"
        alt="Ilustrasi layanan {{ $service->name }}"
        {{ $attributes }}
    >
@else
    <div
        {{ $attributes->class([
            'ml-service-visual-fallback' => ! $isAdminVariant,
            'admin-service-visual-fallback' => $isAdminVariant,
        ]) }}
        role="img"
        aria-label="Ilustrasi generik layanan {{ $service->name }}"
    >
        <i class="bi bi-activity" aria-hidden="true"></i>
        <span>{{ $isAdminVariant ? 'Default' : 'Layanan Laboratorium' }}</span>
    </div>
@endif
