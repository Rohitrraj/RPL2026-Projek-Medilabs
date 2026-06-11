<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LabTest extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'benefit',
        'preparation',
        'price',
        'status',
    ];

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}