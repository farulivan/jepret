<?php

namespace App\Enums;

class ErrorType
{
    const BAD_REQUEST = "ERR_BAD_REQUEST";
    const INVALID_CREDS = "ERR_INVALID_CREDS";
    const INVALID_ACCESS_TOKEN = "ERR_INVALID_ACCESS_TOKEN";
    const FORBIDDEN_ACCESS = "ERR_FORBIDDEN_ACCESS";
    const NOT_FOUND= "ERR_NOT_FOUND";
    const INTERNAL_ERROR = "ERR_INTERNAL_ERROR";
}