<?php

namespace App\Services\PostServices;

use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Posts\PostRepositoryInterface;
use App\Repositories\Storage\StorageRepositoryInterface;
use App\Services\AuthServices\AuthServiceInterface;
use App\Helpers\JsonResponseHelper;

class PostService implements PostServiceInterface
{
    public function __construct(
        protected PostRepositoryInterface $postRepository,
        protected AuthServiceInterface $authService,
        protected StorageRepositoryInterface $storageRepository,
    ) {
    }

    /**
     * Retrieves a collection of posts.
     *
     * @return Collection|array|null Returns a collection of posts, an array, or null if no posts are available.
     */
    public function getPosts(): Collection|array|null
    {
        return $this->postRepository->get();
    }

    /**
     * Generates a pre-signed URL for photo uploads.
     *
     * @param int $expirationMinutes The number of minutes the URL will remain valid.
     * @return string Returns the generated pre-signed URL.
     */
    public function generatePhotoUrl(int $expirationMinutes = 15): string
    {
        $key = time() . '_' . bin2hex(random_bytes(16)) . '.jpg';
        return $this->storageRepository->createPresignedUrl($key, $expirationMinutes);
    }

    /**
     * Stores a new post in the database.
     *
     * @param string $token The authentication token used to identify and authenticate the user.
     * @param array $data Data to be stored as part of the post.
     * @return mixed Returns the newly created post object or an error response if unauthorized.
     */
    public function store(string $token, array $data)
    {
        $user = $this->authService->getUserFromToken($token);

        if (!$user) {
            return JsonResponseHelper::unauthorizedErrorAccessToken();
        }

        $data['author_id'] = $user->id;
        $data['created_at'] = time();
        $post = $this->postRepository->create($data);

        return $post;
    }
}
