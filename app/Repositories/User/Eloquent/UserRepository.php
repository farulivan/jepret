<?php

namespace App\Repositories\User\Eloquent;

use App\Models\User;
use App\Repositories\BaseRepository;
use App\Repositories\User\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * Retrieve user by email.
     *
     * @param string $email User's email
     * @return User|null Returns user object or null if not found
     */
    public function getByEmail($email): ?User
    {
        return $this->model->where('email', $email)->first();
    }
}
