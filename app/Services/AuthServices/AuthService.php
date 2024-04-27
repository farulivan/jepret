<?php

namespace App\Services\AuthServices;

use App\Enums\AuthEnum;
use App\Repositories\User\Eloquent\UserRepositoryInterface;

/**
 * Class AuthService
 *
 * Service class for auth-related operations.
 */
class AuthService implements AuthServiceInterface
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

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

        // Retrieve user by email
        $user = $this->userRepository->getByEmail($email);

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
     * @param  object  $user The user data.
     * @return string
     */
    public function createAccessToken(object $user): string
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
     * @param  object  $user The user data.
     * @return string
     */
    public function createRefreshToken(object $user): string
    {
        $token = $user->createToken(AuthEnum::REFRESH_TOKEN_NAME, [AuthEnum::REFRESH_TOKEN_ABILITY])
            ->plainTextToken;
        return $token;
    }

    /**
     * Check if the user has a refresh token.
     *
     * @param  object  $user The user data.
     * @return bool
     */
    public function isAuthHasRefreshToken(object $user): bool
    {
        return $user->tokenCan(AuthEnum::REFRESH_TOKEN_ABILITY);
    }

    /**
     * Check if the user has an access token.
     *
     * @param  object  $user The user data.
     * @return bool
     */
    public function isAuthHasAccessToken(object $user): bool
    {
        return $user->tokenCan(AuthEnum::AUTH_TOKEN_ABILITY);
    }
}
