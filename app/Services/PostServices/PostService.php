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

    public function getPosts(): Collection|array|null
    {
        return $this->postRepository->get();
    }

    public function generatePhotoUrl(): string
    {
        $key = time() . '_' . bin2hex(random_bytes(16)) . '.jpg';
        return $this->storageRepository->createPresignedUrl($key);
    }

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
