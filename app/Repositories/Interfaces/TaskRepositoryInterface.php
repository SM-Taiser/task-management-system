<?php

namespace App\Repositories\Interfaces;

use App\Models\Task;

interface TaskRepositoryInterface
{
    public function all(string $filter = null);
    public function find(int $id): Task;
    public function store(array $data): Task;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
