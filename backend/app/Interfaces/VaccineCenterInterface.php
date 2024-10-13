<?php

namespace App\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface VaccineCenterInterface
{
    public function getAll(int $limit, ?string $search): LengthAwarePaginator;
    public function findById(int $id): ?array;
    public function findByName(string $name): ?array;
    public function update(int $id, array $data): ?array;
}
