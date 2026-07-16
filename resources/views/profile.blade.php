@extends('layouts.app')

@section('title', 'Profil Saya | MediLabs')

@section('content')
    <section class="ml-public-page">
        <header class="ml-public-page-header">
            <div class="ml-public-page-header__copy">
                <span class="ml-public-eyebrow">
                    <i class="bi bi-person-circle" aria-hidden="true"></i>
                    Akun pasien
                </span>

                <h1 class="ml-public-page-title">Profil Saya</h1>

                <p class="ml-public-page-description">
                    Kelola informasi akun, periksa kelengkapan data pasien,
                    dan lihat reservasi terbaru Anda.
                </p>
            </div>

            <div class="ml-public-page-actions">
                @if ($patient)
                    <a
                        class="ml-public-button ml-public-button--outline"
                        href="{{ route('patients.create', ['edit' => 1]) }}"
                    >
                        <i class="bi bi-pencil-square" aria-hidden="true"></i>
                        Edit Data Pasien
                    </a>

                    <a
                        class="ml-public-button ml-public-button--primary"
                        href="{{ route('reservations.create') }}"
                    >
                        <i class="bi bi-calendar2-plus" aria-hidden="true"></i>
                        Buat Reservasi
                    </a>
                @else
                    <a
                        class="ml-public-button ml-public-button--primary"
                        href="{{ route('patients.create') }}"
                    >
                        <i class="bi bi-person-vcard" aria-hidden="true"></i>
                        Lengkapi Data Pasien
                    </a>
                @endif
            </div>
        </header>

        <div class="ml-profile-grid">
            <article class="ml-public-card">
                <header class="ml-public-card__header">
                    <div class="ml-profile-card-heading">
                        <span class="ml-public-icon-box" aria-hidden="true">
                            <i class="bi bi-person"></i>
                        </span>

                        <div class="ml-public-card__title-wrap">
                            <h2 class="ml-public-card__title">Data Akun</h2>
                            <p class="ml-public-card__description">
                                Informasi akun yang digunakan untuk masuk ke MediLabs.
                            </p>
                        </div>
                    </div>
                </header>

                <div class="ml-public-card__body">
                    <dl class="ml-public-definition-list">
                        <div>
                            <dt>Nama akun</dt>
                            <dd>{{ $user->name }}</dd>
                        </div>
                        <div>
                            <dt>Email</dt>
                            <dd>{{ $user->email }}</dd>
                        </div>
                        <div>
                            <dt>Nomor telepon akun</dt>
                            <dd>{{ $user->phone ?: 'Belum diisi' }}</dd>
                        </div>
                        <div>
                            <dt>Peran</dt>
                            <dd>Pasien</dd>
                        </div>
                    </dl>
                </div>
            </article>

            <article class="ml-public-card">
                <header class="ml-public-card__header">
                    <div class="ml-profile-card-heading">
                        <span class="ml-public-icon-box" aria-hidden="true">
                            <i class="bi bi-person-vcard"></i>
                        </span>

                        <div class="ml-public-card__title-wrap">
                            <h2 class="ml-public-card__title">Data Pasien</h2>
                            <p class="ml-public-card__description">
                                Data ini digunakan pada proses reservasi dan pemeriksaan.
                            </p>
                        </div>
                    </div>

                    <span
                        class="ml-profile-completeness {{ $patient ? '' : 'ml-profile-completeness--incomplete' }}"
                    >
                        <i
                            class="bi {{ $patient ? 'bi-check-circle' : 'bi-exclamation-circle' }}"
                            aria-hidden="true"
                        ></i>
                        {{ $patient ? 'Sudah lengkap' : 'Belum lengkap' }}
                    </span>
                </header>

                <div class="ml-public-card__body">
                    @if ($patient)
                        <dl class="ml-public-definition-list">
                            <div>
                                <dt>Nama lengkap</dt>
                                <dd>{{ $patient->full_name }}</dd>
                            </div>
                            <div>
                                <dt>NIK</dt>
                                <dd>{{ $patient->nik }}</dd>
                            </div>
                            <div>
                                <dt>Jenis kelamin</dt>
                                <dd>{{ $patient->gender }}</dd>
                            </div>
                            <div>
                                <dt>Tanggal lahir</dt>
                                <dd>
                                    {{ $patient->birth_date
                                        ? $patient->birth_date->format('d M Y')
                                        : 'Belum diisi' }}
                                </dd>
                            </div>
                            <div>
                                <dt>Nomor telepon pasien</dt>
                                <dd>{{ $patient->phone }}</dd>
                            </div>
                            <div>
                                <dt>Golongan darah</dt>
                                <dd>{{ $patient->blood_type ?: 'Belum diisi' }}</dd>
                            </div>
                            <div>
                                <dt>Alamat</dt>
                                <dd>{{ $patient->address ?: 'Belum diisi' }}</dd>
                            </div>
                        </dl>
                    @else
                        <div class="ml-public-empty-state">
                            <span class="ml-public-empty-state__icon" aria-hidden="true">
                                <i class="bi bi-person-plus"></i>
                            </span>

                            <h3>Data pasien belum tersedia</h3>
                            <p>
                                Lengkapi data pasien terlebih dahulu agar Anda dapat
                                membuat reservasi pemeriksaan laboratorium.
                            </p>

                            <a
                                class="ml-public-button ml-public-button--primary"
                                href="{{ route('patients.create') }}"
                            >
                                Isi Data Pasien
                            </a>
                        </div>
                    @endif
                </div>
            </article>
        </div>

        <article class="ml-public-card ml-profile-reservations">
            <header class="ml-public-card__header">
                <div class="ml-public-card__title-wrap">
                    <h2 class="ml-public-card__title">Reservasi Terbaru</h2>
                    <p class="ml-public-card__description">
                        Lima reservasi terbaru yang tersimpan pada akun Anda.
                    </p>
                </div>

                <a
                    class="ml-public-button ml-public-button--outline ml-public-button--sm"
                    href="{{ route('reservations.history') }}"
                >
                    Lihat Semua
                    <i class="bi bi-arrow-right" aria-hidden="true"></i>
                </a>
            </header>

            @if ($reservations->isEmpty())
                <div class="ml-public-card__body">
                    <div class="ml-public-empty-state">
                        <span class="ml-public-empty-state__icon" aria-hidden="true">
                            <i class="bi bi-calendar2-x"></i>
                        </span>

                        <h3>Belum ada reservasi</h3>
                        <p>
                            Reservasi yang dibuat akan tampil pada bagian ini.
                        </p>

                        @if ($patient)
                            <a
                                class="ml-public-button ml-public-button--primary"
                                href="{{ route('reservations.create') }}"
                            >
                                Buat Reservasi Pertama
                            </a>
                        @endif
                    </div>
                </div>
            @else
                <div class="ml-public-table-wrap">
                    <table class="ml-public-table">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Layanan</th>
                                <th>Jadwal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reservations as $reservation)
                                <tr>
                                    <td>
                                        <span class="ml-public-table__primary">
                                            {{ $reservation->code }}
                                        </span>
                                    </td>
                                    <td>{{ $reservation->labTest->name ?? '-' }}</td>
                                    <td>
                                        <span class="ml-public-table__primary">
                                            {{ optional($reservation->reservation_date)->format('d M Y') }}
                                        </span>
                                        <span class="ml-public-table__secondary">
                                            {{ substr((string) $reservation->reservation_time, 0, 5) }} WIB
                                        </span>
                                    </td>
                                    <td>
                                        <x-status-badge :status="$reservation->status" />
                                    </td>
                                    <td>
                                        <a
                                            class="ml-public-button ml-public-button--outline ml-public-button--sm"
                                            href="{{ route('reservations.result', $reservation) }}"
                                        >
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </article>
    </section>
@endsection
