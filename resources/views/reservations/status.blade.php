@extends('layouts.app')

@section('title', 'Cek Status Reservasi | MediLabs')

@section('content')
    @php
        $searched = request()->filled('code');
        $requestedCode = strtoupper(trim((string) request('code', '')));
        $displayedCode = strtoupper(trim((string) ($reservation->code ?? '')));
        $showingLatestFallback = $searched
            && $reservation
            && $requestedCode !== $displayedCode;
        $searchNotFound = $searched && ! $reservation;
        $isLatestWithoutSearch = ! $searched && $reservation && auth()->check();
    @endphp

    <section class="ml-public-page">
        <header class="ml-public-page-header">
            <div class="ml-public-page-header__copy">
                <span class="ml-public-eyebrow">
                    <i class="bi bi-search" aria-hidden="true"></i>
                    Pelacakan reservasi
                </span>

                <h1 class="ml-public-page-title">Cek Status Reservasi</h1>

                <p class="ml-public-page-description">
                    Masukkan kode reservasi untuk melihat jadwal dan status
                    pemeriksaan yang tersimpan pada MediLabs.
                </p>
            </div>
        </header>

        <div class="ml-status-layout">
            <article class="ml-public-card ml-status-search-card">
                <div class="ml-status-search-card__hero">
                    <h1>Temukan reservasi Anda</h1>
                    <p>
                        Kode reservasi diberikan setelah proses reservasi berhasil.
                        Contoh format kode: A001.
                    </p>
                </div>

                <form
                    class="ml-public-form"
                    action="{{ route('reservations.status') }}"
                    method="GET"
                >
                    <div class="ml-public-card__body">
                        <div class="ml-public-field">
                            <label class="ml-public-label" for="status-code">
                                Kode Reservasi
                            </label>

                            <input
                                id="status-code"
                                class="ml-public-input"
                                type="search"
                                name="code"
                                value="{{ request('code') }}"
                                placeholder="Contoh: A001"
                                autocomplete="off"
                                autocapitalize="characters"
                                required
                            >

                            <p class="ml-public-help-text">
                                Masukkan kode tanpa nomor telepon atau data pribadi lain.
                            </p>
                        </div>
                    </div>

                    <footer class="ml-public-card__footer">
                        @if ($searched)
                            <a
                                class="ml-public-button ml-public-button--outline"
                                href="{{ route('reservations.status') }}"
                            >
                                Reset
                            </a>
                        @endif

                        <button
                            class="ml-public-button ml-public-button--primary"
                            type="submit"
                        >
                            <i class="bi bi-search" aria-hidden="true"></i>
                            Cek Status
                        </button>
                    </footer>
                </form>
            </article>

            <aside class="ml-public-card">
                <header class="ml-public-card__header">
                    <div class="ml-public-card__title-wrap">
                        <h2 class="ml-public-card__title">Cara Mengecek Status</h2>
                        <p class="ml-public-card__description">
                            Gunakan kode yang tercantum pada hasil reservasi.
                        </p>
                    </div>
                </header>

                <div class="ml-public-card__body">
                    <ol class="ml-status-guide">
                        <li>
                            <span class="ml-status-guide__number">1</span>
                            <span>Salin atau ketik kode reservasi Anda.</span>
                        </li>
                        <li>
                            <span class="ml-status-guide__number">2</span>
                            <span>Tekan tombol Cek Status.</span>
                        </li>
                        <li>
                            <span class="ml-status-guide__number">3</span>
                            <span>Periksa jadwal, antrean, dan status terbaru.</span>
                        </li>
                    </ol>
                </div>
            </aside>
        </div>

        @if ($showingLatestFallback)
            <div class="ml-public-notice ml-public-notice--warning" role="status">
                <i class="bi bi-exclamation-triangle ml-public-notice__icon" aria-hidden="true"></i>
                <span>
                    Kode <strong>{{ $requestedCode }}</strong> tidak ditemukan.
                    Karena Anda sedang masuk, sistem menampilkan reservasi terbaru
                    pada akun Anda sebagai referensi.
                </span>
            </div>
        @endif

        @if ($reservation)
            <article class="ml-public-card ml-status-result print-card">
                <header class="ml-public-card__header">
                    <div class="ml-public-card__title-wrap">
                        <span class="ml-public-eyebrow">
                            <i class="bi bi-clipboard2-check" aria-hidden="true"></i>
                            {{ $showingLatestFallback || $isLatestWithoutSearch
                                ? 'Reservasi terbaru akun Anda'
                                : 'Hasil pencarian' }}
                        </span>

                        <h2 class="ml-public-card__title">
                            Detail Reservasi {{ $reservation->code }}
                        </h2>

                        <p class="ml-public-card__description">
                            Informasi jadwal dan status yang tersimpan pada sistem.
                        </p>
                    </div>

                    <x-status-badge :status="$reservation->status" />
                </header>

                <div class="ml-public-card__body">
                    <div class="ml-reservation-code">
                        <div class="ml-reservation-code__copy">
                            <span>Kode reservasi</span>
                            <strong id="status-reservation-code">
                                {{ $reservation->code }}
                            </strong>
                        </div>

                        <div class="ml-public-inline-actions no-print">
                            <span class="ml-copy-feedback" data-copy-feedback hidden>
                                Kode disalin
                            </span>

                            <button
                                class="ml-public-button ml-public-button--outline"
                                type="button"
                                data-copy-target="#status-reservation-code"
                            >
                                <i class="bi bi-copy" aria-hidden="true"></i>
                                Salin Kode
                            </button>
                        </div>
                    </div>

                    <dl class="ml-public-definition-list">
                        @auth
                            <div>
                                <dt>Nama Pasien</dt>
                                <dd>{{ $reservation->patient->full_name ?? '-' }}</dd>
                            </div>
                        @endauth
                        <div>
                            <dt>Jenis Tes</dt>
                            <dd>{{ $reservation->labTest->name ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt>Tanggal</dt>
                            <dd>{{ optional($reservation->reservation_date)->format('d M Y') }}</dd>
                        </div>
                        <div>
                            <dt>Jam</dt>
                            <dd>{{ substr((string) $reservation->reservation_time, 0, 5) }} WIB</dd>
                        </div>
                        <div>
                            <dt>Nomor Antrean</dt>
                            <dd>{{ $reservation->queue_number ?: '-' }}</dd>
                        </div>
                        <div>
                            <dt>Status</dt>
                            <dd><x-status-badge :status="$reservation->status" /></dd>
                        </div>
                    </dl>
                </div>

                <footer class="ml-public-card__footer no-print">
                    @auth
                        @if (auth()->user()->role !== 'admin')
                            <a
                                class="ml-public-button ml-public-button--outline"
                                href="{{ route('reservations.result', $reservation) }}"
                            >
                                Lihat Detail
                            </a>

                            <a
                                class="ml-public-button ml-public-button--primary"
                                href="{{ route('reservations.history') }}"
                            >
                                Lihat Riwayat
                            </a>
                        @else
                            <a
                                class="ml-public-button ml-public-button--primary"
                                href="{{ route('admin.dashboard') }}"
                            >
                                Dashboard Admin
                            </a>
                        @endif
                    @else
                        <a
                            class="ml-public-button ml-public-button--outline"
                            href="{{ route('services.index') }}"
                        >
                            Lihat Layanan
                        </a>

                        <a
                            class="ml-public-button ml-public-button--primary"
                            href="{{ route('login') }}"
                        >
                            Masuk ke Akun
                        </a>
                    @endauth
                </footer>
            </article>
        @elseif ($searchNotFound)
            <div class="ml-public-empty-state">
                <span class="ml-public-empty-state__icon" aria-hidden="true">
                    <i class="bi bi-search"></i>
                </span>

                <h2>Kode reservasi tidak ditemukan</h2>
                <p>
                    Tidak ada reservasi dengan kode {{ $requestedCode }}.
                    Periksa kembali penulisan kode dan lakukan pencarian ulang.
                </p>

                <a
                    class="ml-public-button ml-public-button--outline"
                    href="{{ route('reservations.status') }}"
                >
                    Cari Kode Lain
                </a>
            </div>
        @else
            <div class="ml-public-empty-state">
                <span class="ml-public-empty-state__icon" aria-hidden="true">
                    <i class="bi bi-clipboard2-pulse"></i>
                </span>

                <h2>Belum ada reservasi yang ditampilkan</h2>
                <p>
                    Masukkan kode reservasi pada form di atas untuk melihat status.
                </p>
            </div>
        @endif
    </section>
@endsection
