<?php

namespace App\Repositories\Storage;

interface StorageRepositoryInterface
{
    public function createPresignedUrl(string $fileKey): string;
}
