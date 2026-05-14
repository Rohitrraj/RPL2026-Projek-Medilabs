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
            <input id="history-code" type="text" name="code" placeholder="Masukkan kode reservasi">
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
                    @foreach ($reservations as $item)
                        <tr>
                            <td>{{ $item['Kode'] }}</td>
                            <td>{{ $item['Jenis Tes'] }}</td>
                            <td>{{ $item['Tanggal'] }}</td>
                            <td>{{ $item['Jam'] }}</td>
                            <td>
                                <span class="history-status-badge">
                                    {{ $item['Status'] }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="history-summary-fixed">
            <h2>Ringkasan Reservasi</h2>

            <div class="history-summary-grid">
                <div>
                    <strong>1</strong>
                    <span>Aktif</span>
                </div>

                <div>
                    <strong>1</strong>
                    <span>Selesai</span>
                </div>

                <div>
                    <strong>1</strong>
                    <span>Menunggu</span>
                </div>
            </div>
        </div>
    </section>
@endsection