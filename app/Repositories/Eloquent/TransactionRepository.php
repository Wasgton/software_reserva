<?php

namespace App\Repositories\Eloquent;

use App\Models\Transaction;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use Carbon\Carbon;

class TransactionRepository implements TransactionRepositoryInterface
{
    protected $model;

    public function __construct(Transaction $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->with(['property', 'reservation'])->get();
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $transaction = $this->find($id);
        $transaction->update($data);
        return $transaction;
    }

    public function delete($id)
    {
        return $this->find($id)->delete();
    }

    public function getByProperty($propertyId, Carbon $startDate, Carbon $endDate)
    {
        return $this->model->where('property_id', $propertyId)
                          ->whereBetween('transaction_date', [$startDate, $endDate])
                          ->orderBy('transaction_date')
                          ->get();
    }

    public function getByReservation($reservationId)
    {
        return $this->model->where('reservation_id', $reservationId)
                          ->orderBy('transaction_date')
                          ->get();
    }

    public function getSumByDateRange(Carbon $startDate, Carbon $endDate, string $type)
    {
        return $this->model->where('type', $type)
                          ->whereBetween('transaction_date', [$startDate, $endDate])
                          ->sum('amount');
    }

    public function getAllWithFilters(array $filters)
    {
        $query = $this->model->with(['property', 'reservation']);

        if (isset($filters['start_date'])) {
            $query->where('transaction_date', '>=', Carbon::parse($filters['start_date']));
        }

        if (isset($filters['end_date'])) {
            $query->where('transaction_date', '<=', Carbon::parse($filters['end_date']));
        }

        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['property_id'])) {
            $query->where('property_id', $filters['property_id']);
        }

        if (isset($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        return $query->orderBy('transaction_date', 'desc')->get();
    }

    public function getByDateRange(Carbon $startDate, Carbon $endDate)
    {
        return $this->model
            ->with(['property', 'reservation'])
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->orderBy('transaction_date')
            ->get();
    }
}
