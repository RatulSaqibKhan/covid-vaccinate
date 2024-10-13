<?php

namespace App\Services\V1;

use App\DTOs\RegisterUserDTO;
use App\Interfaces\UserRepositoryInterface;

class UserService
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function registerUser(RegisterUserDTO $userDto)
    {
        return $this->userRepository->create($userDto->toArray());
    }
}
