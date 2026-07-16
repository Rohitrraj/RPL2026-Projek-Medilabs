@extends('layouts.app')

@section('title', ($patient ? 'Edit' : 'Lengkapi') . ' Data Pasien | MediLabs')

@section('content')
    <section class="ml-public-page">
        <header class="ml-public-page-header">
            <div class="ml-public-page-header__copy">
                <span class="ml-public-eyebrow">
                    <i class="bi bi-person-vcard" aria-hidden="true"></i>
                    Data pasien
                </span>

                <h1 class="ml-public-page-title">
                    {{ $patient ? 'Edit Data Pasien' : 'Lengkapi Data Pasien' }}
                </h1>

                <p class="ml-public-page-description">
                    Pastikan identitas pasien sesuai dengan dokumen resmi karena
                    data ini digunakan pada proses reservasi dan pemeriksaan.
                </p>
            </div>

            @if ($patient)
                <div class="ml-public-page-actions">
                    <a
                        class="ml-public-button ml-public-button--outline"
                        href="{{ route('profile.show') }}"
                    >
                        <i class="bi bi-arrow-left" aria-hidden="true"></i>
                        Kembali ke Profil
                    </a>
                </div>
            @endif
        </header>

        <div class="ml-patient-layout">
            <article class="ml-public-card">
                <header class="ml-public-card__header">
                    <div class="ml-profile-card-heading">
                        <span class="ml-public-icon-box" aria-hidden="true">
                            <i class="bi bi-clipboard2-pulse"></i>
                        </span>

                        <div class="ml-public-card__title-wrap">
                            <h2 class="ml-public-card__title">Form Data Pasien</h2>
                            <p class="ml-public-card__description">
                                Kolom bertanda wajib harus diisi sebelum data disimpan.
                            </p>
                        </div>
                    </div>
                </header>

                <form
                    class="ml-public-form"
                    action="{{ route('patients.store') }}"
                    method="POST"
                    data-public-form
                >
                    @csrf

                    <div class="ml-public-card__body">
                        <div class="ml-public-form-grid">
                            <div class="ml-public-field ml-public-field--full">
                                <label class="ml-public-label" for="full_name">
                                    Nama Lengkap
                                </label>

                                <input
                                    id="full_name"
                                    class="ml-public-input {{ $errors->has('full_name') ? 'is-invalid' : '' }}"
                                    type="text"
                                    name="full_name"
                                    value="{{ old('full_name', $patient->full_name ?? auth()->user()?->name ?? '') }}"
                                    placeholder="Masukkan nama lengkap pasien"
                                    autocomplete="name"
                                    maxlength="100"
                                    required
                                    autofocus
                                    aria-invalid="{{ $errors->has('full_name') ? 'true' : 'false' }}"
                                    @error('full_name') aria-describedby="full-name-error" @enderror
                                >

                                @error('full_name')
                                    <p id="full-name-error" class="ml-public-field-error">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="ml-public-field">
                                <label class="ml-public-label" for="nik">NIK</label>

                                <input
                                    id="nik"
                                    class="ml-public-input {{ $errors->has('nik') ? 'is-invalid' : '' }}"
                                    type="text"
                                    name="nik"
                                    value="{{ old('nik', $patient->nik ?? '') }}"
                                    placeholder="Masukkan NIK pasien"
                                    inputmode="numeric"
                                    autocomplete="off"
                                    maxlength="30"
                                    required
                                    aria-invalid="{{ $errors->has('nik') ? 'true' : 'false' }}"
                                    @error('nik') aria-describedby="nik-error" @enderror
                                >

                                @error('nik')
                                    <p id="nik-error" class="ml-public-field-error">
                                        {{ $message }}
                                    </p>
                                @else
                                    <p class="ml-public-help-text">
                                        Gunakan NIK yang belum digunakan oleh akun lain.
                                    </p>
                                @enderror
                            </div>

                            <div class="ml-public-field">
                                <label class="ml-public-label" for="gender">
                                    Jenis Kelamin
                                </label>

                                <select
                                    id="gender"
                                    class="ml-public-select {{ $errors->has('gender') ? 'is-invalid' : '' }}"
                                    name="gender"
                                    required
                                    aria-invalid="{{ $errors->has('gender') ? 'true' : 'false' }}"
                                    @error('gender') aria-describedby="gender-error" @enderror
                                >
                                    <option value="">Pilih jenis kelamin</option>
                                    @foreach (['Laki-laki', 'Perempuan'] as $gender)
                                        <option
                                            value="{{ $gender }}"
                                            @selected(old('gender', $patient->gender ?? '') === $gender)
                                        >
                                            {{ $gender }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('gender')
                                    <p id="gender-error" class="ml-public-field-error">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="ml-public-field">
                                <div class="ml-public-field__header">
                                    <label class="ml-public-label" for="birth_date">
                                        Tanggal Lahir
                                    </label>
                                    <span class="ml-public-optional">Opsional</span>
                                </div>

                                <input
                                    id="birth_date"
                                    class="ml-public-input {{ $errors->has('birth_date') ? 'is-invalid' : '' }}"
                                    type="date"
                                    name="birth_date"
                                    value="{{ old('birth_date', isset($patient?->birth_date) ? $patient->birth_date->format('Y-m-d') : '') }}"
                                    autocomplete="bday"
                                    aria-invalid="{{ $errors->has('birth_date') ? 'true' : 'false' }}"
                                    @error('birth_date') aria-describedby="birth-date-error" @enderror
                                >

                                @error('birth_date')
                                    <p id="birth-date-error" class="ml-public-field-error">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="ml-public-field">
                                <label class="ml-public-label" for="phone">
                                    Nomor Telepon Pasien
                                </label>

                                <input
                                    id="phone"
                                    class="ml-public-input {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                    type="tel"
                                    name="phone"
                                    value="{{ old('phone', $patient->phone ?? auth()->user()?->phone ?? '') }}"
                                    placeholder="Contoh: 081234567890"
                                    autocomplete="tel"
                                    inputmode="tel"
                                    maxlength="20"
                                    required
                                    aria-invalid="{{ $errors->has('phone') ? 'true' : 'false' }}"
                                    @error('phone') aria-describedby="phone-error" @enderror
                                >

                                @error('phone')
                                    <p id="phone-error" class="ml-public-field-error">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="ml-public-field ml-public-field--full">
                                <div class="ml-public-field__header">
                                    <label class="ml-public-label" for="address">
                                        Alamat
                                    </label>
                                    <span class="ml-public-optional">Opsional</span>
                                </div>

                                <textarea
                                    id="address"
                                    class="ml-public-textarea {{ $errors->has('address') ? 'is-invalid' : '' }}"
                                    name="address"
                                    placeholder="Masukkan alamat pasien"
                                    autocomplete="street-address"
                                    aria-invalid="{{ $errors->has('address') ? 'true' : 'false' }}"
                                    @error('address') aria-describedby="address-error" @enderror
                                >{{ old('address', $patient->address ?? '') }}</textarea>

                                @error('address')
                                    <p id="address-error" class="ml-public-field-error">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="ml-public-field">
                                <div class="ml-public-field__header">
                                    <label class="ml-public-label" for="blood_type">
                                        Golongan Darah
                                    </label>
                                    <span class="ml-public-optional">Opsional</span>
                                </div>

                                <select
                                    id="blood_type"
                                    class="ml-public-select {{ $errors->has('blood_type') ? 'is-invalid' : '' }}"
                                    name="blood_type"
                                    aria-invalid="{{ $errors->has('blood_type') ? 'true' : 'false' }}"
                                    @error('blood_type') aria-describedby="blood-type-error" @enderror
                                >
                                    <option value="">Pilih golongan darah</option>
                                    @foreach (['A', 'B', 'AB', 'O'] as $bloodType)
                                        <option
                                            value="{{ $bloodType }}"
                                            @selected(old('blood_type', $patient->blood_type ?? '') === $bloodType)
                                        >
                                            {{ $bloodType }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('blood_type')
                                    <p id="blood-type-error" class="ml-public-field-error">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <footer class="ml-public-card__footer">
                        @if ($patient)
                            <a
                                class="ml-public-button ml-public-button--outline"
                                href="{{ route('profile.show') }}"
                            >
                                Batal
                            </a>
                        @endif

                        <button
                            class="ml-public-button ml-public-button--primary"
                            type="submit"
                            data-public-submit
                        >
                            <i class="bi bi-check2-circle" aria-hidden="true"></i>
                            <span data-public-submit-label>
                                {{ $patient ? 'Perbarui Data Pasien' : 'Simpan Data Pasien' }}
                            </span>
                        </button>
                    </footer>
                </form>
            </article>

            <aside class="ml-patient-sidebar">
                <div class="ml-patient-illustration" aria-hidden="true">
                    <img
                        src="{{ asset('assets/images/formdatapasien.png') }}"
                        alt=""
                    >
                </div>

                <article class="ml-public-card">
                    <header class="ml-public-card__header">
                        <div class="ml-public-card__title-wrap">
                            <h2 class="ml-public-card__title">Sebelum Menyimpan</h2>
                            <p class="ml-public-card__description">
                                Periksa kembali data pasien untuk mencegah kesalahan.
                            </p>
                        </div>
                    </header>

                    <div class="ml-public-card__body">
                        <ul class="ml-patient-guidance">
                            <li>
                                <i class="bi bi-check-circle" aria-hidden="true"></i>
                                <span>Nama dan NIK sesuai dokumen identitas pasien.</span>
                            </li>
                            <li>
                                <i class="bi bi-check-circle" aria-hidden="true"></i>
                                <span>Nomor telepon aktif dan dapat dihubungi.</span>
                            </li>
                            <li>
                                <i class="bi bi-shield-check" aria-hidden="true"></i>
                                <span>Data digunakan untuk kebutuhan reservasi MediLabs.</span>
                            </li>
                        </ul>
                    </div>
                </article>
            </aside>
        </div>
    </section>
@endsection
