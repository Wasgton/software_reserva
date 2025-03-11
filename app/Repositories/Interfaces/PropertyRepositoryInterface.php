<?php

namespace App\Repositories\Interfaces;

interface PropertyRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function findByStatus($status);
    public function findByLocation($city, $state);
    public function countActive();
}
