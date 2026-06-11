@props(['status'])

@php
    $statusClass = match ($status) {
        'Selesai' => 'is-success',
        'Dibatalkan' => 'is-danger',
        'Diproses' => 'is-process',
        'Terjadwal' => 'is-confirmed',
        default => 'is-waiting',
    };
@endphp

<span class="history-status-badge {{ $statusClass }}">
    {{ $status }}
</span>
