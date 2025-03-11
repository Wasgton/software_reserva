<?php

namespace App\Services;

use App\Repositories\Interfaces\ReservationRepositoryInterface;
use App\Jobs\NotifyOwnerAboutReservation;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class ReservationService
{
    protected $reservationRepository;
    protected $propertyService;

    public function __construct(
        ReservationRepositoryInterface $reservationRepository,
        PropertyService $propertyService
    ) {
        $this->reservationRepository = $reservationRepository;
        $this->propertyService = $propertyService;
    }

    public function getAllReservations(array $filters = [])
    {
        $cacheKey = 'reservations.all.' . md5(json_encode($filters));

        return Cache::remember($cacheKey, 3600, function () use ($filters) {
            return $this->reservationRepository->getAllWithFilters($filters);
        });
    }

    public function getReservation($id)
    {
        return Cache::remember("reservation.{$id}", 3600, function () use ($id) {
            return $this->reservationRepository->find($id);
        });
    }

    public function createReservation(array $data)
    {
        // Calcula o número de diárias
        $checkIn = Carbon::parse($data['check_in']);
        $checkOut = Carbon::parse($data['check_out']);
        $nights = $checkOut->diffInDays($checkIn);

        // Busca a propriedade para pegar a taxa diária
        $property = $this->propertyService->getProperty($data['property_id']);

        $data['nights'] = $nights;
        $data['daily_rate'] = $property->daily_rate;
        $data['total_amount'] = ($nights * $property->daily_rate) + ($data['cleaning_fee'] ?? 0);
        $data['reservation_code'] = 'RES-' . uniqid();

        $reservation = $this->reservationRepository->create($data);

        // Dispara job para notificar o proprietário
        NotifyOwnerAboutReservation::dispatch($reservation);

        $this->clearReservationCache();

        return $reservation;
    }

    public function updateReservation($id, array $data)
    {
        $reservation = $this->reservationRepository->update($id, $data);
        $this->clearReservationCache();
        return $reservation;
    }

    public function updateReservationStatus($id, string $status)
    {
        $reservation = $this->reservationRepository->update($id, ['status' => $status]);
        $this->clearReservationCache();
        return $reservation;
    }

    public function deleteReservation($id)
    {
        $result = $this->reservationRepository->delete($id);
        $this->clearReservationCache();
        return $result;
    }

    public function getCurrentOccupancyRate()
    {
        return Cache::remember('occupancy.current_rate', 3600, function () {
            $totalProperties = $this->propertyService->getTotalActiveProperties();

            if ($totalProperties === 0) {
                return 0;
            }

            $occupiedProperties = $this->reservationRepository->getCurrentlyOccupiedCount();

            return round(($occupiedProperties / $totalProperties) * 100, 1);
        });
    }

    public function getUpcomingReservations()
    {
        return Cache::remember('reservations.upcoming', 3600, function () {
            return $this->reservationRepository->getUpcoming();
        });
    }

    public function getCurrentReservations()
    {
        return Cache::remember('reservations.current', 3600, function () {
            return $this->reservationRepository->getCurrent();
        });
    }

    protected function clearReservationCache()
    {
        Cache::tags(['reservations'])->flush();
    }
}
