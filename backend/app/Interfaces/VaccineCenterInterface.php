<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface VaccineCenterInterface
{
    public function getAll(int $page, int $limit, ?string $search): Builder;
    public function findByName(string $name): ?array;
    public function update(int $id, array $data): ?array;
}
