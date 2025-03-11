<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\PropertyRepositoryInterface;
use App\Repositories\Eloquent\PropertyRepository;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use App\Repositories\Eloquent\ReservationRepository;
use App\Repositories\Interfaces\GuestRepositoryInterface;
use App\Repositories\Eloquent\GuestRepository;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Repositories\Eloquent\TransactionRepository;
use App\Repositories\Interfaces\OwnerRepositoryInterface;
use App\Repositories\Eloquent\OwnerRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PropertyRepositoryInterface::class, PropertyRepository::class);
        $this->app->bind(ReservationRepositoryInterface::class, ReservationRepository::class);
        $this->app->bind(GuestRepositoryInterface::class, GuestRepository::class);
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
        $this->app->bind(OwnerRepositoryInterface::class, OwnerRepository::class);
        // Adicione os outros bindings aqui
    }
}
