<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\JsonResponseHelper;
use App\Services\AuthServices\AuthService;

class ValidateAccessToken
{

    public function __construct(protected AuthService $authService)
    {
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
        $accessToken = $request->bearerToken();

        if (!$this->authService->isAccessTokenValid($accessToken)) {
            return JsonResponseHelper::unauthorizedErrorAccessToken();
        }

        return $next($request);
    }
}
