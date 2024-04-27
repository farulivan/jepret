<?php

namespace App\Services\AuthService;

use App\Enums\AuthEnum;
use App\Models\User;

/**
 * Class AuthService
 *
 * Service class for auth-related operations.
 */
class AuthService implements AuthServiceInterface
{
    /**
     * Validate user authentication attempt.
     *
     * @param array $credentials The user credentials (email and password).
     * @return bool True if the authentication attempt is valid, otherwise false.
     */
    public function isAuthAttemptValid(array $credentials): bool
    {
        // Extract email and password from credentials array
        $email = $credentials['email'] ?? null;
        $password = $credentials['password'] ?? null;

        // If email or password is missing, authentication fails
        if (!$email || !$password) {
            return false;
        }

        // Attempt to find the user by email
        $user = User::where('email', $email)->first();

        // If user does not exist or password does not match, authentication fails
        if (!$user || $user->password !== $password) {
            return false;
        }

        // Authentication successful
        return true;
    }

    /**
     * Create a new access token for the user.
     *
     * @param  \App\Models\User  $user
     * @return string
     */
    public function createAccessToken(User $user): string
    {
        $token = $user->createToken(
            AuthEnum::AUTH_TOKEN_NAME,
            [AuthEnum::AUTH_TOKEN_ABILITY],
            now()->addSeconds(AuthEnum::TOKEN_SECONDS_EXPIRED)
        )
            ->plainTextToken;
        return $token;
    }

    /**
     * Create a new refresh token for the user.
     *
     * @param  \App\Models\User  $user
     * @return string
     */
    public function createRefreshToken(User $user): string
    {
        $token = $user->createToken(AuthEnum::REFRESH_TOKEN_NAME, [AuthEnum::REFRESH_TOKEN_ABILITY])
            ->plainTextToken;
        return $token;
    }

    /**
     * Check if the user has a refresh token.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function isAuthHasRefreshToken(User $user): bool
    {
        return $user->tokenCan(AuthEnum::REFRESH_TOKEN_ABILITY);
    }

    /**
     * Check if the user has an access token.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function isAuthHasAccessToken(User $user): bool
    {
        return $user->tokenCan(AuthEnum::AUTH_TOKEN_ABILITY);
    }
}
