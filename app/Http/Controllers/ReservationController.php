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
    public function create(): View|RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()
                ->route('login')
                ->with('success', 'Silakan login terlebih dahulu sebelum membuat reservasi.');
        }

        $patients = Patient::where('user_id', Auth::id())->latest()->get();

        if ($patients->isEmpty()) {
            return redirect()
                ->route('patients.create')
                ->with('success', 'Silakan isi data pasien terlebih dahulu sebelum membuat reservasi.');
        }

        $labTests = LabTest::where('status', 'active')->orderBy('name')->get();
        $hours = $this->availableHours();

        return view('reservations.create', compact('patients', 'labTests', 'hours'));
    }

    public function store(Request $request): RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()
                ->route('login')
                ->with('success', 'Silakan login terlebih dahulu sebelum membuat reservasi.');
        }

        $validated = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'lab_test_id' => ['required', 'exists:lab_tests,id'],
            'reservation_date' => ['required', 'date'],
            'reservation_time' => ['required'],
            'notes' => ['nullable', 'string'],
        ]);

        $patient = Patient::where('id', $validated['patient_id'])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $reservation = Reservation::create([
            'code' => $this->generateReservationCode(),
            'patient_id' => $patient->id,
            'lab_test_id' => $validated['lab_test_id'],
            'reservation_date' => $validated['reservation_date'],
            'reservation_time' => $validated['reservation_time'],
            'queue_number' => $this->generateQueueNumber(),
            'status' => 'Menunggu',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('reservations.result', $reservation)
            ->with('success', 'Reservasi berhasil dibuat.');
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

        if (! $reservation && Auth::check()) {
            $reservation = Reservation::with(['patient', 'labTest'])
                ->whereHas('patient', fn ($query) => $query->where('user_id', Auth::id()))
                ->latest()
                ->first();
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

    private function generateReservationCode(): string
    {
        $nextNumber = Reservation::count() + 1;

        return 'A' . str_pad((string) $nextNumber, 5, '0', STR_PAD_LEFT);
    }

    private function generateQueueNumber(): string
    {
        return 'A-' . str_pad((string) (Reservation::count() + 1), 2, '0', STR_PAD_LEFT);
    }

    private function availableHours(): array
    {
        $hours = [];

        for ($hour = 7; $hour <= 19; $hour++) {
            foreach (['00', '30'] as $minute) {
                if ($hour === 19 && $minute === '30') {
                    continue;
                }

                $hours[] = sprintf('%02d:%s', $hour, $minute);
            }
        }

        return $hours;
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
