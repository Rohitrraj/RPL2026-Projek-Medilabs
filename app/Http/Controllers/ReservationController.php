<?php

namespace App\Http\Controllers;

use App\Models\LabTest;
use App\Models\Patient;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function create(): View
    {
        // Tahap 25%: tampilkan seluruh data pasien yang sudah masuk database
        // agar bukti koneksi frontend -> backend -> database mudah diverifikasi.
        // Pembatasan per akun pasien dapat dikembangkan pada tahap role/auth lanjutan.
        $patients = Patient::latest()->get();

        $labTests = LabTest::where('status', 'active')->orderBy('name')->get();
        $hours = $this->availableHours();

        return view('reservations.create', compact('patients', 'labTests', 'hours'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'lab_test_id' => ['required', 'exists:lab_tests,id'],
            'reservation_date' => ['required', 'date'],
            'reservation_time' => ['required'],
            'notes' => ['nullable', 'string'],
        ]);

        $reservation = Reservation::create([
            'code' => $this->generateReservationCode(),
            'patient_id' => $validated['patient_id'],
            'lab_test_id' => $validated['lab_test_id'],
            'reservation_date' => $validated['reservation_date'],
            'reservation_time' => $validated['reservation_time'],
            'queue_number' => $this->generateQueueNumber(),
            'status' => 'Menunggu',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('reservations.result', $reservation)
            ->with('success', 'Reservasi berhasil dibuat dan tersimpan ke database.');
    }

    public function result(Reservation $reservation): View
    {
        $reservation->load(['patient', 'labTest']);

        return view('reservations.result', [
            'reservation' => $reservation,
            'reservationData' => $this->reservationDetailArray($reservation),
        ]);
    }

    public function status(Request $request): View
    {
        $reservation = null;

        if ($request->filled('code')) {
            $reservation = Reservation::with(['patient', 'labTest'])
                ->where('code', $request->code)
                ->first();
        }

        if (! $reservation) {
            $reservation = Reservation::with(['patient', 'labTest'])->latest()->first();
        }

        return view('reservations.status', [
            'reservation' => $reservation,
            'reservationData' => $reservation ? $this->reservationDetailArray($reservation) : [],
        ]);
    }

    public function history(Request $request): View
    {
        $query = Reservation::with(['patient', 'labTest'])->latest();

        if ($request->filled('code')) {
            $query->where('code', 'like', '%' . $request->code . '%');
        }

        if (Auth::check() && Auth::user()->role !== 'admin') {
            $query->whereHas('patient', fn ($patientQuery) => $patientQuery->where('user_id', Auth::id()));
        }

        $reservations = $query->get();

        return view('reservations.history', compact('reservations'));
    }

    private function availableHours(): array
    {
        $hours = [];
        $start = strtotime('08:00');
        $end = strtotime('15:00');

        for ($time = $start; $time <= $end; $time += 30 * 60) {
            $hours[] = date('H:i', $time);
        }

        return $hours;
    }

    private function generateReservationCode(): string
    {
        $nextNumber = Reservation::count() + 1;

        return 'A' . str_pad((string) $nextNumber, 5, '0', STR_PAD_LEFT);
    }

    private function generateQueueNumber(): string
    {
        return 'A-' . str_pad((string) (Reservation::count() + 1), 2, '0', STR_PAD_LEFT);
    }

    private function reservationDetailArray(Reservation $reservation): array
    {
        return [
            'Kode Reservasi' => $reservation->code,
            'Nama Pasien' => $reservation->patient->full_name ?? '-',
            'Jenis Tes' => $reservation->labTest->name ?? '-',
            'Tanggal' => optional($reservation->reservation_date)->format('d M Y'),
            'Jam' => substr((string) $reservation->reservation_time, 0, 5),
            'Nomor Antrian' => $reservation->queue_number ?? '-',
            'Status' => $reservation->status,
        ];
    }
}
