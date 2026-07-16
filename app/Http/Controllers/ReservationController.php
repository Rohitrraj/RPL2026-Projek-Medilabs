<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Models\LabTest;
use App\Models\Patient;
use App\Models\Reservation;
use App\Services\ReservationService;
use App\Support\ReservationSchedule;
use App\Support\ReservationStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
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

    $hours = ReservationSchedule::availableHours();

    return view('reservations.create', compact('patient', 'labTests', 'hours'));

}

public function store(
    StoreReservationRequest $request,
    ReservationService $reservationService
): RedirectResponse {
    $reservation = $reservationService->createForPatient(
        $this->currentPatient(),
        $request->validated()
    );

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
        $validated = $request->validate([
            'code' => ['nullable', 'string', 'max:20'],
        ]);

        $requestedCode = strtoupper(trim((string) ($validated['code'] ?? '')));

        $query = Reservation::with(['patient', 'labTest'])
            ->whereHas('patient', function ($patientQuery) {
                $patientQuery->where('user_id', Auth::id());
            });

        $reservation = $requestedCode !== ''
            ? $query->where('code', $requestedCode)->first()
            : $query->latest()->first();

        return view('reservations.status', [
            'reservation' => $reservation,
            'reservationData' => $reservation
                ? $this->reservationDetailArray($reservation)
                : [],
            'requestedCode' => $requestedCode,
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

public function cancel(
    Reservation $reservation
): RedirectResponse {
    $this->ensureReservationOwnedByCurrentUser($reservation);

    if (! ReservationStatus::canBeCancelledByPatient(
        $reservation->status
    )) {
        throw ValidationException::withMessages([
            'reservation' => [
                'Reservasi dengan status '
                . $reservation->status
                . ' tidak dapat dibatalkan.',
            ],
        ]);
    }

    $reservation->update([
        'status' => ReservationStatus::CANCELLED,
    ]);

    return redirect()
        ->route('reservations.history')
        ->with('success', 'Reservasi berhasil dibatalkan.');
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



    private function applyHistoryFilters($query, Request $request)
    {
        if ($request->filled('code')) {
            $query->where('code', 'like', '%' . $request->code . '%');
        }

        return $query;
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