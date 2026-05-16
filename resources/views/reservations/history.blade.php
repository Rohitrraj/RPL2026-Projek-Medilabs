@extends('layouts.app')

@section('title', 'Riwayat Reservasi - MediLabs')

@section('content')
<section class="history-page">
    <div class="history-container">

        <div class="history-header">
            <div>
                <p class="history-subtitle">Riwayat Reservasi</p>
                <h1>Daftar Reservasi Pemeriksaan</h1>
                <p>
                    Lihat daftar reservasi laboratorium yang pernah dibuat melalui akun ini.
                </p>
            </div>

            <a href="{{ route('reservations.create') }}" class="history-primary-button">
                Buat Reservasi Baru
            </a>
        </div>

        <div class="history-summary">
            <div class="history-summary-card">
                <span>Total Reservasi</span>
                <strong>{{ $reservations->count() }}</strong>
            </div>

            <div class="history-summary-card">
                <span>Aktif</span>
                <strong>
                    {{ $reservations->whereIn('status', ['Menunggu', 'Terjadwal', 'Dikonfirmasi', 'Diproses'])->count() }}
                </strong>
            </div>

            <div class="history-summary-card">
                <span>Selesai</span>
                <strong>{{ $reservations->where('status', 'Selesai')->count() }}</strong>
            </div>

            <div class="history-summary-card">
                <span>Dibatalkan</span>
                <strong>{{ $reservations->where('status', 'Dibatalkan')->count() }}</strong>
            </div>
        </div>

        <div class="history-card">
            <form class="history-filter" action="{{ route('reservations.history') }}" method="GET">
                <div class="history-filter-group">
                    <label for="code">Cari Kode Reservasi</label>
                    <input
                        type="text"
                        id="code"
                        name="code"
                        value="{{ request('code') }}"
                        placeholder="Contoh: A00001"
                    >
                </div>

                <button type="submit" class="history-filter-button">
                    Cari
                </button>

                @if(request('code'))
                    <a href="{{ route('reservations.history') }}" class="history-reset-button">
                        Reset
                    </a>
                @endif
            </form>

            <div class="history-table-wrapper">
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Jenis Tes</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Status</th>
                            <th class="history-action-column">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($reservations as $item)
                            <tr>
                                <td>
                                    <span class="history-code">
                                        {{ $item->code ?? $item->reservation_code ?? '-' }}
                                    </span>
                                </td>

                                <td>
                                    <div class="history-test-name">
                                        {{ $item->labTest->name ?? '-' }}
                                    </div>
                                </td>

                                <td>
                                    {{ optional($item->reservation_date)->format('d M Y') }}
                                </td>

                                <td>
                                    {{ substr((string) $item->reservation_time, 0, 5) }}
                                </td>

                                <td>
                                    @php
                                        $statusClass = match ($item->status) {
                                            'Selesai' => 'is-success',
                                            'Dibatalkan' => 'is-danger',
                                            'Diproses' => 'is-process',
                                            'Dikonfirmasi', 'Terjadwal' => 'is-confirmed',
                                            default => 'is-waiting',
                                        };
                                    @endphp

                                    <span class="history-status {{ $statusClass }}">
                                        {{ $item->status }}
                                    </span>
                                </td>

                                <td>
                                    <div class="history-actions">
                                        <a
                                            href="{{ route('reservations.status', ['code' => $item->code ?? $item->reservation_code]) }}"
                                            class="history-detail-button"
                                        >
                                            Detail
                                        </a>

                                        @if(Route::has('reservations.destroy') && ! in_array($item->status, ['Selesai', 'Dibatalkan']))
                                            <form
                                                action="{{ route('reservations.destroy', $item) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus reservasi ini?')"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="history-delete-button">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="history-empty">
                                        <strong>Belum ada reservasi.</strong>
                                        <p>Data reservasi akan tampil setelah kamu membuat reservasi pemeriksaan.</p>
                                        <a href="{{ route('reservations.create') }}">
                                            Buat Reservasi
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>
@endsection