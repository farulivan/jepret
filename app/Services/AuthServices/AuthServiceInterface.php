<?php

namespace App\Services\AuthServices;

interface AuthServiceInterface
{
    public function isAuthAttemptValid(array $credentials): bool;
    public function createAccessToken(object $user): string;
    public function createRefreshToken(object $user): string;
    public function isAuthHasRefreshToken(object $user): bool;
    public function isAuthHasAccessToken(object $user): bool;
}
