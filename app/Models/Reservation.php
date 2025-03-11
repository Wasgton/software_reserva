<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reservation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'reservation_code',
        'guest_id',
        'property_id',
        'check_in',
        'check_out',
        'nights',
        'daily_rate',
        'cleaning_fee',
        'total_amount',
        'status'
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'daily_rate' => 'decimal:2',
        'cleaning_fee' => 'decimal:2',
        'total_amount' => 'decimal:2'
    ];

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('check_in', '>', now())
                    ->where('status', 'confirmed')
                    ->orderBy('check_in');
    }

    public function scopeCurrent($query)
    {
        return $query->where('check_in', '<=', now())
                    ->where('check_out', '>=', now())
                    ->where('status', 'confirmed');
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'confirmed' => 'green',
            'pending' => 'yellow',
            'cancelled' => 'red',
            'completed' => 'blue',
            default => 'gray'
        };
    }
}
