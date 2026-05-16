@extends('layouts.admin')

@section('title', 'Admin Dashboard - MediLabs')

@section('content')
    <section class="admin-page-header">
        <div>
            <p class="admin-eyebrow">Back-End Sesi 3 · 75%</p>
            <h1>Admin Dashboard</h1>
            <p>Ringkasan aktivitas reservasi laboratorium klinik MediLabs.</p>
        </div>
        <a class="admin-primary-link" href="{{ route('admin.reservations.manage') }}">Kelola Reservasi</a>
    </section>

    <section class="admin-stat-grid">
        @foreach ($stats as $label => $value)
            <article class="admin-stat-card">
                <span>{{ $label }}</span>
                <strong>{{ $value }}</strong>
            </article>
        @endforeach
    </section>

    <section class="admin-card">
        <div class="admin-card-header">
            <div>
                <h2>Reservasi Terbaru</h2>
                <p>Data terbaru diambil langsung dari tabel reservations.</p>
            </div>
            <a href="{{ route('admin.reservations.manage') }}">Lihat Semua</a>
        </div>

        <div class="admin-table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Pasien</th>
                        <th>Jenis Tes</th>
                        <th>Jadwal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reservations as $reservation)
                        <tr>
                            <td>{{ $reservation->code }}</td>
                            <td>{{ $reservation->patient->full_name ?? '-' }}</td>
                            <td>{{ $reservation->labTest->name ?? '-' }}</td>
                            <td>{{ optional($reservation->reservation_date)->format('d M Y') }} · {{ substr((string) $reservation->reservation_time, 0, 5) }}</td>
                            <td><span class="status-pill status-{{ strtolower($reservation->status) }}">{{ $reservation->status }}</span></td>
                            <td><a class="admin-table-link" href="{{ route('admin.reservations.show', $reservation) }}">Detail</a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">Belum ada data reservasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
