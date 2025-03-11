<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Owner extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'profession',
        'person_type',
        'nationality',
        'document_number',
        'address',
        'city',
        'state',
        'postal_code'
    ];

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }
}
