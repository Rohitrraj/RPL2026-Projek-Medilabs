@extends('layouts.app')

@section('title', 'MediLabs - Form Reservasi')

@section('content')
    <section class="reservation-layout">
        <div class="dark-panel reservation-panel">
            <h1>Form Reservasi</h1>

            <form action="{{ route('reservations.store') }}" method="POST">
                @csrf

                <label>
                    <span>Nama Pasien</span>
                    <input type="text" value="{{ $patient->full_name }}" readonly>
                </label>

                <label>
                    <span>Pilih Jenis Tes</span>
                    <select name="lab_test_id" required>
                        <option value="">Pilih jenis tes</option>
                        @foreach ($labTests as $labTest)
                            <option value="{{ $labTest->id }}" @selected(old('lab_test_id') == $labTest->id)>
                                {{ $labTest->name }}
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
                            <option value="{{ $hour }}" @selected(old('reservation_time') == $hour)>
                                {{ $hour }}
                            </option>
                        @endforeach
                    </select>
                </label>

                <label>
                    <span>Catatan / Keluhan</span>
                    <textarea name="notes" rows="4" placeholder="Tulis catatan atau keluhan (opsional)">{{ old('notes') }}</textarea>
                </label>

                <button class="button button-primary" type="submit">Buat Reservasi</button>
            </form>
        </div>
        <aside class="reservation-media">
            <img class="staff-image" src="{{ asset('assets/images/formreservasi.png') }}" alt="Petugas klinik memproses reservasi">
        </aside>
    </section>
@endsection
