<?php

namespace App\Services;

use App\Repositories\Interfaces\GuestRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Collection;

class GuestService
{
    protected $guestRepository;

    public function __construct(GuestRepositoryInterface $guestRepository)
    {
        $this->guestRepository = $guestRepository;
    }

    public function getAllGuests(): Collection
    {
        return Cache::remember('guests.all', 3600, function () {
            return $this->guestRepository->all();
        });
    }

    public function getGuest(int $id)
    {
        return Cache::remember("guest.{$id}", 3600, function () use ($id) {
            return $this->guestRepository->find($id);
        });
    }

    public function createGuest(array $data)
    {
        $guest = $this->guestRepository->create($data);
        Cache::tags(['guests'])->flush();
        return $guest;
    }

    public function updateGuest(int $id, array $data)
    {
        $guest = $this->guestRepository->update($id, $data);
        Cache::forget("guest.{$id}");
        Cache::tags(['guests'])->flush();
        return $guest;
    }

    public function deleteGuest(int $id): bool
    {
        $result = $this->guestRepository->delete($id);
        Cache::forget("guest.{$id}");
        Cache::tags(['guests'])->flush();
        return $result;
    }

    public function searchGuests(string $term)
    {
        return $this->guestRepository->search($term);
    }

    public function getGuestStayHistory(int $guestId)
    {
        return Cache::remember("guest.{$guestId}.history", 3600, function () use ($guestId) {
            return $this->guestRepository->getStayHistory($guestId);
        });
    }

    public function validateDocument(string $document): bool
    {
        // Implementar validação de CPF/CNPJ
        return true;
    }
}
