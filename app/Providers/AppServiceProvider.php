<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use App\Models\Property;
use App\Models\Reservation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Property::updated(function ($property) {
            Cache::tags(['properties'])->flush();
        });

        Reservation::created(function ($reservation) {
            Cache::tags(['reservations', 'dashboard'])->flush();
        });

        Reservation::updated(function ($reservation) {
            Cache::tags(['reservations', 'dashboard'])->flush();
        });
    }
}
