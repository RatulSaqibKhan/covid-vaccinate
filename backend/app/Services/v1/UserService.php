<?php

namespace App\Services\V1;

use App\DTOs\RegisterUserDTO;
use App\Interfaces\UserRepositoryInterface;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function registerUser(RegisterUserDTO $userDto)
    {
        // Register the user
        $user = $this->userRepository->create($userDto->toArray());
    }
}
