<?php

namespace App\Jobs;

use App\DTOs\CloudEventDTO;
use App\DTOs\EventEmitterDTO;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\V1\UserService;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessVaccineScheduling implements ShouldQueue
{
    use Dispatchable, Queueable;

    protected $userData;

    /**
     * Create a new job instance.
     *
     * @param array $userData
     * @return void
     */
    public function __construct(array $userData)
    {
        $this->userData = $userData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(UserService $userService)
    {
        $userService->scheduleVaccination($this->userData['data']);
    }
}
