<?php

namespace App\Repositories\Interfaces;

interface ReservationRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function getUpcoming();
    public function getCurrent();
    public function getByPropertyAndDateRange($propertyId, $startDate, $endDate);
    public function getByGuest($guestId);
    public function getByStatus($status);
    public function getCurrentlyOccupiedCount();
    public function getAllWithFilters(array $filters);
}
