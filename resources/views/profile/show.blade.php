@extends('layouts.app')

@section('title', 'MediLabs - Profil')

@section('content')
    <section class="profile-layout">
        <div class="dark-panel profile-card">
            <h1>Profil Pengguna</h1>

            <div class="profile-grid">
                <div>
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
                </div>

                <div>
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
                                <dt>Tanggal Lahir</dt>
                                <dd>{{ optional($patient->birth_date)->format('d M Y') ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt>No. Telepon</dt>
                                <dd>{{ $patient->phone }}</dd>
                            </div>
                            <div>
                                <dt>Alamat</dt>
                                <dd>{{ $patient->address ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt>Golongan Darah</dt>
                                <dd>{{ $patient->blood_type ?? '-' }}</dd>
                            </div>
                        </dl>
                    @else
                        <p>Data pasien belum diisi.</p>
                        <a class="button button-primary" href="{{ route('patients.create') }}">Isi Data Pasien</a>
                    @endif
                </div>
            </div>

            <div class="profile-actions">
                @if ($patient)
                    <a class="button button-primary" href="{{ route('patients.create', ['edit' => 1]) }}">Edit Data Pasien</a>
                    <a class="button button-secondary" href="{{ route('reservations.create') }}">Buat Reservasi</a>
                @else
                    <a class="button button-primary" href="{{ route('patients.create') }}">Isi Data Pasien</a>
                @endif
                <a class="button button-outline" href="{{ route('reservations.history') }}">Lihat Riwayat</a>
            </div>
        </div>
    </section>
@endsection
