<?php

namespace App\Support;

class ReservationStatus
{
    public const WAITING = 'Menunggu';
    public const SCHEDULED = 'Terjadwal';
    public const IN_PROGRESS = 'Diproses';
    public const COMPLETED = 'Selesai';
    public const CANCELLED = 'Dibatalkan';

    private const TRANSITIONS = [
        self::WAITING => [
            self::SCHEDULED,
            self::CANCELLED,
        ],
        self::SCHEDULED => [
            self::IN_PROGRESS,
            self::CANCELLED,
        ],
        self::IN_PROGRESS => [
            self::COMPLETED,
        ],
        self::COMPLETED => [],
        self::CANCELLED => [],
    ];

    private const PATIENT_DELETABLE_STATUSES = [
        self::WAITING,
        self::CANCELLED,
    ];

    public static function all(): array
    {
        return [
            self::WAITING,
            self::SCHEDULED,
            self::IN_PROGRESS,
            self::COMPLETED,
            self::CANCELLED,
        ];
    }

    public static function canTransition(
        string $currentStatus,
        string $nextStatus
    ): bool {
        if ($currentStatus === $nextStatus) {
            return true;
        }

        return in_array(
            $nextStatus,
            self::TRANSITIONS[$currentStatus] ?? [],
            true
        );
    }

    public static function canBeDeletedByPatient(string $status): bool
    {
        return in_array(
            $status,
            self::PATIENT_DELETABLE_STATUSES,
            true
        );
    }
}