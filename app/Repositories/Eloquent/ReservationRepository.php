<?php

namespace App\Repositories\Eloquent;

use App\Models\Reservation;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use Carbon\Carbon;

class ReservationRepository implements ReservationRepositoryInterface
{
    protected $model;

    public function __construct(Reservation $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->with(['guest', 'property'])->get();
    }

    public function find($id)
    {
        return $this->model->with(['guest', 'property'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $reservation = $this->find($id);
        $reservation->update($data);
        return $reservation;
    }

    public function delete($id)
    {
        return $this->find($id)->delete();
    }

    public function getUpcoming()
    {
        return $this->model->with(['guest', 'property'])
                          ->upcoming()
                          ->get();
    }

    public function getCurrent()
    {
        return $this->model->with(['guest', 'property'])
                          ->current()
                          ->get();
    }

    public function getByPropertyAndDateRange($propertyId, $startDate, $endDate)
    {
        return $this->model->where('property_id', $propertyId)
                          ->where(function ($query) use ($startDate, $endDate) {
                              $query->whereBetween('check_in', [$startDate, $endDate])
                                    ->orWhereBetween('check_out', [$startDate, $endDate])
                                    ->orWhere(function ($q) use ($startDate, $endDate) {
                                        $q->where('check_in', '<=', $startDate)
                                          ->where('check_out', '>=', $endDate);
                                    });
                          })
                          ->where('status', 'confirmed')
                          ->get();
    }

    public function getByGuest($guestId)
    {
        return $this->model->with(['property'])
                          ->where('guest_id', $guestId)
                          ->orderBy('check_in', 'desc')
                          ->get();
    }

    public function getByStatus($status)
    {
        return $this->model->with(['guest', 'property'])
                          ->where('status', $status)
                          ->orderBy('check_in')
                          ->get();
    }

    public function getCurrentlyOccupiedCount()
    {
        $now = Carbon::now();

        return $this->model
            ->where('status', 'confirmed')
            ->where('check_in', '<=', $now)
            ->where('check_out', '>=', $now)
            ->distinct('property_id')
            ->count('property_id');
    }

    public function getAllWithFilters(array $filters)
    {
        $query = $this->model->with(['guest', 'property']);

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['check_in'])) {
            $query->where('check_in', '>=', Carbon::parse($filters['check_in']));
        }

        if (isset($filters['check_out'])) {
            $query->where('check_out', '<=', Carbon::parse($filters['check_out']));
        }

        return $query->orderBy('check_in')->get();
    }
}
