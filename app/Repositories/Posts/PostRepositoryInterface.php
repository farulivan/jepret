<?php

namespace App\Repositories\Posts;

interface PostRepositoryInterface
{
    public function get();
    public function create(array $data, array $options = []);
}
