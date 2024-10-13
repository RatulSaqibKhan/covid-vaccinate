<?php

namespace App\Repositories;

use App\Caching\VaccineScheduleCache;
use App\Models\VaccineSchedule;
use App\Interfaces\VaccineScheduleInterface;

class VaccineScheduleRepository implements VaccineScheduleInterface
{
    public function find(int $vaccineCenterId, string $scheduledDate): ?array
    {
        $vaccineSchedule = VaccineScheduleCache::get($vaccineCenterId, $scheduledDate);
        if (!$vaccineCenterId) {
            $vaccineSchedule = VaccineSchedule::query()->where([
                'vaccine_center_id' => $vaccineCenterId,
                'scheduled_date' => $scheduledDate
            ])->first()->toArray();
            VaccineScheduleCache::set($vaccineCenterId, $scheduledDate, $vaccineSchedule);
        }
        return $vaccineSchedule;
    }

    public function create(int $vaccineCenterId, string $scheduledDate, int $slotsFilled): ?array
    {
        $vaccineSchedule = VaccineSchedule::query()->create([
            'vaccine_center_id' => $vaccineCenterId,
            'scheduled_date' => $scheduledDate,
            'slots_filled' => $slotsFilled
        ]);
        $data = $vaccineSchedule->toArray();
        VaccineScheduleCache::set($vaccineCenterId, $scheduledDate, $data);

        return $data;
    }

    public function update(int $vaccineCenterId, string $scheduledDate, array $data): ?array
    {
        $vaccineSchedule = VaccineSchedule::query()->where([
            'vaccine_center_id' => $vaccineCenterId,
            'scheduled_date' => $scheduledDate
        ])->first();
        if ($vaccineSchedule) {
            VaccineScheduleCache::remove($vaccineCenterId, $scheduledDate);
            $vaccineSchedule->update($data);
        }

        return $this->find($vaccineCenterId, $scheduledDate);
    }
}
