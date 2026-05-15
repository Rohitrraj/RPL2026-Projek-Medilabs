@extends('layouts.app')

@section('title', 'MediLabs - Profile')

@section('content')
    <section class="profile-page">
        <div class="profile-header">
            <h1>Profile Pengguna</h1>
            <p>Halaman ini menampilkan data akun, data pasien, dan riwayat reservasi terbaru.</p>
        </div>

        <div class="profile-grid">
            <article class="dark-panel profile-card">
                <h2>Data Akun</h2>
                <dl>
                    <div>
                        <dt>Nama</dt>
                        <dd>{{ $user->name }}</dd>
                    </div>
                    <div>
                        <dt>Email</dt>
                        <dd>{{ $user->email }}</dd>
                    </div>
                    <div>
                        <dt>No. Telepon</dt>
                        <dd>{{ $user->phone ?? '-' }}</dd>
                    </div>
                </dl>
            </article>

            <article class="dark-panel profile-card">
                <h2>Data Pasien</h2>

                @if ($patient)
                    <dl>
                        <div>
                            <dt>Nama Pasien</dt>
                            <dd>{{ $patient->full_name }}</dd>
                        </div>
                        <div>
                            <dt>NIK</dt>
                            <dd>{{ $patient->nik }}</dd>
                        </div>
                        <div>
                            <dt>Jenis Kelamin</dt>
                            <dd>{{ $patient->gender }}</dd>
                        </div>
                        <div>
                            <dt>Golongan Darah</dt>
                            <dd>{{ $patient->blood_type ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt>Alamat</dt>
                            <dd>{{ $patient->address ?? '-' }}</dd>
                        </div>
                    </dl>
                @else
                    <p class="profile-empty">Data pasien belum diisi.</p>
                    <a class="button button-primary" href="{{ route('patients.create') }}">Isi Data Pasien</a>
                @endif
            </article>
        </div>

        <article class="profile-history-card">
            <h2>Reservasi Terbaru</h2>

            @if ($reservations->isEmpty())
                <p>Belum ada reservasi yang tersimpan untuk akun ini.</p>
            @else
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
                        @foreach ($reservations as $reservation)
                            <tr>
                                <td>{{ $reservation->code }}</td>
                                <td>{{ $reservation->labTest->name ?? '-' }}</td>
                                <td>{{ optional($reservation->reservation_date)->format('d M Y') }}</td>
                                <td>{{ substr((string) $reservation->reservation_time, 0, 5) }}</td>
                                <td>{{ $reservation->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </article>
    </section>
@endsection
