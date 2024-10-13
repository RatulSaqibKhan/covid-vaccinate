<?php

namespace App\Services\V1;

use App\Repositories\VaccineCenterRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class VaccineCenterService
{
    public function __construct(private VaccineCenterRepository $repository) {}

    public function getVaccineCenters(int $page, int $limit, ?string $search = null): LengthAwarePaginator {
        return $this->repository->getAll($page, $limit, $search);
    }
}
