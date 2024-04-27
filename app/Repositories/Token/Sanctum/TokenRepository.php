<?php

namespace App\Repositories\Token\Sanctum;

use Illuminate\Support\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Sanctum\PersonalAccessToken;
use App\Repositories\Token\TokenRepositoryInterface;

class TokenRepository implements TokenRepositoryInterface
{
    /**
     * Find a token by its identifier.
     *
     * @param string $token The unique identifier of the token to find.
     * @return object|null The found token object, or null if no token is found.
     */

    public function findToken(string $token): ?object
    {
        return PersonalAccessToken::findToken($token);
    }

    /**
     * Retrieve the user associated with a specific token.
     *
     * @param string $token The token whose associated user is to be retrieved.
     * @return Authenticatable|null The user associated with the token, or null if none.
     */
    public function getUserFromToken(string $token): ?Authenticatable
    {
        $tokenModel = $this->findToken($token);
        if ($tokenModel) {
            return $tokenModel->tokenable;  // tokenable is the relationship name defined by Sanctum
        }
        return null;
    }

    /**
     * Check if a token has a specific ability.
     *
     * @param string $token The token to check.
     * @param string $ability The ability to check for.
     * @return bool True if the token has the specified ability, false otherwise.
     */
    public function tokenHasAbility(string $token, string $ability): bool
    {
        $tokenModel = $this->findToken($token);
        return $tokenModel ? $tokenModel->can($ability) : false;
    }

    /**
     * Determine if a token has expired.
     *
     * @param string $token The token to check for expiration.
     * @return bool True if the token is expired, false otherwise.
     */
    public function isTokenExpired(string $token): bool
    {
        $tokenModel = $this->findToken($token);
        if ($tokenModel && $tokenModel->expires_at) {
            return Carbon::parse($tokenModel->expires_at)->isPast();
        }
        return false;
    }
}
