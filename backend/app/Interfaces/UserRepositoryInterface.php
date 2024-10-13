<?php

namespace App\Interfaces;

use App\Models\User;

interface UserRepositoryInterface
{
    public function create(array $data): array;
    public function update(int $id, array $data): array;
    public function findByEmail(string $email): ?array;
    public function findByNid(string $nid): ?array;
}
