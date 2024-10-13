<?php

namespace App\Repositories;

use App\Models\VaccineCenter;
use App\Caching\VaccineCenterCache;
use App\Interfaces\VaccineCenterInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class VaccineCenterRepository implements VaccineCenterInterface
{
    public function getAll(int $limit, ?string $search = null): LengthAwarePaginator
    {
        $vaccineCenterQuery = VaccineCenter::query();
        if ($search) {
            $vaccineCenterQuery->where('name', 'like', "%$search%")
                ->orWhere('address', 'like', "%$search%");
        }

        $vaccineCenterQuery = $vaccineCenterQuery->orderByDesc('id');

        $data = $vaccineCenterQuery->paginate($limit);
        VaccineCenterCache::redisPipelineStore($data->items());

        return $data;
    }

    public function findByName(string $name): ?array
    {
        $vaccineCenter = VaccineCenterCache::get($name);
        if (!$vaccineCenter) {
            $vaccineCenter = VaccineCenter::query()->where('name', $name)->first()->toArray();
            VaccineCenterCache::set($name, $vaccineCenter);
        }
        return $vaccineCenter;
    }

    public function findById(int $id): ?array
    {
        return VaccineCenter::query()->find($id)->toArray();
    }

    public function update(int $id, array $data): ?array
    {
        $vaccineCenter = VaccineCenter::query()->find($id);
        VaccineCenterCache::remove($vaccineCenter['name']);
        $updatedData = $vaccineCenter->fill($data);
        $vaccineCenter->save();

        return $updatedData->toArray();
    }
}
