<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'person_type',
        'nationality',
        'profession',
        'rg',
        'cpf',
        'phone',
        'email',
        'birth_date',
        'address',
        'city',
        'state',
        'postal_code'
    ];

    protected $casts = [
        'birth_date' => 'date'
    ];

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
