@extends('layouts.admin')

@section('title', 'MediLabs Admin - Kelola Reservasi')

@section('content')
    <section class="admin-section">
        <x-page-header
            title="Kelola Reservasi"
            description="Admin dapat mengelola, memverifikasi, dan mengubah status reservasi"
            wrapper-class="admin-heading"
        />

        <form class="dark-panel admin-search-card manage-search-card" action="{{ route('admin.reservations.manage') }}" method="GET">
            <label>
                <span>Cari Kode Reservasi</span>
                <input type="text" name="code" value="{{ request('code') }}" placeholder="A001">
            </label>
            <button class="button admin-button" type="submit">Cari</button>
        </form>

        <div class="table-card admin-table-card manage-table-card">
            <table class="med-table admin-table manage-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Pasien</th>
                        <th>Jenis Tes</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
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
                            <td>{{ optional($reservation->reservation_date)->format('d M Y') }}</td>
                            <td>{{ substr((string) $reservation->reservation_time, 0, 5) }}</td>
                            <td>{{ $reservation->status }}</td>
                            <td>
                                <form action="{{ route('admin.reservations.update-status', $reservation) }}" method="POST" class="inline-status-form">
                                    @csrf
                                    @method('PATCH')

                                    <select name="status">
                                        @foreach ($statuses as $status)
                                            <option value="{{ $status }}" @selected($reservation->status === $status)>
                                                {{ $status }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <button type="submit">Simpan</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">Belum ada data reservasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="quick-actions">
            <h2>Aksi Cepat</h2>
            <div>
                <a class="button admin-button" href="{{ route('admin.reservations.manage') }}">Refresh Data</a>
                <a class="button admin-button" href="{{ route('admin.reservations.status') }}">Cek Detail</a>
            </div>
        </div>
    </section>
@endsection