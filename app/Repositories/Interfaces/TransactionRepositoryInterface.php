<?php

namespace App\Repositories\Interfaces;

use Carbon\Carbon;

interface TransactionRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function getByProperty($propertyId, Carbon $startDate, Carbon $endDate);
    public function getByReservation($reservationId);
    public function getSumByDateRange(Carbon $startDate, Carbon $endDate, string $type);
    public function getAllWithFilters(array $filters);
    public function getByDateRange(Carbon $startDate, Carbon $endDate);
}
