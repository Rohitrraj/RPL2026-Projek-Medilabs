@extends('layouts.app')

@section('title', 'MediLabs - Cek Status Reservasi')

@section('content')
    <section class="status-page-fixed">
        <div class="status-top-grid">
            <form class="dark-panel status-form-fixed" action="{{ route('reservations.status') }}" method="get">
                <h1>Cek Status Reservasi</h1>
                <p>Masukkan kode reservasi untuk melihat status pemeriksaan anda.</p>

                <div class="status-form-row">
                    <label for="code">Kode Reservasi</label>
                    <input id="code" type="text" name="code" value="{{ request('code') }}" placeholder="Masukkan kode reservasi">
                </div>

                <div class="status-form-row">
                    <label for="phone">No. Telepon</label>
                    <input id="phone" type="tel" name="phone" placeholder="08xxxxxxxxxx">
                </div>

                <button class="button button-primary status-submit" type="submit">Cek Status</button>
            </form>

            <aside class="status-info-fixed">
                <h2>Informasi Reservasi</h2>
                <p>Kode reservasi didapat setelah pasien berhasil melakukan reservasi.</p>
                <p>Pastikan kode yang dimasukkan sudah benar agar informasi reservasi dapat ditemukan.</p>
            </aside>
        </div>

        <article class="dark-panel status-detail-fixed">
            <h2>Detail Reservasi</h2>

            @if ($reservation)
                <dl>
                    @foreach ($reservationData as $label => $value)
                        <div>
                            <dt>{{ $label }}</dt>
                            <dd>{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>

                <div class="status-action-row">
                    <button class="button button-secondary" type="button" data-print>Cetak Bukti</button>
                    <a class="button button-primary" href="{{ route('reservations.history') }}">Lihat Riwayat</a>
                    <a class="button button-dark" href="{{ route('home') }}">Kembali</a>
                </div>
            @else
                <p>Belum ada data reservasi yang dapat ditampilkan.</p>
            @endif
        </article>
    </section>
@endsection
