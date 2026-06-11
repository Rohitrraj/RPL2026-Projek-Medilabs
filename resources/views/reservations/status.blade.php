@extends('layouts.app')

@section('title', 'MediLabs - Cek Status Reservasi')

@section('content')
    <section class="status-page-fixed">
        <div class="status-top-grid no-print">
            <form class="dark-panel status-form-fixed" action="{{ route('reservations.status') }}" method="GET">
                <h1>Cek Status Reservasi</h1>
                <p>Masukkan kode reservasi untuk melihat status pemeriksaan anda.</p>

                <div class="status-form-row">
                    <label for="code">Kode Reservasi</label>
                    <input
                        id="code"
                        type="text"
                        name="code"
                        value="{{ request('code') }}"
                        placeholder="Masukkan kode reservasi"
                    >
                </div>

                <div class="status-form-row">
                    <label for="phone">No. Telepon</label>
                    <input
                        id="phone"
                        type="tel"
                        name="phone"
                        placeholder="08xxxxxxxxxx"
                    >
                </div>

                <button class="button button-primary status-submit" type="submit">
                    Cek Status
                </button>
            </form>

            <aside class="status-info-fixed">
                <h2>Informasi Reservasi</h2>
                <p>Kode reservasi didapat setelah pasien berhasil melakukan reservasi.</p>
                <p>Pastikan kode yang dimasukkan sudah benar agar informasi reservasi dapat ditemukan.</p>
            </aside>
        </div>

        @if ($reservation)
            <article class="dark-panel status-detail-fixed print-card">
                <div class="print-header">
                    <div class="print-heading-block">
                        <h2>Detail Reservasi</h2>
                        <p>Bukti reservasi pemeriksaan laboratorium MediLabs.</p>
                    </div>

                    <div class="status-action-row no-print">
                        <button class="button button-secondary" type="button" onclick="window.print()">
                            Cetak Bukti
                        </button>
                        <a class="button button-primary" href="{{ route('reservations.history') }}">
                            Lihat Riwayat
                        </a>
                        <a class="button button-dark" href="{{ route('home') }}">
                            Kembali
                        </a>
                    </div>
                </div>

                <div class="print-body">
                    <div class="print-checkmark" aria-hidden="true">✓</div>

                    <dl>
                        @foreach ($reservationData as $label => $value)
                            <div>
                                <dt>{{ $label }}</dt>
                                <dd>{{ $value }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </div>

                <div class="print-footer">
                    <p>Harap datang 15 menit sebelum jadwal pemeriksaan.</p>
                    <p>Simpan bukti ini untuk verifikasi di laboratorium.</p>
                </div>
            </article>
        @else
            <article class="dark-panel status-empty-fixed">
                <h2>Detail Reservasi</h2>
                <p>Belum ada data reservasi yang dapat ditampilkan.</p>
            </article>
        @endif
    </section>
@endsection