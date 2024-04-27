<?php

namespace App\Services\AuthServices;

use App\Enums\AuthEnum;
use App\Repositories\Token\TokenRepositoryInterface;
use App\Repositories\User\Eloquent\UserRepositoryInterface;

/**
 * Class AuthService
 *
 * Service class for auth-related operations.
 */
class AuthService implements AuthServiceInterface
{
    protected $userRepository;
    protected $tokenRepository;

    public function __construct(UserRepositoryInterface $userRepository, TokenRepositoryInterface $tokenRepository)
    {
        $this->userRepository = $userRepository;
        $this->tokenRepository = $tokenRepository;
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
     * Validates whether a refresh token is valid.
     *
     * @param string|null $refreshToken The refresh token to validate.
     * @return bool True if the refresh token is valid, otherwise false.
     */
    public function isRefreshTokenValid(?string $refreshToken): bool
    {
        if ($refreshToken === null) {
            return false;
        }

        if (!$this->tokenRepository->findToken($refreshToken)) {
            return false;
        }

        if ($this->tokenRepository->isTokenExpired($refreshToken)) {
            return false;
        }

        if (!$this->tokenRepository->tokenHasAbility($refreshToken, AuthEnum::REFRESH_TOKEN_ABILITY)) {
            return false;
        }

        return true;
    }

    /**
     * Retrieves the user associated with a given refresh token.
     *
     * @param string $refreshToken The refresh token whose associated user is to be retrieved.
     * @return object|null The user object if found, otherwise null.
     */
    public function getUserFromRefreshToken(string $refreshToken): ?object
    {
        return $this->tokenRepository->getUserFromToken($refreshToken);
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
