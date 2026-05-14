@extends('layouts.admin')

@section('title', 'MediLabs Admin - Cek Status Reservasi')

@section('content')
    <section class="admin-section">
        <div class="admin-heading">
            <h1>Cek Status Reservasi</h1>
            <p>Cari data reservasi pasien berdasarkan kode reservasi</p>
        </div>

        <div class="admin-status-grid">
            <form class="dark-panel admin-search-card" action="{{ route('admin.reservations.status') }}" method="get">
                <label>
                    <span>Cari Kode Reservasi</span>
                    <input type="text" name="code" value="A01000">
                </label>
            </form>

            <article class="dark-panel admin-patient-card">
                <h2>Informasi Pasien</h2>
                <dl>
                    @foreach ($patient as $label => $value)
                        <div>
                            <dt>{{ $label }}</dt>
                            <dd>{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>
            </article>
        </div>

        <article class="dark-panel admin-detail-card">
            <h2>Detail Reservasi</h2>
            <dl>
                @foreach ($reservation as $label => $value)
                    <div>
                        <dt>{{ $label }}</dt>
                        <dd>{{ $value }}</dd>
                    </div>
                @endforeach
            </dl>
        </article>

        <div class="admin-action-row">
            <button class="button admin-button" type="button" data-print>Cetak Bukti</button>
            <a class="button admin-button" href="{{ route('admin.reservations.manage') }}">Ubah Status</a>
            <a class="button admin-button" href="{{ route('admin.dashboard') }}">Kembali</a>
        </div>
    </section>
@endsection
