@extends('layouts.app')

@section('title', 'MediLabs - Hasil Reservasi')

@section('content')
    <section class="result-layout">
        <article class="dark-panel result-card">
            <h1>Hasil Reservasi</h1>

            <div class="result-body">
                <div class="checkmark" aria-hidden="true"></div>
                <dl>
                    @foreach ($reservation as $label => $value)
                        <div>
                            <dt>{{ $label }}</dt>
                            <dd>{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>
        </article>

        <aside class="result-actions">
            <button class="button button-secondary" type="button" data-print>Cetak Bukti</button>
            <a class="button button-dark" href="{{ route('reservations.create') }}">Kembali</a>
        </aside>
    </section>
@endsection
