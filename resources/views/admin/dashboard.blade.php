@extends('layouts.admin')

@section('title', 'MediLabs Admin - Dashboard')

@section('content')
    <section class="admin-section">
        <div class="admin-heading">
            <h1>Dashboard</h1>
            <p>Ringkasan aktivitas reservasi laboratorium klinik</p>
        </div>

        <div class="admin-stats-grid">
            @foreach ($stats as $label => $value)
                <article class="admin-stat-card">
                    <span>{{ $label }}</span>
                    <strong>{{ $value }}</strong>
                </article>
            @endforeach
        </div>

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
                    @foreach ($reservations as $reservation)
                        <tr>
                            <td>{{ $reservation['code'] }}</td>
                            <td>{{ $reservation['patient'] }}</td>
                            <td>{{ $reservation['test'] }}</td>
                            <td>{{ $reservation['date'] }} {{ $reservation['hour'] }}</td>
                            <td>{{ $reservation['status'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
