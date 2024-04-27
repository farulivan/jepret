<?php

namespace App\Services\AuthService;

use App\Models\User;

interface AuthServiceInterface
{
    public function isAuthAttemptValid(array $credentials): bool;
    public function createAccessToken(User $user): string;
    public function createRefreshToken(User $user): string;
    public function isAuthHasRefreshToken(User $user): bool;
    public function isAuthHasAccessToken(User $user): bool;
}
