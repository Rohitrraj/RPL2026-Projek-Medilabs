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

    if ($redirect = $this->ensureAuthenticated(

        'Silakan login terlebih dahulu sebelum membuat reservasi.'

    )) {

        return $redirect;

    }

    $patient = Patient::where('user_id', Auth::id())->first();

    if (! $patient) {

        return redirect()

            ->route('patients.create')

            ->with('success', 'Silakan isi data pasien terlebih dahulu sebelum membuat reservasi.');

    }

    $labTests = $this->activeLabTests();

    $hours = $this->availableHours();

    return view('reservations.create', compact('patient', 'labTests', 'hours'));

}

public function store(Request $request): RedirectResponse

{

    if ($redirect = $this->ensureAuthenticated(

        'Silakan login terlebih dahulu sebelum membuat reservasi.'

    )) {

        return $redirect;

    }

    $validated = $request->validate([

        'lab_test_id' => ['required', 'exists:lab_tests,id'],

        'reservation_date' => ['required', 'date'],

        'reservation_time' => ['required'],

        'notes' => ['nullable', 'string'],

    ]);

    $patient = $this->currentPatient();

    $sequence = $this->nextSequenceNumber();

    $reservation = Reservation::create([

        'code' => $this->generateReservationCode($sequence),

        'patient_id' => $patient->id,

        'lab_test_id' => $validated['lab_test_id'],

        'reservation_date' => $validated['reservation_date'],

        'reservation_time' => $validated['reservation_time'],

        'queue_number' => $this->generateQueueNumber($sequence),

        'status' => 'Menunggu',

        'notes' => $validated['notes'] ?? null,

    ]);

    return redirect()

        ->route('reservations.result', $reservation)

        ->with('success', 'Reservasi berhasil dibuat.');

}

public function result(Reservation $reservation): View
{
    $this->ensureReservationOwnedByCurrentUser($reservation);

    $reservation->loadMissing(['patient', 'labTest']);

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
            $reservation = $this->latestReservationForCurrentUser();
        }

        return view('reservations.status', [
            'reservation' => $reservation,
            'reservationData' => $reservation ? $this->reservationDetailArray($reservation) : [],
        ]);
    }

public function history(Request $request): View
{
    $query = Reservation::with(['patient', 'labTest'])
        ->whereHas('patient', function ($patientQuery) {
            $patientQuery->where('user_id', Auth::id());
        })
        ->latest();

    $query = $this->applyHistoryFilters($query, $request);

    $reservations = $query->get();

    return view('reservations.history', compact('reservations'));
}

public function destroy(Reservation $reservation): RedirectResponse
{
    $this->ensureReservationOwnedByCurrentUser($reservation);

    $reservation->delete();

    return redirect()
        ->route('reservations.history')
        ->with('success', 'Reservasi berhasil dihapus dari riwayat.');
}

private function ensureReservationOwnedByCurrentUser(
    Reservation $reservation
): void {
    $reservation->loadMissing('patient');

    $owned = $reservation->patient
        && (int) $reservation->patient->user_id === (int) Auth::id();

    abort_unless(
        $owned,
        403,
        'Reservasi ini bukan milik akun Anda.'
    );
    
}

    private function currentPatient(): Patient

{

    return Patient::where('user_id', Auth::id())->firstOrFail();

}
    private function ensureAuthenticated(string $message): ?RedirectResponse
    {
        if (Auth::check()) {
            return null;
        }

        return redirect()
            ->route('login')
            ->with('success', $message);
    }

    private function findOwnedPatient(int $patientId): Patient
    {
        return Patient::where('id', $patientId)
            ->where('user_id', Auth::id())
            ->firstOrFail();
    }

    private function activeLabTests()
    {
        return LabTest::where('status', 'active')
            ->orderBy('name')
            ->get();
    }

    private function nextSequenceNumber(): int
    {
        $latestReservationId = Reservation::query()->max('id') ?? 0;

        return $latestReservationId + 1;
    }

    private function generateReservationCode(int $sequence): string
    {
        return 'A' . str_pad((string) $sequence, 3, '0', STR_PAD_LEFT);
    }

    private function generateQueueNumber(int $sequence): string
    {
        return 'A-' . str_pad((string) $sequence, 2, '0', STR_PAD_LEFT);
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

    private function applyHistoryFilters($query, Request $request)
    {
        if ($request->filled('code')) {
            $query->where('code', 'like', '%' . $request->code . '%');
        }

        return $query;
    }

    private function latestReservationForCurrentUser(): ?Reservation
    {
        return Reservation::with(['patient', 'labTest'])
            ->whereHas('patient', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->latest()
            ->first();
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