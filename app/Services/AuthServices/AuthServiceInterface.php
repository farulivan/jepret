<?php

namespace App\Services\AuthServices;

interface AuthServiceInterface
{
    public function isAuthAttemptValid(array $credentials): bool;
    public function isRefreshTokenValid(string $refreshToken): bool;
    public function isAccessTokenValid(?string $accessToken): bool;
    public function getUserFromToken(string $refreshToken): ?object;
    public function createAccessToken(object $user): string;
    public function createRefreshToken(object $user): string;
}
