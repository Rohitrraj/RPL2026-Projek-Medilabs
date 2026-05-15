@extends('layouts.app')

@section('title', 'MediLabs - Form Reservasi')

@section('content')
    <section class="reservation-layout">
        <form class="dark-panel reservation-panel" action="{{ route('reservations.store') }}" method="POST">
            @csrf
            <h1>Form Reservasi</h1>

            <label>
                <span>Pilih Pasien</span>
                <select name="patient_id" required>
                    <option value="">Pilih Pasien</option>
                    @foreach ($patients as $patient)
                        <option value="{{ $patient->id }}" @selected(old('patient_id', session('current_patient_id')) == $patient->id)>
                            {{ $patient->full_name }}
                        </option>
                    @endforeach
                </select>
            </label>

            @if ($patients->isEmpty())
                <p class="form-helper-note">
                    Data pasien belum tersedia. Silakan isi <a href="{{ route('patients.create') }}">Form Data Pasien</a> terlebih dahulu.
                </p>
            @endif

            <label>
                <span>Pilih Jenis Tes</span>
                <select name="lab_test_id" required>
                    <option value="">Pilih jenis tes</option>
                    @foreach ($labTests as $test)
                        <option value="{{ $test->id }}" @selected(old('lab_test_id') == $test->id)>
                            {{ $test->name }}
                        </option>
                    @endforeach
                </select>
            </label>

            <label>
                <span>Pilih Tanggal</span>
                <input type="date" name="reservation_date" value="{{ old('reservation_date') }}" required>
            </label>

            <label>
                <span>Pilih Jam</span>
                <select name="reservation_time" required>
                    <option value="">Pilih jam</option>
                    @foreach ($hours as $hour)
                        <option value="{{ $hour }}" @selected(old('reservation_time') === $hour)>{{ $hour }}</option>
                    @endforeach
                </select>
            </label>

            <label class="wide-label">
                <span>Catatan / Keluhan</span>
                <textarea name="notes" placeholder="Tulis catatan atau keluhan (opsional)">{{ old('notes') }}</textarea>
            </label>

            <button class="button button-primary form-button" type="submit">Buat Reservasi</button>
        </form>

        <aside class="reservation-media">
            <img class="staff-image" src="{{ asset('assets/images/formreservasi.png') }}" alt="Petugas klinik memproses reservasi">
        </aside>
    </section>
@endsection
