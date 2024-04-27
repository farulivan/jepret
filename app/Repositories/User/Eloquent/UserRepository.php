<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct(User $model)
    {
        parent::__construct($model);
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
