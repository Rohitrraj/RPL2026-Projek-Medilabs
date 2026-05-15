@extends('layouts.app')

@section('title', 'MediLabs - Form Data Pasien')

@section('content')
    <section class="two-column-layout">
        <form class="dark-panel patient-panel" action="{{ route('patients.store') }}" method="POST">
            @csrf
            <h1>Form Data Pasien</h1>

            <label>
                <span>Nama Lengkap</span>
                <input type="text" name="full_name" value="{{ old('full_name', $patient->full_name ?? auth()->user()?->name ?? '') }}" required>
            </label>

            <label>
                <span>NIK</span>
                <input type="text" name="nik" value="{{ old('nik', $patient->nik ?? '') }}" required>
            </label>

            <label>
                <span>Jenis Kelamin</span>
                <select name="gender" required>
                    <option value="">Pilih jenis kelamin</option>
                    @foreach (['Laki-laki', 'Perempuan'] as $gender)
                        <option value="{{ $gender }}" @selected(old('gender', $patient->gender ?? '') === $gender)>{{ $gender }}</option>
                    @endforeach
                </select>
            </label>

            <label>
                <span>Tanggal Lahir</span>
                <input type="date" name="birth_date" value="{{ old('birth_date', isset($patient?->birth_date) ? $patient->birth_date->format('Y-m-d') : '') }}">
            </label>

            <label>
                <span>No. Telepon</span>
                <input type="tel" name="phone" value="{{ old('phone', $patient->phone ?? auth()->user()?->phone ?? '') }}" required>
            </label>

            <label>
                <span>Alamat</span>
                <textarea name="address">{{ old('address', $patient->address ?? '') }}</textarea>
            </label>

            <label>
                <span>Golongan Darah</span>
                <select name="blood_type">
                    <option value="">Pilih golongan darah</option>
                    @foreach (['A', 'B', 'AB', 'O'] as $bloodType)
                        <option value="{{ $bloodType }}" @selected(old('blood_type', $patient->blood_type ?? '') === $bloodType)>{{ $bloodType }}</option>
                    @endforeach
                </select>
            </label>

            <button class="button button-primary form-button" type="submit">Simpan Data</button>
        </form>

        <aside class="patient-info-card">
            <img src="{{ asset('assets/images/formdatapasien.png') }}" alt="Ikon data pasien">
            <p>Pastikan data pasien diisi dengan benar. Data akan digunakan untuk proses reservasi dan pemeriksaan.</p>
        </aside>
    </section>
@endsection
