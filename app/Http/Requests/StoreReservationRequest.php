<?php

namespace App\Http\Requests;

use App\Support\ReservationSchedule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'lab_test_id' => [
                'required',
                'integer',
                Rule::exists('lab_tests', 'id')
                    ->where(fn ($query) => $query->where('status', 'active')),
            ],
            'reservation_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],
            'reservation_time' => [
                'required',
                Rule::in(ReservationSchedule::availableHours()),
            ],
            'notes' => [
                'nullable',
                'string',
                'max:500',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'lab_test_id.required' => 'Layanan laboratorium wajib dipilih.',
            'lab_test_id.integer' => 'Layanan laboratorium tidak valid.',
            'lab_test_id.exists' => 'Layanan tidak tersedia atau sedang tidak aktif.',

            'reservation_date.required' => 'Tanggal reservasi wajib diisi.',
            'reservation_date.date' => 'Format tanggal reservasi tidak valid.',
            'reservation_date.after_or_equal' => 'Tanggal reservasi tidak boleh sebelum hari ini.',

            'reservation_time.required' => 'Jam reservasi wajib dipilih.',
            'reservation_time.in' => 'Jam reservasi tidak tersedia.',

            'notes.string' => 'Catatan harus berupa teks.',
            'notes.max' => 'Catatan maksimal 500 karakter.',
        ];
    }
}