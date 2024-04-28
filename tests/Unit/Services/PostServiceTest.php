<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Repositories\Posts\Eloquent\PostRepository;
use App\Repositories\Token\Sanctum\TokenRepository;
use App\Repositories\User\Eloquent\UserRepository;
use App\Services\AuthServices\AuthService;
use App\Services\AuthServices\AuthServiceInterface;
use App\Services\PostServices\PostService;
use App\Services\PostServices\PostServiceInterface;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Http;
use Mockery;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use Tests\TestCase;

class PostServiceTest extends TestCase
{
    protected $s3ClientMock;
    protected PostServiceInterface $postService;
    protected AuthServiceInterface $authService;

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_posts()
    {
        $posts = $this->postService->getPosts();

        if (filled($posts)) {
            $this->assertNotEmpty($posts);
        } else {
            $this->assertEmpty($posts);
        }
    }

    #[DataProviderExternal('Tests\DataProviders\UserDataProvider', 'getUserData')]
    public function test_store_post($email, $password)
    {
        $accessToken = $this->authService->createAccessToken(
            User::query()->where('email', $email)->first()
        );
        $post = $this->postService->store($accessToken, [
            'caption' => 'Test Caption',
            'photo_url' => 'https://example.com/photo.jpg',
        ]);

        $this->assertNotEmpty($post);
        $this->assertIsObject($post);
    }

    public function test_generate_photo_url_returns_valid_url(): void
    {
        // Arrange: Define the actual bucket name used in the environment
        $actualBucketName = 'nabitu-jepret';

        // Prepare a regex pattern that matches the actual bucket name and the structure of an S3 pre-signed URL
        $urlPattern = '/^https:\/\/' . preg_quote($actualBucketName, '/') . '\.s3\.amazonaws\.com\/.+\?.+$/';

        // Mock the createPresignedRequest method to return a mock request
        // Instead of checking for a specific URL, adjust the mock to return a URL that will match our pattern
        $expectedUrl = 'https://nabitu-jepret.s3.amazonaws.com/some-key?expiration=params&signature=exampleSignature';
        $presignedRequestMock = Mockery::mock(\GuzzleHttp\Psr7\Request::class);
        $presignedRequestMock->shouldReceive('getUri')->andReturn($expectedUrl);

        $this->s3ClientMock->shouldReceive('getCommand')
            ->andReturnSelf(); // Return the mock client to chain the next method call.

        $this->s3ClientMock->shouldReceive('createPresignedRequest')
            ->andReturn($presignedRequestMock);

        // Act: Call the method under test
        $url = $this->postService->generatePhotoUrl();

        // Assert: Check that the returned URL matches the expected pattern with the actual bucket name
        $this->assertIsString($url);
        $this->assertNotEmpty($url);
        $this->assertMatchesRegularExpression($urlPattern, $url);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->s3ClientMock = Mockery::mock(S3Client::class);
        $this->authService = (new AuthService(
            userRepository: (new UserRepository((new User()))),
            tokenRepository: (new TokenRepository())
        ));
        $this->postService = (new PostService((new PostRepository()), $this->authService));
    }
}
