<?php

namespace App\Repositories;

use App\Interfaces\VaccineCenterInterface;
use App\Models\VaccineCenter;
use App\Caching\VaccineCenterCache;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;

class VaccineCenterRepository
{
    public function getAll(int $page, int $limit, ?string $search = null): LengthAwarePaginator
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

    public function update(int $id, array $data): ?array
    {
        $vaccineCenter = VaccineCenter::query()->find($id);
        VaccineCenterCache::remove($vaccineCenter['name']);
        $updatedData = $vaccineCenter->fill($data);
        $vaccineCenter->save();

        return $updatedData->toArray();
    }
}
