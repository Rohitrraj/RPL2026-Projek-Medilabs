@extends('layouts.app')

@section('title', 'MediLabs - Hasil Reservasi')

@section('content')

    <section class="result-layout">

        <div class="dark-panel result-card print-card">

            <div class="print-header">

                <div>

                    <h1>Hasil Reservasi</h1>

                    <p>Bukti reservasi pemeriksaan laboratorium MediLabs.</p>

                </div>

                <div class="print-actions no-print">

                    <button type="button" class="button button-primary" onclick="window.print()">

                        Cetak Bukti

                    </button>

                </div>

            </div>

            <div class="print-body">

                <div class="print-checkmark" aria-hidden="true">✓</div>

                <div class="result-body">

                    <dl>

                        @foreach ($reservationData as $label => $value)

                            <div>

                                <dt>{{ $label }}</dt>

                                <dd>{{ $value }}</dd>

                            </div>

                        @endforeach

                    </dl>

                </div>

            </div>

            <div class="print-footer">

                <p>Harap datang 15 menit sebelum jadwal pemeriksaan.</p>

                <p>Simpan bukti ini untuk verifikasi di laboratorium.</p>

            </div>

        </div>

    </section>

@endsection