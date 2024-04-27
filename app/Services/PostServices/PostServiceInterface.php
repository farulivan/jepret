<?php

namespace App\Services\PostServices;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;

interface PostServiceInterface
{
    public function getPosts(): Collection|array|null;

    public function generatePhotoUrl(): string|null;

    public function store(string $token, array $data);
}
