<?php

namespace App\Services;

use App\Repositories\Interfaces\PropertyRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class PropertyService
{
    protected $propertyRepository;

    public function __construct(PropertyRepositoryInterface $propertyRepository)
    {
        $this->propertyRepository = $propertyRepository;
    }

    public function getAllProperties()
    {
        return Cache::remember('properties.all', 3600, function () {
            return $this->propertyRepository->all();
        });
    }

    public function getProperty($id)
    {
        return Cache::remember("property.{$id}", 3600, function () use ($id) {
            return $this->propertyRepository->find($id);
        });
    }

    public function createProperty(array $data)
    {
        $property = $this->propertyRepository->create($data);
        Cache::tags(['properties'])->flush();
        return $property;
    }

    public function updateProperty($id, array $data)
    {
        $property = $this->propertyRepository->update($id, $data);
        Cache::forget("property.{$id}");
        Cache::tags(['properties'])->flush();
        return $property;
    }

    public function deleteProperty($id)
    {
        $result = $this->propertyRepository->delete($id);
        Cache::forget("property.{$id}");
        Cache::tags(['properties'])->flush();
        return $result;
    }

    public function getAvailableProperties()
    {
        return Cache::remember('properties.available', 3600, function () {
            return $this->propertyRepository->findByStatus('available');
        });
    }

    public function getTotalActiveProperties()
    {
        return Cache::remember('properties.total_active', 3600, function () {
            return $this->propertyRepository->countActive();
        });
    }
}
