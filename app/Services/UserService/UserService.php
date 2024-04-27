<?php

namespace App\Services\UserService;

use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;

/**
 * Class UserService
 *
 * Service class for user-related operations.
 */
class UserService implements UserServiceInterface
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Retrieve a user by email.
     *
     * @param string $email The email of the user to retrieve.
     * @return \App\Models\User|null The user with the specified email, or null if not found.
     */
    public function getByEmail($email): ?User
    {
        return $this->userRepository->getByEmail($email);
    }
}
