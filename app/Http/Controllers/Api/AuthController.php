<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Helpers\JsonResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthServices\AuthServiceInterface;
use App\Services\UserServices\UserServiceInterface;

class AuthController extends Controller
{
    protected $userService;
    protected $authService;

    public function __construct(UserServiceInterface $userService, AuthServiceInterface $authService)
    {
        $this->userService = $userService;
        $this->authService = $authService;
    }

    /**
     * Handle user login.
     *
     * @param LoginRequest  $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();

        //attempt to login
        if (!$this->authService->isAuthAttemptValid($data)) {
            return JsonResponseHelper::unauthorizedErrorLogin();
        }

        $user =  $this->userService->getByEmail($data['email']);
        $accessToken = $this->authService->createAccessToken($user);
        $refreshToken = $this->authService->createRefreshToken($user);
        return JsonResponseHelper::successLogin(new UserResource($user), $accessToken, $refreshToken);
    }

    /**
     * Refreshes the user's access token.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function refreshAccessToken(Request $request): JsonResponse
    {
        $refreshToken = $request->bearerToken();

        if (!$this->authService->isRefreshTokenValid($refreshToken)) {
            return JsonResponseHelper::unauthorizedErrorRefreshToken();
        }

        $user = $this->authService->getUserFromToken($refreshToken);
        if (!$user) {
            return JsonResponseHelper::unauthorizedErrorRefreshToken();
        }

        $newAccessToken = $this->authService->createAccessToken($user);
        return JsonResponseHelper::successRefreshToken($newAccessToken);
    }
}
