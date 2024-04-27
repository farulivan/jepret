<?php

namespace App\Services\UserService;

use App\Models\User;

interface UserServiceInterface
{
    public function getByEmail($email): ?User;
}
