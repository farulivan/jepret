<?php

namespace App\Services\UserServices;

use App\Repositories\User\Eloquent\UserRepositoryInterface;

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
     * @return object|null The user with the specified email, or null if not found.
     */
    public function getByEmail($email): ?object
    {
        return $this->userRepository->getByEmail($email);
    }
}
