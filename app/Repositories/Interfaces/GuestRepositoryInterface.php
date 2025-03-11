<?php

namespace App\Repositories\Interfaces;

interface GuestRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function getStayHistory($id);
    public function search($term);
}
