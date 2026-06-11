@extends('layouts.admin')

@section('title', 'MediLabs Admin - Dashboard')

@section('content')
    <section class="admin-section">
        <x-page-header
            title="Dashboard"
            description="Ringkasan aktivitas reservasi laboratorium klinik"
            wrapper-class="admin-heading"
        />

        <div class="admin-stats-grid">
            @foreach ($stats as $label => $value)
                <article class="admin-stat-card">
                    <span>{{ $label }}</span>
                    <strong>{{ $value }}</strong>
                </article>
            @endforeach
        </div>

        <div>
            <h2 class="admin-subtitle">Reservasi Terbaru</h2>

            <div class="table-card admin-table-card">
                <table class="med-table admin-table">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Pasien</th>
                            <th>Jenis Tes</th>
                            <th>Jadwal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reservations as $reservation)
                            <tr>
                                <td>{{ $reservation->code }}</td>
                                <td>{{ $reservation->patient->full_name ?? '-' }}</td>
                                <td>{{ $reservation->labTest->name ?? '-' }}</td>
                                <td>
                                    {{ optional($reservation->reservation_date)->format('d M Y') }}
                                    {{ substr((string) $reservation->reservation_time, 0, 5) }}
                                </td>
                                <td>{{ $reservation->status }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">Belum ada data reservasi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection