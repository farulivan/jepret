<?php

namespace App\Repositories\Storage;

interface StorageRepositoryInterface
{
    public function createPresignedUrl(string $fileKey, int $minutes = 15): string;
}
