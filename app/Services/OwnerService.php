<?php

namespace App\Services;

use App\Repositories\Interfaces\OwnerRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class OwnerService
{
    protected $ownerRepository;

    public function __construct(OwnerRepositoryInterface $ownerRepository)
    {
        $this->ownerRepository = $ownerRepository;
    }

    public function getAllOwners()
    {
        return Cache::remember('owners.all', 3600, function () {
            return $this->ownerRepository->all();
        });
    }

    public function getOwner($id)
    {
        return Cache::remember("owner.{$id}", 3600, function () use ($id) {
            return $this->ownerRepository->find($id);
        });
    }

    public function createOwner(array $data)
    {
        $owner = $this->ownerRepository->create($data);
        $this->clearOwnerCache();
        return $owner;
    }

    public function updateOwner($id, array $data)
    {
        $owner = $this->ownerRepository->update($id, $data);
        $this->clearOwnerCache();
        return $owner;
    }

    public function deleteOwner($id)
    {
        $result = $this->ownerRepository->delete($id);
        $this->clearOwnerCache();
        return $result;
    }

    protected function clearOwnerCache()
    {
        Cache::tags(['owners'])->flush();
    }
}
