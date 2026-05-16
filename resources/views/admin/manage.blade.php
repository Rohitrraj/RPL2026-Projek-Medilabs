@extends('layouts.admin')

@section('title', 'Kelola Reservasi - MediLabs')

@section('content')
    <section class="admin-page-header">
        <div>
            <p class="admin-eyebrow">Manajemen Reservasi</p>
            <h1>Kelola Reservasi</h1>
            <p>Admin dapat melihat, memfilter, memperbarui status, dan menghapus data reservasi.</p>
        </div>
    </section>

    <section class="admin-card admin-filter-card">
        <form method="GET" action="{{ route('admin.reservations.manage') }}" class="admin-filter-form">
            <label>
                Cari Kode
                <input type="text" name="code" value="{{ request('code') }}" placeholder="A01000">
            </label>

            <label>
                Status
                <select name="status">
                    <option value="">Semua Status</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </label>

            <label>
                Tanggal
                <input type="date" name="date" value="{{ request('date') }}">
            </label>

            <label>
                Jenis Tes
                <select name="lab_test_id">
                    <option value="">Semua Tes</option>
                    @foreach ($labTests as $test)
                        <option value="{{ $test->id }}" @selected((string) request('lab_test_id') === (string) $test->id)>{{ $test->name }}</option>
                    @endforeach
                </select>
            </label>

            <button type="submit">Filter</button>
            <a href="{{ route('admin.reservations.manage') }}">Reset</a>
        </form>
    </section>

    <section class="admin-card">
        <div class="admin-card-header">
            <div>
                <h2>Daftar Reservasi</h2>
                <p>Data terhubung dengan tabel patients dan lab_tests.</p>
            </div>
        </div>

        <div class="admin-table-wrapper">
            <table class="admin-table">
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
                            <td><span class="status-pill status-{{ strtolower($reservation->status) }}">{{ $reservation->status }}</span></td>
                            <td class="admin-action-cell">
                                <a class="admin-table-link" href="{{ route('admin.reservations.show', $reservation) }}">Detail</a>
                                <form method="POST" action="{{ route('admin.reservations.destroy', $reservation) }}" onsubmit="return confirm('Hapus reservasi ini? Data yang dihapus tidak bisa dikembalikan.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-danger-link">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">Tidak ada data reservasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="admin-pagination">
            {{ $reservations->links() }}
        </div>
    </section>
@endsection
