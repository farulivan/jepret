<?php

namespace Tests\DataProviders;

class UserDataProvider
{
    public static function getUserData(): array
    {
        return [
            [
                'email' => 'alice@mail.com',
                'password' => '123456',
            ],
            [
                'email' => 'bob@mail.com',
                'password' => '123456',
            ],
            [
                'email' => 'cherry@mail.com',
                'password' => '123456',
            ],
        ];
    }

}
