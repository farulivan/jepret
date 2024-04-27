<?php

namespace App\Services\UserServices;

interface UserServiceInterface
{
    public function getByEmail($email): ?object;
}
