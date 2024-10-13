<?php

namespace App\Repositories;

use App\Models\User;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function create(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'nid' => $data['nid'],
            'phone' => $data['phone'],
            'vaccine_center_id' => $data['vaccine_center_id'],
            'registered_at' => $data['registered_at'],
        ]);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function findByNid(string $nid): ?User
    {
        return User::where('nid', $nid)->first();
    }
}
