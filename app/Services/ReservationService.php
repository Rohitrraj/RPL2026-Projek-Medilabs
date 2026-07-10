<?php

namespace App\Services;

use App\Models\Patient;
use App\Models\Reservation;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReservationService
{
    private const ACTIVE_STATUSES = [
        'Menunggu',
        'Terjadwal',
        'Diproses',
    ];

    private const MAX_CREATION_ATTEMPTS = 3;

    public function createForPatient(
        Patient $patient,
        array $validated
    ): Reservation {
        for (
            $attempt = 1;
            $attempt <= self::MAX_CREATION_ATTEMPTS;
            $attempt++
        ) {
            try {
                return $this->createInTransaction($patient, $validated);
            } catch (QueryException $exception) {
                $shouldRetry = $attempt < self::MAX_CREATION_ATTEMPTS
                    && $this->isReservationCodeConflict($exception);

                if (! $shouldRetry) {
                    throw $exception;
                }
            }
        }

        throw new \RuntimeException(
            'Reservasi gagal dibuat setelah beberapa percobaan.'
        );
    }

    private function createInTransaction(
        Patient $patient,
        array $validated
    ): Reservation {
        return DB::transaction(function () use ($patient, $validated) {
            $lockedPatient = Patient::query()
                ->whereKey($patient->id)
                ->lockForUpdate()
                ->firstOrFail();

            $this->ensureNoDuplicateActiveReservation(
                $lockedPatient,
                $validated
            );

            $sequence = $this->nextSequenceNumber();

            return Reservation::create([
                'code' => $this->generateReservationCode($sequence),
                'patient_id' => $lockedPatient->id,
                'lab_test_id' => $validated['lab_test_id'],
                'reservation_date' => $validated['reservation_date'],
                'reservation_time' => $validated['reservation_time'],
                'queue_number' => $this->generateQueueNumber($sequence),
                'status' => 'Menunggu',
                'notes' => $validated['notes'] ?? null,
            ]);
        });
    }

    private function ensureNoDuplicateActiveReservation(
        Patient $patient,
        array $validated
    ): void {
        $duplicateExists = Reservation::query()
            ->where('patient_id', $patient->id)
            ->whereDate(
                'reservation_date',
                $validated['reservation_date']
            )
            ->where(
                'reservation_time',
                $validated['reservation_time']
            )
            ->whereIn('status', self::ACTIVE_STATUSES)
            ->exists();

        if ($duplicateExists) {
            throw ValidationException::withMessages([
                'reservation_time' => [
                    'Anda sudah memiliki reservasi aktif untuk layanan dan jadwal tersebut.',
                ],
            ]);
        }
    }

    private function nextSequenceNumber(): int
    {
        $latestReservationId = Reservation::query()->max('id') ?? 0;

        return $latestReservationId + 1;
    }

    private function generateReservationCode(int $sequence): string
    {
        return 'A' . str_pad(
            (string) $sequence,
            3,
            '0',
            STR_PAD_LEFT
        );
    }

    private function generateQueueNumber(int $sequence): string
    {
        return 'A-' . str_pad(
            (string) $sequence,
            2,
            '0',
            STR_PAD_LEFT
        );
    }

    private function isReservationCodeConflict(
        QueryException $exception
    ): bool {
        $sqlState = $exception->errorInfo[0] ?? null;
        $driverCode = (int) ($exception->errorInfo[1] ?? 0);
        $message = strtolower($exception->getMessage());

        $isUniqueConstraint = in_array(
            $sqlState,
            ['23000', '23505'],
            true
        );

        $referencesReservationCode =
            $driverCode === 1062
            || str_contains($message, 'reservations.code')
            || str_contains($message, 'reservations_code_unique');

        return $isUniqueConstraint && $referencesReservationCode;
    }
}