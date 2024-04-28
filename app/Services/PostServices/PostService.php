<?php

namespace App\Services\PostServices;

use Aws\S3\S3Client;
use App\Helpers\JsonResponseHelper;
use App\Repositories\Posts\PostRepositoryInterface;
use App\Services\AuthServices\AuthServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class PostService implements PostServiceInterface
{
    public function __construct(
        protected PostRepositoryInterface $postRepository,
        protected AuthServiceInterface $authService,
    ) {
    }

    public function getPosts(): Collection|array|null
    {
        return $this->postRepository->get();
    }

    public function generatePhotoUrl(): string
    {
        $s3Client = new S3Client([
            'version' => 'latest',
            'region' => config('filesystems.disks.s3.region'),
            'credentials' => [
                'key' => config('filesystems.disks.s3.key'),
                'secret' => config('filesystems.disks.s3.secret'),
            ],
        ]);

        // Nama bucket S3 Anda
        $bucket = config('filesystems.disks.s3.bucket');
        $key = time() . '_' . bin2hex(random_bytes(16)) . '.jpg';

        // Membuat URL presigned untuk upload
        $cmd = $s3Client->getCommand('PutObject', [
            'Bucket' => $bucket,
            'Key' => $key,
            'ACL' => 'public-read',
        ]);

        // URL berlaku selama 15 menit
        $presignedRequest = $s3Client->createPresignedRequest($cmd, '+15 minutes');

        return (string)$presignedRequest->getUri();
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
