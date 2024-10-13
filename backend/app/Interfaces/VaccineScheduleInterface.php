<?php

namespace App\Interfaces;


interface VaccineScheduleInterface
{
    public function find(int $vaccineCenterId, string $scheduledDate): ?array;
    public function create(int $vaccineCenterId, string $scheduledDate, int $slotsFilled): ?array;
    public function update(int $vaccineCenterId, string $scheduledDate, array $data): ?array;
}
