<?php

namespace App\Services\V1;

use App\DTOs\RegisterUserDTO;
use App\Events\UserRegisterEventEmitted;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\VaccineCenterInterface;
use App\Interfaces\VaccineScheduleInterface;
use App\Models\User;
use App\Utils\Helpers;
use Carbon\Carbon;

class UserService
{
    public function __construct(private UserRepositoryInterface $userRepository, private VaccineCenterInterface $vaccineCenterRepository, private VaccineScheduleInterface $vaccineScheduleRepository) {}

    public function registerUser(RegisterUserDTO $userDto)
    {
        $user = $this->userRepository->create($userDto->toArray());

        event(new UserRegisterEventEmitted($user));

        return $user;
    }

    public function searchUser(string $nid)
    {
        $user = $this->userRepository->findByNid($nid);
        $scheduleDate = $user['scheduled_date'] ? Carbon::parse($user['scheduled_date'], config('app.timezone')) : null;
        $now = \now(config('app.timezone'));
        if ($scheduleDate && $now->greaterThan($scheduleDate)) {
            $data = [
                'status' => User::VACCINATED
            ];
            $user = $this->userRepository->update($user['id'], $data);
        }

        return $user;
    }

    public function scheduleVaccination(array $user)
    {
        // Check if the user has a vaccine center ID
        $vaccineCenterId = $user['vaccine_center_id'];
        if (!$vaccineCenterId) {
            return;
        }

        // Retrieve vaccine center details
        $vaccineCenter = $this->vaccineCenterRepository->findById($vaccineCenterId);
        if (!$vaccineCenter) {
            return;
        }

        // Schedule the vaccination
        $this->assignVaccineScheduleToUser($user, $vaccineCenter);
    }

    private function assignVaccineScheduleToUser(array $user, array $vaccineCenter)
    {
        $scheduleDate = $vaccineCenter['available_date'];
        $dailyCapacity = $vaccineCenter['daily_capacity'];

        // Update user schedule date
        $this->updateUserScheduledDate($user['id'], $scheduleDate);

        // Update vaccine schedule and slots
        $this->updateVaccineScheduleSlots($vaccineCenter['id'], $scheduleDate, $dailyCapacity);
    }

    private function updateUserScheduledDate(int $userId, string $scheduleDate)
    {
        $data = ['scheduled_date' => $scheduleDate, 'status' => User::SCHEDULED];
        $this->userRepository->update($userId, $data);
    }

    private function updateVaccineScheduleSlots(int $vaccineCenterId, string $scheduleDate, int $dailyCapacity)
    {
        // Get vaccine schedule for the given date and center
        $vaccineSchedule = $this->vaccineScheduleRepository->find($vaccineCenterId, $scheduleDate);
        $slotsFilled = 1;
        if ($vaccineSchedule) {
            $slotsFilled = (int)($vaccineSchedule['slots_filled'] + 1);
            // Update the slots filled in the vaccine schedule
            $this->vaccineScheduleRepository->update($vaccineCenterId, $scheduleDate, ['slots_filled' => $slotsFilled]);
        } else {
            // Insert the the vaccine schedule data
            $this->vaccineScheduleRepository->create($vaccineCenterId, $scheduleDate, $slotsFilled);
        }

        // Check if the daily capacity has been reached
        if ($slotsFilled >= $dailyCapacity) {
            $this->updateVaccineCenterNextAvailableDate($vaccineCenterId, $scheduleDate);
        }
    }

    private function updateVaccineCenterNextAvailableDate(int $vaccineCenterId, string $scheduleDate)
    {
        // Get the next working day
        $nextWorkingDay = Helpers::getNextWorkingDate($scheduleDate);

        // Update the vaccine center's available date
        $data = ['available_date' => $nextWorkingDay];
        $this->vaccineCenterRepository->update($vaccineCenterId, $data);
    }
}
