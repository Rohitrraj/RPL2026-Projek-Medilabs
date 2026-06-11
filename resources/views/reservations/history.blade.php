@extends('layouts.app')

@section('title', 'MediLabs - Riwayat Reservasi')

@section('content')
    <section class="history-page-fixed">
        <div class="history-heading-fixed">
            <h1>Riwayat Reservasi</h1>
            <p>Daftar reservasi pemeriksaan laboratorium yang pernah dibuat pasien.</p>
        </div>

        <form class="history-search-fixed" action="{{ route('reservations.history') }}" method="get">
            <label for="history-code">Cari Kode</label>
            <input id="history-code" type="text" name="code" value="{{ request('code') }}" placeholder="Masukkan kode reservasi">
            <button class="button button-primary" type="submit">Cari</button>
        </form>

        <div class="history-table-fixed">
            <table>
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Jenis Tes</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($reservations as $item)
                        <tr>
                            <td>{{ $item->code }}</td>
                            <td>{{ $item->labTest->name ?? '-' }}</td>
                            <td>{{ optional($item->reservation_date)->format('d M Y') }}</td>
                            <td>{{ substr((string) $item->reservation_time, 0, 5) }}</td>
                            <td>
                                <x-status-badge :status="$item->status" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">Belum ada data reservasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="history-summary-fixed">
            <h2>Ringkasan Reservasi</h2>

            <div class="history-summary-grid">
                <div>
                    <strong>{{ $reservations->whereIn('status', ['Menunggu', 'Terjadwal', 'Diproses'])->count() }}</strong>
                    <span>Aktif</span>
                </div>

                <div>
                    <strong>{{ $reservations->where('status', 'Selesai')->count() }}</strong>
                    <span>Selesai</span>
                </div>

                <div>
                    <strong>{{ $reservations->where('status', 'Menunggu')->count() }}</strong>
                    <span>Menunggu</span>
                </div>
            </div>
        </div>
    </section>
@endsection
