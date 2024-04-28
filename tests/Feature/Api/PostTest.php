<?php

namespace Tests\Feature\Api;

use PHPUnit\Framework\Attributes\DataProviderExternal;
use Tests\TestCase;

class PostTest extends TestCase
{
    #[DataProviderExternal('Tests\DataProviders\UserDataProvider', 'getUserData')]
    public function test_request_photo_url_success($email, $password)
    {
        $accessToken = $this->postJson(route('api.session.login'), [
            'email' => $email,
            'password' => $password,
        ])->assertOk()->json('data.access_token');
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->postJson(route('api.posts.request-photo-url'))
            ->assertOk()
            ->assertJsonStructure([
                'ok',
                'data' => [
                    'photo_url',
                ],
            ]);
    }

    #[DataProviderExternal('Tests\DataProviders\UserDataProvider', 'getUserData')]
    public function test_request_photo_url_failed($email, $password)
    {
        $accessToken = $this->postJson(route('api.session.login'), [
            'email' => $email,
            'password' => $password,
        ])->assertOk()->json('data.access_token');
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken . ' ---',
        ])->postJson(route('api.posts.request-photo-url'))
            ->assertStatus(401)
            ->assertJsonStructure([
                'ok',
                'err',
                'msg',
            ])->assertJson([
                'ok' => false,
                'err' => 'ERR_INVALID_ACCESS_TOKEN',
                'msg' => 'invalid access token',
            ]);
    }

    #[DataProviderExternal('Tests\DataProviders\UserDataProvider', 'getUserData')]
    public function test_submit_post_success($email, $password)
    {
        $accessToken = $this->postJson(route('api.session.login'), [
            'email' => $email,
            'password' => $password,
        ])->assertOk()->json('data.access_token');
        $photoUrl = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->postJson(route('api.posts.request-photo-url'))->assertOk()->json('data.photo_url');
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->postJson(route('api.posts.submit-post'), [
            'photo_url' => $photoUrl,
            'caption' => 'test caption',
        ])->assertOk()
            ->assertJsonStructure([
                'ok',
                'data' => [
                    'id',
                    'photo_url',
                    'caption',
                    'author_id',
                    'author_handle',
                    'created_at',
                ],
            ]);
    }

    #[DataProviderExternal('Tests\DataProviders\UserDataProvider', 'getUserData')]
    public function test_submit_post_failed($email, $password)
    {
        $accessToken = $this->postJson(route('api.session.login'), [
            'email' => $email,
            'password' => $password,
        ])->assertOk()->json('data.access_token');
        $photoUrl = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->postJson(route('api.posts.request-photo-url'))->assertOk()->json('data.photo_url');

        // token invalid
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken . ' ---',
        ])->postJson(route('api.posts.submit-post'), [])
            ->assertStatus(401)
            ->assertJsonStructure([
                'ok',
                'err',
                'msg',
            ])->assertJson([
                'ok' => false,
                'err' => 'ERR_INVALID_ACCESS_TOKEN',
                'msg' => 'invalid access token',
            ]);

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->postJson(route('api.posts.submit-post'), [
            'photo_url' => $photoUrl,
            'caption' => null,
        ])
            ->assertStatus(400)
            ->assertJsonStructure([
                'ok',
                'err',
                'msg',
            ])->assertJson([
                'ok' => false,
                'err' => 'ERR_BAD_REQUEST',
                'msg' => 'invalid value of caption',
            ]);
    }

    public function test_discover_posts()
    {
        $response = $this->getJson(route('api.posts.discover-posts'));
        $response->assertOk();

        if ($response->isOk() && count($response->json('data.posts')) > 0) {
            $response->assertJsonStructure([
                'ok',
                'data' => [
                    'posts' => [
                        '*' => [
                            'id',
                            'photo_url',
                            'caption',
                            'author_id',
                            'author_handle',
                            'created_at',
                        ],
                    ],
                ],
            ]);
        } else {
            $response->assertJsonStructure([
                'ok',
                'data' => [
                    'posts',
                ],
            ]);
        }
    }
}
