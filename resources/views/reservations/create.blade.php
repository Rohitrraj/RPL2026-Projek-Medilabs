@extends('layouts.app')

@section('title', 'Buat Reservasi - MediLabs')

@section('content')
    @php
        $selectedLabTestId = (string) old('lab_test_id', request('service', ''));
        $selectedLabTest = $labTests->first(
            fn ($labTest) => (string) $labTest->id === $selectedLabTestId
        );
    @endphp

    <section class="ml-public-page">
        <header class="ml-public-page-header">
            <div class="ml-public-page-header__copy">
                <span class="ml-public-eyebrow">
                    <i class="bi bi-calendar2-plus" aria-hidden="true"></i>
                    Reservasi pasien
                </span>

                <h1 class="ml-public-page-title">Buat Reservasi Pemeriksaan</h1>

                <p class="ml-public-page-description">
                    Pilih layanan, tanggal, dan jam pemeriksaan yang tersedia.
                    Reservasi baru akan memiliki status awal Menunggu.
                </p>
            </div>

            <div class="ml-public-page-actions">
                <a
                    class="ml-public-button ml-public-button--outline"
                    href="{{ route('reservations.history') }}"
                >
                    <i class="bi bi-clock-history" aria-hidden="true"></i>
                    Lihat Riwayat
                </a>
            </div>
        </header>

        <div class="ml-reservation-layout">
            <article class="ml-public-card">
                <header class="ml-public-card__header">
                    <div class="ml-profile-card-heading">
                        <span class="ml-public-icon-box" aria-hidden="true">
                            <i class="bi bi-clipboard2-pulse"></i>
                        </span>

                        <div class="ml-public-card__title-wrap">
                            <h2 class="ml-public-card__title">Form Reservasi</h2>
                            <p class="ml-public-card__description">
                                Pastikan layanan dan jadwal sudah sesuai sebelum disimpan.
                            </p>
                        </div>
                    </div>
                </header>

                <form
                    class="ml-public-form"
                    action="{{ route('reservations.store') }}"
                    method="POST"
                    data-public-form
                    data-reservation-form
                >
                    @csrf

                    <div class="ml-public-card__body">
                        <div class="ml-public-form">
                            <div class="ml-reservation-patient">
                                <div class="ml-reservation-patient__identity">
                                    <span class="ml-reservation-patient__avatar" aria-hidden="true">
                                        {{ strtoupper(mb_substr($patient->full_name, 0, 1)) }}
                                    </span>

                                    <span class="ml-reservation-patient__copy">
                                        <strong>{{ $patient->full_name }}</strong>
                                        <span>NIK {{ $patient->nik }}</span>
                                    </span>
                                </div>

                                <a
                                    class="ml-public-button ml-public-button--outline ml-public-button--sm"
                                    href="{{ route('patients.create', ['edit' => 1]) }}"
                                >
                                    Edit Data
                                </a>
                            </div>

                            <div class="ml-public-field">
                                <label class="ml-public-label" for="lab_test_id">
                                    Layanan Laboratorium
                                </label>

                                <select
                                    id="lab_test_id"
                                    class="ml-public-select {{ $errors->has('lab_test_id') ? 'is-invalid' : '' }}"
                                    name="lab_test_id"
                                    required
                                    data-reservation-service
                                    aria-invalid="{{ $errors->has('lab_test_id') ? 'true' : 'false' }}"
                                    @error('lab_test_id') aria-describedby="lab-test-error" @enderror
                                >
                                    <option value="">Pilih layanan laboratorium</option>
                                    @foreach ($labTests as $labTest)
                                        <option
                                            value="{{ $labTest->id }}"
                                            data-service-name="{{ $labTest->name }}"
                                            data-service-price="Rp {{ number_format((float) $labTest->price, 0, ',', '.') }}"
                                            @selected($selectedLabTestId === (string) $labTest->id)
                                        >
                                            {{ $labTest->name }} — Rp {{ number_format((float) $labTest->price, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('lab_test_id')
                                    <p id="lab-test-error" class="ml-public-field-error">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="ml-public-form-grid">
                                <div class="ml-public-field">
                                    <label class="ml-public-label" for="reservation_date">
                                        Tanggal Pemeriksaan
                                    </label>

                                    <input
                                        id="reservation_date"
                                        class="ml-public-input {{ $errors->has('reservation_date') ? 'is-invalid' : '' }}"
                                        type="date"
                                        name="reservation_date"
                                        value="{{ old('reservation_date') }}"
                                        min="{{ now()->format('Y-m-d') }}"
                                        required
                                        data-reservation-date
                                        aria-invalid="{{ $errors->has('reservation_date') ? 'true' : 'false' }}"
                                        @error('reservation_date') aria-describedby="reservation-date-error" @enderror
                                    >

                                    @error('reservation_date')
                                        <p id="reservation-date-error" class="ml-public-field-error">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div class="ml-public-field">
                                    <label class="ml-public-label" for="reservation_time">
                                        Jam Pemeriksaan
                                    </label>

                                    <select
                                        id="reservation_time"
                                        class="ml-public-select {{ $errors->has('reservation_time') ? 'is-invalid' : '' }}"
                                        name="reservation_time"
                                        required
                                        data-reservation-time
                                        aria-invalid="{{ $errors->has('reservation_time') ? 'true' : 'false' }}"
                                        @error('reservation_time') aria-describedby="reservation-time-error" @enderror
                                    >
                                        <option value="">Pilih jam pemeriksaan</option>
                                        @foreach ($hours as $hour)
                                            <option
                                                value="{{ $hour }}"
                                                @selected(old('reservation_time') === $hour)
                                            >
                                                {{ $hour }} WIB
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('reservation_time')
                                        <p id="reservation-time-error" class="ml-public-field-error">
                                            {{ $message }}
                                        </p>
                                    @else
                                        <p class="ml-public-help-text">
                                            Jam tersedia mengikuti pilihan yang diberikan sistem.
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <div class="ml-public-field">
                                <div class="ml-public-field__header">
                                    <label class="ml-public-label" for="notes">
                                        Catatan atau Keluhan
                                    </label>
                                    <span class="ml-public-optional">Opsional</span>
                                </div>

                                <textarea
                                    id="notes"
                                    class="ml-public-textarea {{ $errors->has('notes') ? 'is-invalid' : '' }}"
                                    name="notes"
                                    rows="4"
                                    maxlength="500"
                                    placeholder="Tuliskan catatan yang perlu diketahui petugas"
                                    data-character-count
                                    aria-invalid="{{ $errors->has('notes') ? 'true' : 'false' }}"
                                    @error('notes') aria-describedby="notes-error" @enderror
                                >{{ old('notes') }}</textarea>

                                <p class="ml-public-counter">
                                    <span data-character-count-value>0</span>/500 karakter
                                </p>

                                @error('notes')
                                    <p id="notes-error" class="ml-public-field-error">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="ml-public-notice">
                                <i class="bi bi-info-circle ml-public-notice__icon" aria-hidden="true"></i>
                                <span>
                                    Pasien tidak dapat membuat dua reservasi aktif pada
                                    tanggal dan jam yang sama.
                                </span>
                            </div>
                        </div>
                    </div>

                    <footer class="ml-public-card__footer">
                        <a
                            class="ml-public-button ml-public-button--outline"
                            href="{{ route('services.index') }}"
                        >
                            Lihat Layanan
                        </a>

                        <button
                            class="ml-public-button ml-public-button--primary"
                            type="submit"
                            data-public-submit
                        >
                            <i class="bi bi-calendar2-check" aria-hidden="true"></i>
                            <span data-public-submit-label>Buat Reservasi</span>
                        </button>
                    </footer>
                </form>
            </article>

            <aside class="ml-reservation-sidebar">
                <article class="ml-public-card">
                    <header class="ml-public-card__header">
                        <div class="ml-public-card__title-wrap">
                            <h2 class="ml-public-card__title">Ringkasan Reservasi</h2>
                            <p class="ml-public-card__description">
                                Ringkasan akan diperbarui sesuai pilihan form.
                            </p>
                        </div>
                    </header>

                    <div class="ml-public-card__body">
                        <div class="ml-reservation-summary">
                            <div class="ml-reservation-summary__item">
                                <span>Pasien</span>
                                <strong>{{ $patient->full_name }}</strong>
                            </div>
                            <div class="ml-reservation-summary__item">
                                <span>Layanan</span>
                                <strong data-reservation-summary-service>
                                    {{ $selectedLabTest?->name ?? 'Belum dipilih' }}
                                </strong>
                            </div>
                            <div class="ml-reservation-summary__item">
                                <span>Harga</span>
                                <strong data-reservation-summary-price>
                                    {{ $selectedLabTest
                                        ? 'Rp ' . number_format((float) $selectedLabTest->price, 0, ',', '.')
                                        : '-' }}
                                </strong>
                            </div>
                            <div class="ml-reservation-summary__item">
                                <span>Tanggal</span>
                                <strong data-reservation-summary-date>
                                    {{ old('reservation_date') ?: 'Belum dipilih' }}
                                </strong>
                            </div>
                            <div class="ml-reservation-summary__item">
                                <span>Jam</span>
                                <strong data-reservation-summary-time>
                                    {{ old('reservation_time')
                                        ? old('reservation_time') . ' WIB'
                                        : 'Belum dipilih' }}
                                </strong>
                            </div>
                            <div class="ml-reservation-summary__item">
                                <span>Status awal</span>
                                <x-status-badge status="Menunggu" />
                            </div>
                        </div>
                    </div>
                </article>

                <article class="ml-public-card">
                    <header class="ml-public-card__header">
                        <div class="ml-public-card__title-wrap">
                            <h2 class="ml-public-card__title">Informasi Jadwal</h2>
                        </div>
                    </header>

                    <div class="ml-public-card__body">
                        <ul class="ml-result-instructions">
                            <li>
                                <i class="bi bi-clock" aria-hidden="true"></i>
                                <span>Slot tersedia mulai pukul 07.00–19.00 WIB.</span>
                            </li>
                            <li>
                                <i class="bi bi-calendar-check" aria-hidden="true"></i>
                                <span>Tanggal tidak boleh sebelum hari ini.</span>
                            </li>
                            <li>
                                <i class="bi bi-check2-circle" aria-hidden="true"></i>
                                <span>Periksa kembali detail sebelum menyimpan.</span>
                            </li>
                        </ul>
                    </div>
                </article>
            </aside>
        </div>
    </section>
@endsection
