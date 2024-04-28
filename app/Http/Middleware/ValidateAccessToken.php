<?php

namespace App\Http\Middleware;

use App\Helpers\JsonResponseHelper;
use Closure;
use Illuminate\Http\Request;
use App\Repositories\Token\TokenRepositoryInterface;
use Illuminate\Support\Str;

class ValidateAccessToken
{
    protected $tokenRepository;

    public function __construct(TokenRepositoryInterface $tokenRepository)
    {
        $this->tokenRepository = $tokenRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request  $request
     * @param Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        $tokenModel = $this->tokenRepository->findToken($token);

        if (Str::lower($tokenModel->name ?? '') != \App\Enums\AuthEnum::AUTH_TOKEN_NAME) {
            return JsonResponseHelper::unauthorizedErrorAccessToken();
        }

        if (!$tokenModel || $this->tokenRepository->isTokenExpired($token)) {
            return JsonResponseHelper::unauthorizedErrorAccessToken();
        }

        return $next($request);
    }
}
