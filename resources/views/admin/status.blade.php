@extends('layouts.admin')

@section('title', 'MediLabs Admin - Cek Status Reservasi')

@section('content')
    <section class="admin-section">
        <div class="admin-heading">
            <h1>Cek Status Reservasi</h1>
            <p>Cari data reservasi pasien berdasarkan kode reservasi</p>
        </div>

        <div class="admin-status-grid">
            <form class="dark-panel admin-search-card" action="{{ route('admin.reservations.status') }}" method="get">
                <label>
                    <span>Cari Kode Reservasi</span>
                    <input type="text" name="code" value="{{ request('code', $reservation->code ?? '') }}">
                </label>
                <button class="button admin-button" type="submit">Cari</button>
            </form>

            <article class="dark-panel admin-patient-card">
                <h2>Informasi Pasien</h2>
                @if ($reservation)
                    <dl>
                        <div><dt>NIK</dt><dd>{{ $reservation->patient->nik ?? '-' }}</dd></div>
                        <div><dt>Nama</dt><dd>{{ $reservation->patient->full_name ?? '-' }}</dd></div>
                        <div><dt>No. Telepon</dt><dd>{{ $reservation->patient->phone ?? '-' }}</dd></div>
                        <div><dt>Email</dt><dd>{{ $reservation->patient->user->email ?? '-' }}</dd></div>
                        <div><dt>Alamat</dt><dd>{{ $reservation->patient->address ?? '-' }}</dd></div>
                    </dl>
                @else
                    <p>Data pasien belum tersedia.</p>
                @endif
            </article>
        </div>

        <article class="dark-panel admin-detail-card">
            <h2>Detail Reservasi</h2>
            @if ($reservation)
                <dl>
                    <div><dt>Kode Reservasi</dt><dd>{{ $reservation->code }}</dd></div>
                    <div><dt>Nama Pasien</dt><dd>{{ $reservation->patient->full_name ?? '-' }}</dd></div>
                    <div><dt>Jenis Tes</dt><dd>{{ $reservation->labTest->name ?? '-' }}</dd></div>
                    <div><dt>Tanggal</dt><dd>{{ optional($reservation->reservation_date)->format('d M Y') }}</dd></div>
                    <div><dt>Jam</dt><dd>{{ substr((string) $reservation->reservation_time, 0, 5) }}</dd></div>
                    <div><dt>Nomor Antrian</dt><dd>{{ $reservation->queue_number }}</dd></div>
                    <div><dt>Status</dt><dd>{{ $reservation->status }}</dd></div>
                </dl>
            @else
                <p>Data reservasi belum tersedia.</p>
            @endif
        </article>

        <div class="admin-action-row">
            <button class="button admin-button" type="button" data-print>Cetak Bukti</button>
            <a class="button admin-button" href="{{ route('admin.reservations.manage') }}">Ubah Status</a>
            <a class="button admin-button" href="{{ route('admin.dashboard') }}">Kembali</a>
        </div>
    </section>
@endsection
