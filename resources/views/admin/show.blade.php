@extends('layouts.admin')

@section('title', 'Detail Reservasi Admin - MediLabs')

@section('content')
    <section class="admin-page-header">
        <div>
            <p class="admin-eyebrow">Detail Reservasi</p>
            <h1>{{ $reservation->code }}</h1>
            <p>Admin dapat melihat detail pasien, layanan, jadwal, dan memperbarui status reservasi.</p>
        </div>
        <a class="admin-primary-link" href="{{ route('admin.reservations.manage') }}">Kembali</a>
    </section>

    <section class="admin-detail-grid">
        <article class="admin-card">
            <h2>Data Pasien</h2>
            <dl class="admin-detail-list">
                <div><dt>Nama</dt><dd>{{ $reservation->patient->full_name ?? '-' }}</dd></div>
                <div><dt>NIK</dt><dd>{{ $reservation->patient->nik ?? '-' }}</dd></div>
                <div><dt>No. Telepon</dt><dd>{{ $reservation->patient->phone ?? '-' }}</dd></div>
                <div><dt>Email Akun</dt><dd>{{ $reservation->patient->user->email ?? '-' }}</dd></div>
                <div><dt>Alamat</dt><dd>{{ $reservation->patient->address ?? '-' }}</dd></div>
                <div><dt>Golongan Darah</dt><dd>{{ $reservation->patient->blood_type ?? '-' }}</dd></div>
            </dl>
        </article>

        <article class="admin-card">
            <h2>Data Reservasi</h2>
            <dl class="admin-detail-list">
                <div><dt>Kode</dt><dd>{{ $reservation->code }}</dd></div>
                <div><dt>Jenis Tes</dt><dd>{{ $reservation->labTest->name ?? '-' }}</dd></div>
                <div><dt>Tanggal</dt><dd>{{ optional($reservation->reservation_date)->format('d M Y') }}</dd></div>
                <div><dt>Jam</dt><dd>{{ substr((string) $reservation->reservation_time, 0, 5) }}</dd></div>
                <div><dt>Nomor Antrean</dt><dd>{{ $reservation->queue_number ?? '-' }}</dd></div>
                <div><dt>Catatan</dt><dd>{{ $reservation->notes ?? '-' }}</dd></div>
            </dl>
        </article>

        <article class="admin-card admin-status-card">
            <h2>Status Reservasi</h2>
            <p>Status saat ini:</p>
            <span class="status-pill status-{{ strtolower($reservation->status) }}">{{ $reservation->status }}</span>

            <form method="POST" action="{{ route('admin.reservations.update-status', $reservation) }}" class="admin-status-form">
                @csrf
                @method('PATCH')

                <label for="status">Ubah Status</label>
                <select name="status" id="status" required>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" @selected($reservation->status === $status)>{{ $status }}</option>
                    @endforeach
                </select>

                <button type="submit">Simpan Perubahan</button>
            </form>
        </article>
    </section>
@endsection
