@props([
    'status',
])

@php
    $rawStatus = trim((string) $status);
    $statusKey = \Illuminate\Support\Str::lower($rawStatus);

    $variant = match ($statusKey) {
        'terjadwal', 'scheduled' => 'scheduled',
        'diproses', 'processing', 'processed' => 'processing',
        'selesai', 'completed', 'complete' => 'completed',
        'dibatalkan', 'cancelled', 'canceled' => 'cancelled',
        'aktif', 'active' => 'active',
        'nonaktif', 'inactive' => 'inactive',
        default => 'waiting',
    };

    $label = match ($variant) {
        'scheduled' => 'Terjadwal',
        'processing' => 'Diproses',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
        'active' => 'Aktif',
        'inactive' => 'Nonaktif',
        default => $rawStatus !== '' ? $rawStatus : 'Menunggu',
    };

    $legacyClass = match ($variant) {
        'scheduled' => 'is-confirmed',
        'processing' => 'is-process',
        'completed' => 'is-success',
        'cancelled' => 'is-danger',
        default => 'is-waiting',
    };
@endphp

<span
    {{ $attributes->class([
        'history-status-badge',
        'ml-status-badge',
        "ml-status-badge--{$variant}",
        $legacyClass,
    ]) }}
>
    {{ $label }}
</span>