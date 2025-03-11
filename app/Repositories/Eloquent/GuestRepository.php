<?php

namespace App\Repositories\Eloquent;

use App\Models\Guest;
use App\Repositories\Interfaces\GuestRepositoryInterface;

class GuestRepository implements GuestRepositoryInterface
{
    protected $model;

    public function __construct(Guest $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
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
        $guest = $this->find($id);
        $guest->update($data);
        return $guest;
    }

    public function delete($id)
    {
        return $this->find($id)->delete();
    }

    public function getStayHistory($id)
    {
        return $this->model->findOrFail($id)
            ->reservations()
            ->with('property')
            ->orderBy('check_in', 'desc')
            ->get();
    }

    public function search($term)
    {
        return $this->model
            ->where('name', 'like', "%{$term}%")
            ->orWhere('cpf', 'like', "%{$term}%")
            ->orWhere('email', 'like', "%{$term}%")
            ->orWhere('phone', 'like', "%{$term}%")
            ->get();
    }
}
