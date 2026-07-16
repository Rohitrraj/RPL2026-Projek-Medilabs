<?php

namespace App\Support;

use Illuminate\Support\Facades\Date;
use InvalidArgumentException;

class ReservationPeriod
{
    public const TODAY = 'today';
    public const WEEK = 'week';
    public const MONTH = 'month';
    public const CUSTOM = 'custom';

    public static function options(): array
    {
        return [
            self::TODAY => 'Hari Ini',
            self::WEEK => 'Minggu Ini',
            self::MONTH => 'Bulan Ini',
            self::CUSTOM => 'Rentang Tanggal',
        ];
    }

    public static function resolve(
        string $period,
        ?string $startDate = null,
        ?string $endDate = null
    ): array {
        $today = Date::today()->toImmutable();

        return match ($period) {
            self::TODAY => [
                'start_date' => $today->toDateString(),
                'end_date' => $today->toDateString(),
                'filename' => sprintf(
                    'rekap-reservasi-harian-%s.csv',
                    $today->format('Y-m-d')
                ),
            ],

            self::WEEK => [
                'start_date' => $today
                    ->startOfWeek()
                    ->toDateString(),
                'end_date' => $today
                    ->endOfWeek()
                    ->toDateString(),
                'filename' => sprintf(
                    'rekap-reservasi-mingguan-%s_%s.csv',
                    $today->startOfWeek()->format('Y-m-d'),
                    $today->endOfWeek()->format('Y-m-d')
                ),
            ],

            self::MONTH => [
                'start_date' => $today
                    ->startOfMonth()
                    ->toDateString(),
                'end_date' => $today
                    ->endOfMonth()
                    ->toDateString(),
                'filename' => sprintf(
                    'rekap-reservasi-bulanan-%s.csv',
                    $today->format('Y-m')
                ),
            ],

            self::CUSTOM => self::resolveCustomPeriod(
                $startDate,
                $endDate
            ),

            default => throw new InvalidArgumentException(
                'Periode reservasi tidak valid.'
            ),
        };
    }

    private static function resolveCustomPeriod(
        ?string $startDate,
        ?string $endDate
    ): array {
        if (! $startDate || ! $endDate) {
            throw new InvalidArgumentException(
                'Tanggal awal dan tanggal akhir wajib diisi.'
            );
        }

        return [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'filename' => sprintf(
                'rekap-reservasi-%s_%s.csv',
                $startDate,
                $endDate
            ),
        ];
    }
}