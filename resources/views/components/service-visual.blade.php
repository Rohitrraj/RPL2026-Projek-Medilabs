@props([
    'service',
    'variant' => 'card',
])

@php
    $serviceImages = config('service_images', []);

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
