@extends('layouts.app')

@section('title', 'MediLabs - Layanan Populer')

@section('content')
    <section class="service-detail-layout">
        <article class="dark-panel service-detail-card">
            <h1>Hematologi Lengkap</h1>

            <div class="service-info-block">
                <span class="line-icon clipboard-icon"></span>
                <div>
                    <h2>Nama Layanan</h2>
                    <p>Hematologi Lengkap</p>
                </div>
            </div>

            <div class="service-info-block">
                <span class="line-icon list-icon"></span>
                <div>
                    <h2>Deskripsi</h2>
                    <p>Hematologi lengkap adalah pemeriksaan darah komprehensif untuk mengevaluasi komponen darah, seperti hemoglobin, eritrosit, leukosit, trombosit, dan hematokrit.</p>
                </div>
            </div>

            <div class="service-info-block">
                <span class="line-icon pulse-icon"></span>
                <div>
                    <h2>Manfaat Pemeriksaan</h2>
                    <p>Tes ini membantu mendeteksi infeksi, anemia, leukemia, hingga gangguan pembekuan darah.</p>
                </div>
            </div>

            <div class="service-info-block">
                <span class="line-icon calendar-icon"></span>
                <div>
                    <h2>Persiapan Sebelum Pemeriksaan</h2>
                    <p>Puasa tidak diperlukan, namun pasien disarankan menjaga kondisi tubuh dan mengikuti arahan petugas.</p>
                </div>
            </div>

            <div class="service-info-block">
                <span class="line-icon tag-icon"></span>
                <div>
                    <h2>Estimasi Harga</h2>
                    <p>Rp.145.000</p>
                </div>
            </div>

            <a class="button button-primary service-button" href="{{ route('patients.create') }}">Reservasi Sekarang</a>
        </article>

        <aside class="service-side">
            <img class="lab-image" src="{{ asset('assets/images/lab.svg') }}" alt="Pemeriksaan sampel di laboratorium">

            <div class="white-info-card">
                <span class="circle-icon clock-symbol"></span>
                <div>
                    <h2>Jadwal Tersedia</h2>
                    <p>Senin - Minggu<br>07.00 - 19.00 WIB</p>
                </div>
            </div>

            <div class="white-info-card">
                <span class="circle-icon info-symbol"></span>
                <div>
                    <h2>Informasi</h2>
                    <p>Hasil pemeriksaan biasanya tersedia dalam 1x24 jam kerja.</p>
                </div>
            </div>
        </aside>
    </section>
@endsection
