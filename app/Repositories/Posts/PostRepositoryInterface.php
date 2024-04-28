<?php

namespace App\Repositories\Posts;

interface PostRepositoryInterface
{
    public function get(): \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|array|null;
    public function create(array $data, array $options = []);
}
