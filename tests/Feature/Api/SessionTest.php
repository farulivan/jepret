<?php

namespace Feature\Api;

use PHPUnit\Framework\Attributes\DataProviderExternal;
use Tests\TestCase;

class SessionTest extends TestCase
{
    #[DataProviderExternal('Tests\DataProviders\UserDataProvider', 'getUserData')]
    public function test_login_success($email, $password)
    {
        $response = $this->postJson(route('api.session.login'), [
            'email' => $email,
            'password' => $password,
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'ok',
                'data' => [
                    'access_token',
                    'refresh_token',
                    'handle',
                    'email',
                ],
            ]);
    }

    #[DataProviderExternal('Tests\DataProviders\UserDataProvider', 'getUserData')]
    public function test_login_failed($email, $password)
    {
        $response = $this->postJson(route('api.session.login'), [
            'email' => $email,
            'password' => $password . '_',
        ]);

        $response
            ->assertStatus(401)
            ->assertJsonStructure([
                'ok',
                'err',
                'msg',
            ])->assertJson([
                'ok' => false,
                'err' => 'ERR_INVALID_CREDS',
                'msg' => 'incorrect email or password',
            ]);
    }

    #[DataProviderExternal('Tests\DataProviders\UserDataProvider', 'getUserData')]
    public function test_refresh_access_token_success($email, $password)
    {
        $refreshtoken = $this->postJson(route('api.session.login'), [
            'email' => $email,
            'password' => $password,
        ])->assertOk()->json('data.refresh_token');

        $response = $this->putJson(route('api.session.refresh-access-token'), [], [
            'Authorization' => 'Bearer ' . $refreshtoken,
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'ok',
                'data' => [
                    'access_token',
                ],
            ]);
    }

    #[DataProviderExternal('Tests\DataProviders\UserDataProvider', 'getUserData')]
    public function test_refresh_access_token_failed($email, $password)
    {
        $data = $this->postJson(route('api.session.login'), [
            'email' => $email,
            'password' => $password,
        ])->assertOk()->json('data');

        // make access using access token
        $this->putJson(route('api.session.refresh-access-token'), [], [
            'Authorization' => 'Bearer ' . $data['access_token'],
        ])->assertStatus(401)
            ->assertJsonStructure([
                'ok',
                'err',
                'msg',
            ]);
        // make access using wrong refresh access token
        $this->putJson(route('api.session.refresh-access-token'), [], [
            'Authorization' => 'Bearer ' . $data['refresh_token'] .'-----',
        ])->assertStatus(401)
            ->assertJsonStructure([
                'ok',
                'err',
                'msg',
            ])->assertJson([
                'ok' => false,
                'err' => 'ERR_INVALID_REFRESH_TOKEN',
                'msg' => 'invalid refresh token',
            ]);
    }
}
