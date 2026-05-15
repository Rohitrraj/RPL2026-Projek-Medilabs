<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'patient_id',
        'lab_test_id',
        'reservation_date',
        'reservation_time',
        'queue_number',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'reservation_date' => 'date',
        ];
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function labTest()
    {
        return $this->belongsTo(LabTest::class);
    }

    public function formattedDate(): string
    {
        return optional($this->reservation_date)->format('d M Y') ?? '-';
    }

    public function formattedTime(): string
    {
        return substr((string) $this->reservation_time, 0, 5);
    }
}
