<?php

namespace App\Services\AuthServices;

use App\Enums\AuthEnum;
use App\Repositories\Token\TokenRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

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

        if ($user && Hash::check($password, $user->password)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Validates whether a refresh token is valid.
     *
     * @param string|null $refreshToken The refresh token to validate.
     * @return bool True if the refresh token is valid, otherwise false.
     */
    public function isRefreshTokenValid(?string $refreshToken): bool
    {
        return $this->isTokenValid($refreshToken, AuthEnum::REFRESH_TOKEN_ABILITY);
    }

    /**
     * Validates whether an access token is valid.
     *
     * @param string|null $accessToken The access token to validate.
     * @return bool True if the access token is valid, otherwise false.
     */
    public function isAccessTokenValid(?string $accessToken): bool
    {
        return $this->isTokenValid($accessToken, AuthEnum::AUTH_TOKEN_ABILITY);
    }

    private function isTokenValid(?string $token, string $ability): bool
    {
        if ($token === null) {
            return false;
        }

        if (!$this->tokenRepository->findToken($token)) {
            return false;
        }

        if ($this->tokenRepository->isTokenExpired($token)) {
            return false;
        }

        if (!$this->tokenRepository->tokenHasAbility($token, $ability)) {
            return false;
        }

        return true;
    }

    /**
     * Retrieves the user associated with a given token.
     *
     * @param string $token The token whose associated user is to be retrieved.
     * @return object|null The user object if found, otherwise null.
     */
    public function getUserFromToken(string $refreshToken): ?object
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
}
