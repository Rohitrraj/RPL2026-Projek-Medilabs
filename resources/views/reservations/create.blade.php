@extends('layouts.app')

@section('title', 'MediLabs - Form Reservasi')

@section('content')
    <section class="reservation-layout">
        <form class="dark-panel reservation-panel" action="{{ route('reservations.result') }}" method="get">
            <h1>Form Reservasi</h1>

            <label>
                <span>Pilih Pasien</span>
                <select name="patient">
                    <option value="">Pilih Pasien</option>
                    @foreach ($patients as $patient)
                        <option value="{{ $patient }}">{{ $patient }}</option>
                    @endforeach
                </select>
            </label>

            <label>
                <span>Pilih Jenis Tes</span>
                <select name="test">
                    <option value="">Pilih jenis tes</option>
                    @foreach ($tests as $test)
                        <option value="{{ $test }}">{{ $test }}</option>
                    @endforeach
                </select>
            </label>

            <label>
                <span>Pilih Tanggal</span>
                <input type="date" name="date">
            </label>

            <label>
                <span>Pilih Jam</span>
                <select name="hour">
                    <option value="">Pilih jam</option>
                    @foreach ($hours as $hour)
                        <option value="{{ $hour }}">{{ $hour }}</option>
                    @endforeach
                </select>
            </label>

            <label class="wide-label">
                <span>Catatan / Keluhan</span>
                <textarea name="note" placeholder="Tulis catatan atau keluhan (opsional)"></textarea>
            </label>

            <button class="button button-primary form-button" type="submit">Buat Reservasi</button>
        </form>

        <aside class="reservation-media">
            <img class="staff-image" src="{{ asset('assets/images/formreservasi.png') }}" alt="Petugas klinik memproses reservasi">
        </aside>
    </section>
@endsection
