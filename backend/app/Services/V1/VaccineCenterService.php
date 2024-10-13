<?php

namespace App\Services\V1;

use App\Interfaces\VaccineCenterInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class VaccineCenterService
{
    public function __construct(private VaccineCenterInterface $repository) {}

    public function getVaccineCenters(int $limit, ?string $search = null): LengthAwarePaginator {
        return $this->repository->getAll($limit, $search);
    }
}
