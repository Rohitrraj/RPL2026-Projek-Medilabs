@extends('layouts.admin')

@section('title', 'MediLabs Admin - Kelola Reservasi')

@section('content')
    <section class="admin-section">
        <div class="admin-heading">
            <h1>Kelola Reservasi</h1>
            <p>Admin dapat mengelola, memverifikasi, dan mengubah status reservasi</p>
        </div>

        <form class="dark-panel admin-search-card manage-search-card" action="{{ route('admin.reservations.manage') }}" method="get">
            <label>
                <span>Cari Kode Reservasi</span>
                <input type="text" name="code" value="A01000">
            </label>
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
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reservations as $reservation)
                        <tr>
                            <td>{{ $reservation['code'] }}</td>
                            <td>{{ $reservation['patient'] }}</td>
                            <td>{{ $reservation['test'] }}</td>
                            <td>{{ $reservation['date'] }}</td>
                            <td>{{ $reservation['hour'] }}</td>
                            <td>{{ $reservation['status'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="quick-actions">
            <h2>Aksi Cepat</h2>
            <div>
                <button class="button admin-button" type="button">Konfirmasi</button>
                <button class="button admin-button" type="button">Ubah ke Diproses</button>
                <button class="button admin-button" type="button">Selesai</button>
                <button class="button admin-button" type="button">Batalkan</button>
            </div>
        </div>
    </section>
@endsection
