<?php

namespace App\Enums;

class AuthEnum
{
    const AUTH_TOKEN_NAME = 'access-token';
    const AUTH_TOKEN_ABILITY = 'token-access';
    const REFRESH_TOKEN_NAME = 'refresh-token';
    const REFRESH_TOKEN_ABILITY = 'token-refresh';
    const TOKEN_SECONDS_EXPIRED = 20;
}
