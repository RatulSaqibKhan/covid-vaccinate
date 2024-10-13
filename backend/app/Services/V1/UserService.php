<?php

namespace App\Services\V1;

use App\DTOs\CloudEventDTO;
use App\DTOs\RegisterUserDTO;
use App\Events\UserRegisterEventEmitted;
use App\Interfaces\UserRepositoryInterface;

class UserService
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function registerUser(RegisterUserDTO $userDto)
    {
        $user = $this->userRepository->create($userDto->toArray());

        event(new UserRegisterEventEmitted($user));

        return $user;
    }

    public function scheduleVaccination(array $user) {
        // TODO: implement this method
        dump($user);
    }
}
