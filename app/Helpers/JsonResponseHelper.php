<?php

namespace App\Helpers;

use App\Enums\ErrorMessage;
use App\Enums\ErrorType;
use App\Enums\HttpStatus;

class JsonResponseHelper
{
    public static function successLogin($user, $accessToken, $refreshToken)
    {
        return response()->json([
            "ok" => true,
            "data" => [
                "access_token" => $accessToken,
                "refresh_token" => $refreshToken,
                "handle" => $user->handle,
                "email" => $user->email,
            ]
        ], HttpStatus::SUCCESS);
    }

    public static function unauthorizedErrorLogin()
    {
        return response()->json([
            "ok" => false,
            "err" => ErrorType::INVALID_CREDS,
            "msg" => ErrorMessage::INVALID_CREDS,
        ], HttpStatus::UNAUTHORIZED);
    }

    public static function successRefreshToken($accessToken)
    {
        return response()->json([
            "ok" => true,
            "data" => [
                "access_token" => $accessToken
            ]
        ], HttpStatus::SUCCESS);
    }

    public static function unauthorizedErrorRefreshToken()
    {
        return response()->json([
            "ok" => false,
            "err" => ErrorType::INVALID_REFRESH_TOKEN,
            "msg" => ErrorMessage::INVALID_REFRESH_TOKEN,
        ], HttpStatus::UNAUTHORIZED);
    }

    public static function successDiscoverPosts($posts)
    {
        return response()->json([
            "ok" => true,
            "data" => [
                "posts" => $posts,
            ]
        ], HttpStatus::SUCCESS);
    }

    public static function successRequestPhotoUrl($photoUrl)
    {
        return response()->json([
            "ok" => true,
            "data" => [
                "photo_url" => $photoUrl,
            ]
        ], HttpStatus::SUCCESS);
    }

    public static function successSubmitPost($post, $userDetails)
    {
        return response()->json([
            "ok" => true,
            "data" => [
                "id" => $post->id,
                "photo_url" => $post->photo_url,
                "caption" => $post->caption,
                "author_id" => $userDetails->id,
                "author_handle" => $userDetails->handle,
                "created_at" => $post->created_at
            ]
        ], HttpStatus::SUCCESS);
    }

    public static function unauthorizedErrorAccessToken()
    {
        return response()->json([
            "ok" => false,
            "err" => ErrorType::INVALID_ACCESS_TOKEN,
            "msg" => ErrorMessage::INVALID_ACCESS_TOKEN,
        ], HttpStatus::UNAUTHORIZED);
    }

    public static function badRequestError($errorField)
    {
        return response()->json([
            "ok" => false,
            "err" => ErrorType::BAD_REQUEST,
            "msg" => 'invalid value of ' . $errorField,
        ], HttpStatus::BAD_REQUEST);
    }

    public static function internalServerError($errorMessage)
    {
        return response()->json([
            "status" => HttpStatus::INTERNAL_ERROR,
            "err" => ErrorType::INTERNAL_ERROR,
            "msg" => $errorMessage,
        ], HttpStatus::INTERNAL_ERROR);
    }
}
