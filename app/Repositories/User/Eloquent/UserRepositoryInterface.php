<?php

namespace App\Repositories\User\Eloquent;

use App\Models\User;
use App\Repositories\BaseRepositoryInterface;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function getByEmail($email): ?User;
}