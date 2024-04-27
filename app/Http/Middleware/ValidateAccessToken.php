<?php

namespace App\Http\Middleware;

use App\Helpers\JsonResponseHelper;
use Closure;
use Illuminate\Http\Request;
use App\Repositories\Token\TokenRepositoryInterface;

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

        // TODO: Need to make sure it's Access Token not Refresh Token
        if (!$token || !$this->tokenRepository->findToken($token) || $this->tokenRepository->isTokenExpired($token)) {
            return JsonResponseHelper::unauthorizedErrorAccessToken();
        }

        return $next($request);
    }
}
