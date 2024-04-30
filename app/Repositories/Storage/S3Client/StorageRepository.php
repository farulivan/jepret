<?php

namespace App\Repositories\Storage\S3Client;

use Aws\S3\S3Client;
use App\Repositories\Storage\StorageRepositoryInterface;

class StorageRepository implements StorageRepositoryInterface
{
    protected S3Client $s3Client;

    public function __construct()
    {
        $this->s3Client = new S3Client([
            'version' => 'latest',
            'region' => config('filesystems.disks.s3.region'),
            'use_path_style_endpoint' => config('filesystems.disks.s3.use_path_style_endpoint'),
            'endpoint' => config('filesystems.disks.s3.endpoint'),
            'credentials' => [
                'key' => config('filesystems.disks.s3.key'),
                'secret' => config('filesystems.disks.s3.secret'),
            ],
        ]);
    }

    public function createPresignedUrl(string $fileKey): string
    {
        $bucket = config('filesystems.disks.s3.bucket');

        $cmd = $this->s3Client->getCommand('PutObject', [
            'Bucket' => $bucket,
            'Key' => $fileKey,
            'ACL' => 'public-read',
        ]);

        $presignedRequest = $this->s3Client->createPresignedRequest($cmd, '+15 minutes');

        return (string)$presignedRequest->getUri();
    }
}
