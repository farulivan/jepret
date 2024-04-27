<?php

namespace App\Services\PostService;

use Illuminate\Database\Eloquent\Collection;

interface PostServiceInterface
{
    public function getPosts(): Collection|array|null;
}
