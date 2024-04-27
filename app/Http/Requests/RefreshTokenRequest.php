<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Helpers\JsonResponseHelper;

class RefreshTokenRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    /**
     * Extract the refresh token from the Authorization header.
     */
    public function refreshToken()
    {
        $bearerToken = $this->header('Authorization', '');
        if (strpos($bearerToken, 'Bearer ') === 0) {
            return substr($bearerToken, 7);
        }
        return null;
    }
}
