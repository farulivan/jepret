<?php

namespace App\Services\PostServices;

use Illuminate\Database\Eloquent\Collection;

interface PostServiceInterface
{
    public function getPosts(): Collection|array|null;
    public function generatePhotoUrl(int $expirationMinutes = 15): string|null;
    public function store(string $token, array $data);
}
