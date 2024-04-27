<?php

namespace App\Repositories\Token;

interface TokenRepositoryInterface
{
    public function findToken(string $token): ?object;
    public function getUserFromToken(string $token): ?object;
    public function tokenHasAbility(string $token, string $ability): bool;
    public function isTokenExpired(string $token): bool;
}
