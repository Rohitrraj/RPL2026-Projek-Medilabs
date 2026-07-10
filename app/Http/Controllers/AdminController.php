<?php

namespace App\Http\Controllers;

use App\Models\LabTest;
use App\Models\Patient;
use App\Models\Reservation;
use App\Support\ReservationStatus;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use App\Support\ReservationPeriod;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
public function dashboard(): View
{
    $todayPeriod = ReservationPeriod::resolve(
        ReservationPeriod::TODAY
    );

    $weekPeriod = ReservationPeriod::resolve(
        ReservationPeriod::WEEK
    );

    $monthPeriod = ReservationPeriod::resolve(
        ReservationPeriod::MONTH
    );

    $reservations = Reservation::with(['patient', 'labTest'])
        ->orderByDesc('reservation_date')
        ->orderByDesc('reservation_time')
        ->limit(6)
        ->get();

$stats = [
    'Total Reservasi' => Reservation::count(),

    'Hari Ini' => Reservation::whereDate(
        'reservation_date',
        $todayPeriod['start_date']
    )->count(),

    'Minggu Ini' => Reservation::query()
        ->whereDate(
            'reservation_date',
            '>=',
            $weekPeriod['start_date']
        )
        ->whereDate(
            'reservation_date',
            '<=',
            $weekPeriod['end_date']
        )
        ->count(),

    'Bulan Ini' => Reservation::query()
        ->whereDate(
            'reservation_date',
            '>=',
            $monthPeriod['start_date']
        )
        ->whereDate(
            'reservation_date',
            '<=',
            $monthPeriod['end_date']
        )
        ->count(),

    ReservationStatus::WAITING => Reservation::where(
        'status',
        ReservationStatus::WAITING
    )->count(),

    ReservationStatus::SCHEDULED => Reservation::where(
        'status',
        ReservationStatus::SCHEDULED
    )->count(),

    ReservationStatus::IN_PROGRESS => Reservation::where(
        'status',
        ReservationStatus::IN_PROGRESS
    )->count(),

    ReservationStatus::COMPLETED => Reservation::where(
        'status',
        ReservationStatus::COMPLETED
    )->count(),

    ReservationStatus::CANCELLED => Reservation::where(
        'status',
        ReservationStatus::CANCELLED
    )->count(),

    'Pasien' => Patient::count(),

    'Layanan Aktif' => LabTest::where(
        'status',
        'active'
    )->count(),
];

    $periodOptions = ReservationPeriod::options();

    return view('admin.dashboard', compact(
        'stats',
        'reservations',
        'periodOptions'
    ));
}

public function exportReservations(
    Request $request
): StreamedResponse {
    $validated = $request->validate([
        'period' => [
            'required',
            Rule::in(array_keys(
                ReservationPeriod::options()
            )),
        ],
        'start_date' => [
            'nullable',
            'required_if:period,custom',
            'date',
        ],
        'end_date' => [
            'nullable',
            'required_if:period,custom',
            'date',
            'after_or_equal:start_date',
        ],
    ]);

    $period = ReservationPeriod::resolve(
        $validated['period'],
        $validated['start_date'] ?? null,
        $validated['end_date'] ?? null
    );

    $query = Reservation::query()
        ->with(['patient', 'labTest'])
->whereDate(
    'reservation_date',
    '>=',
    $period['start_date']
)
->whereDate(
    'reservation_date',
    '<=',
    $period['end_date']
)
        ->orderBy('reservation_date')
        ->orderBy('reservation_time')
        ->orderBy('id');

    return response()->streamDownload(
        function () use ($query) {
            $output = fopen('php://output', 'w');

            if ($output === false) {
                return;
            }

            // BOM agar karakter UTF-8 terbaca dengan baik di Excel.
            fwrite($output, "\xEF\xBB\xBF");

            fputcsv($output, [
                'Kode Reservasi',
                'Nama Pasien',
                'NIK',
                'Layanan',
                'Tanggal Reservasi',
                'Jam',
                'Nomor Antrean',
                'Status',
                'Catatan',
                'Tanggal Dibuat',
            ]);

            $query->chunk(
                500,
                function ($reservations) use ($output) {
                    foreach ($reservations as $reservation) {
                        fputcsv($output, [
                            $reservation->code,
                            $reservation->patient?->full_name ?? '-',
                            $reservation->patient?->nik ?? '-',
                            $reservation->labTest?->name ?? '-',
                            optional(
                                $reservation->reservation_date
                            )->format('Y-m-d'),
                            substr(
                                (string) $reservation->reservation_time,
                                0,
                                5
                            ),
                            $reservation->queue_number,
                            $reservation->status,
                            $reservation->notes ?? '',
                            optional(
                                $reservation->created_at
                            )->format('Y-m-d H:i:s'),
                        ]);
                    }
                }
            );

            fclose($output);
        },
        $period['filename'],
        [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]
    );
}

    public function status(Request $request): View
    {
        $reservation = null;

        if ($request->filled('code')) {
            $reservation = Reservation::with(['patient', 'labTest'])
                ->where('code', $request->code)
                ->first();
        }

        return view('admin.status', compact('reservation'));
    }

public function manage(Request $request): View
{
    $filters = $request->validate([
        'code' => [
            'nullable',
            'string',
            'max:50',
        ],
        'patient' => [
            'nullable',
            'string',
            'max:100',
        ],
        'status' => [
            'nullable',
            Rule::in(ReservationStatus::all()),
        ],
        'lab_test_id' => [
            'nullable',
            'integer',
            Rule::exists('lab_tests', 'id'),
        ],
        'reservation_date' => [
            'nullable',
            'date',
        ],
        'sort' => [
            'nullable',
            Rule::in(['latest', 'oldest']),
        ],
    ]);

    $query = Reservation::query()
        ->with(['patient', 'labTest']);

    $this->applyReservationFilters($query, $filters);
    $this->applyReservationSorting(
        $query,
        $filters['sort'] ?? 'latest'
    );

    $reservations = $query
        ->paginate(10)
        ->withQueryString();

    $labTests = LabTest::query()
        ->orderBy('name')
        ->get(['id', 'name']);

    $statuses = ReservationStatus::all();

    return view('admin.manage', compact(
        'reservations',
        'labTests',
        'statuses',
        'filters'
    ));
}

    public function show(Reservation $reservation): View
    {
        $reservation->load(['patient.user', 'labTest']);
        $statuses = $this->statuses();

        return view('admin.show', compact('reservation', 'statuses'));
    }

public function updateStatus(
    Request $request,
    Reservation $reservation
): RedirectResponse {
    $validated = $request->validate([
        'status' => [
            'required',
            'in:' . implode(',', ReservationStatus::all()),
        ],
    ]);

    if (! ReservationStatus::canTransition(
        $reservation->status,
        $validated['status']
    )) {
        throw ValidationException::withMessages([
            'status' => [
                sprintf(
                    'Status reservasi tidak dapat diubah dari %s menjadi %s.',
                    $reservation->status,
                    $validated['status']
                ),
            ],
        ]);
    }

    $reservation->update([
        'status' => $validated['status'],
    ]);

    return redirect()
        ->back()
        ->with('success', 'Status reservasi berhasil diperbarui.');
}

    public function destroy(Reservation $reservation): RedirectResponse
    {
        $reservation->delete();

        return redirect()
            ->route('admin.reservations.manage')
            ->with('success', 'Data reservasi berhasil dihapus oleh admin.');
    }

    public function servicesIndex(Request $request): View
    {
        $query = LabTest::query()->latest();

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($innerQuery) use ($keyword) {
                $innerQuery->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('slug', 'like', '%' . $keyword . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $services = $query->paginate(10)->withQueryString();

        return view('admin.services.index', compact('services'));
    }

    public function servicesCreate(): View
    {
        return view('admin.services.form', [
            'service' => new LabTest(),
            'formMode' => 'create',
        ]);
    }

    public function servicesStore(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->serviceRules());

        LabTest::create([
            'name' => $validated['name'],
            'slug' => $this->generateUniqueSlug($validated['slug'] ?? $validated['name']),
            'description' => $validated['description'] ?? null,
            'benefit' => $validated['benefit'] ?? null,
            'preparation' => $validated['preparation'] ?? null,
            'price' => $validated['price'],
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function servicesEdit(LabTest $labTest): View
    {
        return view('admin.services.form', [
            'service' => $labTest,
            'formMode' => 'edit',
        ]);
    }

    public function servicesUpdate(Request $request, LabTest $labTest): RedirectResponse
    {
        $validated = $request->validate($this->serviceRules($labTest->id));

        $labTest->update([
            'name' => $validated['name'],
            'slug' => $this->generateUniqueSlug($validated['slug'] ?? $validated['name'], $labTest->id),
            'description' => $validated['description'] ?? null,
            'benefit' => $validated['benefit'] ?? null,
            'preparation' => $validated['preparation'] ?? null,
            'price' => $validated['price'],
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Layanan berhasil diperbarui.');
    }

    public function servicesToggleStatus(LabTest $labTest): RedirectResponse
    {
        $nextStatus = $labTest->status === 'active' ? 'inactive' : 'active';

        $labTest->update([
            'status' => $nextStatus,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Status layanan berhasil diperbarui menjadi ' . $nextStatus . '.');
    }

    private function serviceRules(?int $ignoreId = null): array
    {
        $slugUniqueRule = 'unique:lab_tests,slug';

        if ($ignoreId) {
            $slugUniqueRule .= ',' . $ignoreId;
        }

        return [
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:120', $slugUniqueRule],
            'description' => ['nullable', 'string'],
            'benefit' => ['nullable', 'string'],
            'preparation' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }

    private function generateUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($value);
        $slug = $baseSlug;
        $counter = 1;

        while (
            LabTest::query()
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

private function applyReservationFilters(
    Builder $query,
    array $filters
): void {
    $query->when(
        $filters['code'] ?? null,
        function (Builder $query, string $code) {
            $query->where(
                'code',
                'like',
                '%' . trim($code) . '%'
            );
        }
    );

    $query->when(
        $filters['patient'] ?? null,
        function (Builder $query, string $patientName) {
            $query->whereHas(
                'patient',
                function (Builder $patientQuery) use ($patientName) {
                    $patientQuery->where(
                        'full_name',
                        'like',
                        '%' . trim($patientName) . '%'
                    );
                }
            );
        }
    );

    $query->when(
        $filters['status'] ?? null,
        function (Builder $query, string $status) {
            $query->where('status', $status);
        }
    );

    $query->when(
        $filters['lab_test_id'] ?? null,
        function (Builder $query, int|string $labTestId) {
            $query->where('lab_test_id', $labTestId);
        }
    );

    $query->when(
        $filters['reservation_date'] ?? null,
        function (Builder $query, string $reservationDate) {
            $query->whereDate(
                'reservation_date',
                $reservationDate
            );
        }
    );
}

private function applyReservationSorting(
    Builder $query,
    string $sort
): void {
    if ($sort === 'oldest') {
        $query
            ->orderBy('reservation_date')
            ->orderBy('reservation_time')
            ->orderBy('id');

        return;
    }

    $query
        ->orderByDesc('reservation_date')
        ->orderByDesc('reservation_time')
        ->orderByDesc('id');
}

private function statuses(): array
{
    return ReservationStatus::all();
}
}