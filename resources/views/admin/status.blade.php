@extends('layouts.admin')

@section('title', 'Admin Cek Status - MediLabs')

@section('content')
    <section class="admin-page-header">
        <div>
            <p class="admin-eyebrow">Cek Reservasi</p>
            <h1>Cek Status Reservasi</h1>
            <p>Cari reservasi berdasarkan kode untuk melihat detail dan status terbaru.</p>
        </div>
    </section>

    <section class="admin-card admin-filter-card">
        <form method="GET" action="{{ route('admin.reservations.status') }}" class="admin-filter-form compact">
            <label>
                Kode Reservasi
                <input type="text" name="code" value="{{ request('code') }}" placeholder="Contoh: A01000" required>
            </label>
            <button type="submit">Cari Reservasi</button>
        </form>
    </section>

    @if ($reservation)
        <section class="admin-detail-grid two-column">
            <article class="admin-card">
                <h2>Detail Reservasi</h2>
                <dl class="admin-detail-list">
                    <div><dt>Kode Reservasi</dt><dd>{{ $reservation->code }}</dd></div>
                    <div><dt>Nama Pasien</dt><dd>{{ $reservation->patient->full_name ?? '-' }}</dd></div>
                    <div><dt>Jenis Tes</dt><dd>{{ $reservation->labTest->name ?? '-' }}</dd></div>
                    <div><dt>Tanggal</dt><dd>{{ optional($reservation->reservation_date)->format('d M Y') }}</dd></div>
                    <div><dt>Jam</dt><dd>{{ substr((string) $reservation->reservation_time, 0, 5) }}</dd></div>
                    <div><dt>Nomor Antrean</dt><dd>{{ $reservation->queue_number ?? '-' }}</dd></div>
                    <div><dt>Status</dt><dd><span class="status-pill status-{{ strtolower($reservation->status) }}">{{ $reservation->status }}</span></dd></div>
                </dl>
            </article>

            <article class="admin-card">
                <h2>Informasi Pasien</h2>
                <dl class="admin-detail-list">
                    <div><dt>NIK</dt><dd>{{ $reservation->patient->nik ?? '-' }}</dd></div>
                    <div><dt>No. Telepon</dt><dd>{{ $reservation->patient->phone ?? '-' }}</dd></div>
                    <div><dt>Email</dt><dd>{{ $reservation->patient->user->email ?? '-' }}</dd></div>
                    <div><dt>Alamat</dt><dd>{{ $reservation->patient->address ?? '-' }}</dd></div>
                </dl>
                <a class="admin-primary-link" href="{{ route('admin.reservations.show', $reservation) }}">Buka Detail</a>
            </article>
        </section>
    @elseif(request()->filled('code'))
        <section class="admin-card">
            <p>Reservasi dengan kode <strong>{{ request('code') }}</strong> tidak ditemukan.</p>
        </section>
    @endif
@endsection
