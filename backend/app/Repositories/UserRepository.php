<?php

namespace App\Repositories;

use App\Models\User;
use App\Interfaces\UserRepositoryInterface;
use App\Caching\UserCache;

class UserRepository implements UserRepositoryInterface
{
    public function create(array $data): array
    {
        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'nid' => $data['nid'],
            'phone' => $data['phone'],
            'vaccine_center_id' => $data['vaccine_center_id'],
            'registered_at' => $data['registered_at'],
        ]);
        $userInfo = $user->toArray();
        UserCache::remove($data['nid']);
        return $userInfo;
    }

    public function update(int $id, array $data): array
    {
        $user = User::query()->find($id);
        UserCache::remove($user['nid']);
        $updatedData = $user->fill($data);
        $user->save();
        $userInfo = $updatedData->toArray();
        return $userInfo;
    }

    public function findByEmail(string $email): ?array
    {
        $user = User::query()->where('email', $email)->first()->toArray();
        UserCache::set($user['nid'], $user);
        return $user;
    }

    public function findByNid(string $nid): ?array
    {
        $user = UserCache::get($nid);
        if (!$user) {
            $user = User::query()->where('nid', $nid)->first()->toArray();
            UserCache::set($nid, $user);
        }
        return $user;
    }
}
