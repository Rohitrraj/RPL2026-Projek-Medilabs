@extends('layouts.app')

@section('title', 'Hasil Reservasi | MediLabs')

@section('content')
    <section class="ml-public-page">
        <header class="ml-public-page-header no-print">
            <div class="ml-public-page-header__copy">
                <span class="ml-public-eyebrow">
                    <i class="bi bi-check-circle" aria-hidden="true"></i>
                    Reservasi berhasil
                </span>

                <h1 class="ml-public-page-title">Hasil Reservasi</h1>

                <p class="ml-public-page-description">
                    Simpan kode reservasi dan periksa kembali jadwal pemeriksaan Anda.
                </p>
            </div>

            <div class="ml-public-page-actions">
                <button
                    class="ml-public-button ml-public-button--outline"
                    type="button"
                    data-print
                >
                    <i class="bi bi-printer" aria-hidden="true"></i>
                    Cetak Bukti
                </button>

                <a
                    class="ml-public-button ml-public-button--primary"
                    href="{{ route('reservations.history') }}"
                >
                    <i class="bi bi-clock-history" aria-hidden="true"></i>
                    Lihat Riwayat
                </a>
            </div>
        </header>

        <div class="ml-result-hero">
            <span class="ml-result-hero__icon" aria-hidden="true">
                <i class="bi bi-check-lg"></i>
            </span>

            <div>
                <h1>Reservasi berhasil dibuat</h1>
                <p>
                    Status awal reservasi Anda adalah Menunggu sampai diproses admin.
                </p>
            </div>

            <x-status-badge :status="$reservation->status" />
        </div>

        <div class="ml-reservation-code">
            <div class="ml-reservation-code__copy">
                <span>Kode reservasi</span>
                <strong id="reservation-code">{{ $reservation->code }}</strong>
            </div>

            <div class="ml-public-inline-actions no-print">
                <span class="ml-copy-feedback" data-copy-feedback hidden>
                    Kode disalin
                </span>

                <button
                    class="ml-public-button ml-public-button--outline"
                    type="button"
                    data-copy-target="#reservation-code"
                >
                    <i class="bi bi-copy" aria-hidden="true"></i>
                    Salin Kode
                </button>
            </div>
        </div>

        <div class="ml-result-grid">
            <article class="ml-public-card print-card">
                <header class="ml-public-card__header">
                    <div class="ml-public-card__title-wrap">
                        <h2 class="ml-public-card__title">Detail Pemeriksaan</h2>
                        <p class="ml-public-card__description">
                            Informasi reservasi yang tersimpan pada sistem MediLabs.
                        </p>
                    </div>
                </header>

                <div class="ml-public-card__body">
                    <dl class="ml-public-definition-list">
                        <div>
                            <dt>Kode Reservasi</dt>
                            <dd>{{ $reservation->code }}</dd>
                        </div>
                        <div>
                            <dt>Nama Pasien</dt>
                            <dd>{{ $reservation->patient->full_name ?? '-' }}</dd>
                        </div>
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
                        <div>
                            <dt>Catatan</dt>
                            <dd>{{ $reservation->notes ?: 'Tidak ada catatan.' }}</dd>
                        </div>
                    </dl>
                </div>
            </article>

            <aside class="ml-public-card">
                <header class="ml-public-card__header">
                    <div class="ml-public-card__title-wrap">
                        <h2 class="ml-public-card__title">Persiapan Kedatangan</h2>
                        <p class="ml-public-card__description">
                            Ikuti panduan berikut sebelum datang ke laboratorium.
                        </p>
                    </div>
                </header>

                <div class="ml-public-card__body">
                    <ul class="ml-result-instructions">
                        <li>
                            <i class="bi bi-clock" aria-hidden="true"></i>
                            <span>Datang sekitar 15 menit sebelum jadwal pemeriksaan.</span>
                        </li>
                        <li>
                            <i class="bi bi-ticket-perforated" aria-hidden="true"></i>
                            <span>Simpan kode reservasi untuk proses verifikasi.</span>
                        </li>
                        <li>
                            <i class="bi bi-clipboard2-check" aria-hidden="true"></i>
                            <span>Ikuti persiapan pemeriksaan sesuai layanan yang dipilih.</span>
                        </li>
                        <li>
                            <i class="bi bi-search" aria-hidden="true"></i>
                            <span>Pantau perubahan status melalui halaman Cek Status.</span>
                        </li>
                    </ul>
                </div>

                <footer class="ml-public-card__footer no-print">
                    <a
                        class="ml-public-button ml-public-button--outline"
                        href="{{ route('reservations.status', ['code' => $reservation->code]) }}"
                    >
                        Cek Status
                    </a>

                    <a
                        class="ml-public-button ml-public-button--primary"
                        href="{{ route('reservations.create') }}"
                    >
                        Reservasi Baru
                    </a>
                </footer>
            </aside>
        </div>
    </section>
@endsection
