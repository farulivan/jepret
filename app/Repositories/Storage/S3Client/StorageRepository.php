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

    /**
     * Creates a pre-signed URL for file upload to AWS S3.
     *
     *
     * @param string $fileKey The key under which the file will be stored in the bucket.
     * @param int $minutes The duration in minutes for which the generated URL will remain valid.
     * @return string The generated pre-signed URL.
     */
    public function createPresignedUrl(string $fileKey, int $minutes = 15): string
    {
        $bucket = config('filesystems.disks.s3.bucket');
        $expiration = '+' . $minutes . ' minutes';

        $cmd = $this->s3Client->getCommand('PutObject', [
            'Bucket' => $bucket,
            'Key' => $fileKey,
            'ACL' => 'public-read',
        ]);

        $presignedRequest = $this->s3Client->createPresignedRequest($cmd, $expiration);

        return (string)$presignedRequest->getUri();
    }
}
