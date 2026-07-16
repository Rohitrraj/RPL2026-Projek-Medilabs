@extends('layouts.app')

@section('title', 'Riwayat Reservasi | MediLabs')

@section('content')
    @php
        $activeCount = $reservations
            ->whereIn('status', ['Menunggu', 'Terjadwal', 'Diproses'])
            ->count();
        $completedCount = $reservations->where('status', 'Selesai')->count();
        $waitingCount = $reservations->where('status', 'Menunggu')->count();
        $cancelledCount = $reservations->where('status', 'Dibatalkan')->count();
    @endphp

    <section class="ml-public-page">
        <header class="ml-public-page-header">
            <div class="ml-public-page-header__copy">
                <span class="ml-public-eyebrow">
                    <i class="bi bi-clock-history" aria-hidden="true"></i>
                    Aktivitas pasien
                </span>

                <h1 class="ml-public-page-title">Riwayat Reservasi</h1>

                <p class="ml-public-page-description">
                    Lihat reservasi yang pernah dibuat, periksa detail, dan batalkan
                    reservasi yang masih memenuhi ketentuan.
                </p>
            </div>

            <div class="ml-public-page-actions">
                <a
                    class="ml-public-button ml-public-button--primary"
                    href="{{ route('reservations.create') }}"
                >
                    <i class="bi bi-calendar2-plus" aria-hidden="true"></i>
                    Buat Reservasi
                </a>
            </div>
        </header>

        <div class="ml-history-metrics" aria-label="Ringkasan reservasi">
            <article class="ml-history-metric">
                <span class="ml-history-metric__icon" aria-hidden="true">
                    <i class="bi bi-activity"></i>
                </span>
                <span class="ml-history-metric__copy">
                    <strong>{{ $activeCount }}</strong>
                    <span>Reservasi aktif</span>
                </span>
            </article>

            <article class="ml-history-metric">
                <span class="ml-history-metric__icon" aria-hidden="true">
                    <i class="bi bi-hourglass-split"></i>
                </span>
                <span class="ml-history-metric__copy">
                    <strong>{{ $waitingCount }}</strong>
                    <span>Menunggu</span>
                </span>
            </article>

            <article class="ml-history-metric">
                <span class="ml-history-metric__icon" aria-hidden="true">
                    <i class="bi bi-check2-circle"></i>
                </span>
                <span class="ml-history-metric__copy">
                    <strong>{{ $completedCount }}</strong>
                    <span>Selesai</span>
                </span>
            </article>

            <article class="ml-history-metric">
                <span class="ml-history-metric__icon" aria-hidden="true">
                    <i class="bi bi-x-circle"></i>
                </span>
                <span class="ml-history-metric__copy">
                    <strong>{{ $cancelledCount }}</strong>
                    <span>Dibatalkan</span>
                </span>
            </article>
        </div>

        <form
            class="ml-history-filter"
            action="{{ route('reservations.history') }}"
            method="GET"
        >
            <div class="ml-public-field">
                <label class="ml-public-label" for="history-code">
                    Cari Kode Reservasi
                </label>
                <input
                    id="history-code"
                    class="ml-public-input"
                    type="search"
                    name="code"
                    value="{{ request('code') }}"
                    placeholder="Contoh: A001"
                    autocomplete="off"
                >
            </div>

            <div class="ml-history-filter__actions">
                @if (request()->filled('code'))
                    <a
                        class="ml-public-button ml-public-button--outline"
                        href="{{ route('reservations.history') }}"
                    >
                        Reset
                    </a>
                @endif

                <button
                    class="ml-public-button ml-public-button--primary"
                    type="submit"
                >
                    <i class="bi bi-search" aria-hidden="true"></i>
                    Cari
                </button>
            </div>
        </form>

        @if ($reservations->isEmpty())
            <div class="ml-public-empty-state">
                <span class="ml-public-empty-state__icon" aria-hidden="true">
                    <i class="bi bi-calendar2-x"></i>
                </span>

                <h2>
                    {{ request()->filled('code')
                        ? 'Reservasi tidak ditemukan'
                        : 'Belum ada riwayat reservasi' }}
                </h2>

                <p>
                    {{ request()->filled('code')
                        ? 'Tidak ada reservasi pada akun Anda yang sesuai dengan kode tersebut.'
                        : 'Reservasi yang dibuat akan tampil pada halaman ini.' }}
                </p>

                <div class="ml-public-inline-actions">
                    @if (request()->filled('code'))
                        <a
                            class="ml-public-button ml-public-button--outline"
                            href="{{ route('reservations.history') }}"
                        >
                            Lihat Semua Riwayat
                        </a>
                    @endif

                    <a
                        class="ml-public-button ml-public-button--primary"
                        href="{{ route('reservations.create') }}"
                    >
                        Buat Reservasi
                    </a>
                </div>
            </div>
        @else
            <div class="ml-public-table-wrap">
                <table class="ml-public-table">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Layanan</th>
                            <th>Jadwal</th>
                            <th>Antrean</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reservations as $item)
                            <tr>
                                <td>
                                    <span class="ml-public-table__primary">
                                        {{ $item->code }}
                                    </span>
                                </td>
                                <td>{{ $item->labTest->name ?? '-' }}</td>
                                <td>
                                    <span class="ml-public-table__primary">
                                        {{ optional($item->reservation_date)->format('d M Y') }}
                                    </span>
                                    <span class="ml-public-table__secondary">
                                        {{ substr((string) $item->reservation_time, 0, 5) }} WIB
                                    </span>
                                </td>
                                <td>{{ $item->queue_number ?: '-' }}</td>
                                <td>
                                    <x-status-badge :status="$item->status" />
                                </td>
                                <td>
                                    <div class="ml-history-actions">
                                        <a
                                            class="ml-public-button ml-public-button--outline ml-public-button--sm"
                                            href="{{ route('reservations.result', $item) }}"
                                        >
                                            Detail
                                        </a>

                                        @if (in_array($item->status, ['Menunggu', 'Terjadwal'], true))
                                            <form
                                                action="{{ route('reservations.cancel', $item) }}"
                                                method="POST"
                                                data-confirm-form
                                                data-confirm-message="Batalkan reservasi {{ $item->code }}? Data reservasi akan tetap tersimpan."
                                            >
                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    class="ml-public-button ml-public-button--danger ml-public-button--sm"
                                                    type="submit"
                                                >
                                                    Batalkan
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="ml-public-notice">
            <i class="bi bi-info-circle ml-public-notice__icon" aria-hidden="true"></i>
            <span>
                Reservasi dapat dibatalkan ketika berstatus Menunggu atau Terjadwal.
                Data yang dibatalkan tetap tersimpan dan dapat dilihat oleh pasien serta administrator.
            </span>
        </div>
    </section>
@endsection
