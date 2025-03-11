<?php

namespace App\Repositories\Eloquent;

use App\Models\Owner;
use App\Repositories\Interfaces\OwnerRepositoryInterface;

class OwnerRepository implements OwnerRepositoryInterface
{
    protected $model;

    public function __construct(Owner $model)
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
        $owner = $this->find($id);
        $owner->update($data);
        return $owner;
    }

    public function delete($id)
    {
        return $this->find($id)->delete();
    }
}
