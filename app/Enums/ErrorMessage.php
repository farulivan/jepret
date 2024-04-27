<?php

namespace App\Enums;

class ErrorMessage
{
    const INVALID_CREDS = "incorrect email or password";
    const INVALID_ACCESS_TOKEN = "invalid access token";
    const INVALID_REFRESH_TOKEN = "invalid refresh token";
    const FORBIDDEN_ACCESS = "user doesn't have enough authorization";
    const NOT_FOUND = "resource is not found";
}
