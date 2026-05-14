@extends('layouts.app')

@section('title', 'MediLabs - Form Data Pasien')

@section('content')
    <section class="two-column-layout">
        <form class="dark-panel patient-panel" action="{{ route('reservations.create') }}" method="get">
            <h1>Form Data Pasien</h1>

            <label>
                <span>Nama Lengkap</span>
                <input type="text" name="name">
            </label>

            <label>
                <span>NIK</span>
                <input type="text" name="nik">
            </label>

            <label>
                <span>Jenis Kelamin</span>
                <input type="text" name="gender">
            </label>

            <label>
                <span>Tanggal Lahir</span>
                <input type="text" name="birth_date">
            </label>

            <label>
                <span>No. Telepon</span>
                <input type="tel" name="phone">
            </label>

            <label>
                <span>Alamat</span>
                <textarea name="address"></textarea>
            </label>

            <label>
                <span>Golongan Darah</span>
                <input type="text" name="blood_type">
            </label>

            <button class="button button-primary form-button" type="submit">Simpan Data</button>
        </form>

        <aside class="patient-info-card">
            <img src="{{ asset('assets/images/patient-info.svg') }}" alt="Ikon data pasien">
            <p>Pastikan data pasien diisi dengan benar. Data akan digunakan untuk proses reservasi dan pemeriksaan.</p>
        </aside>
    </section>
@endsection
