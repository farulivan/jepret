<?php

namespace App\Repositories\Posts\QueryBuilder;

use App\Repositories\Posts\PostRepositoryInterface;
use Illuminate\Support\Facades\DB;

class PostRepository implements PostRepositoryInterface
{
    public function get()
    {
        return DB::table('posts')
            ->select([
                DB::raw('posts.id as id'),
                DB::raw('posts.photo_url as photo_url'),
                DB::raw('posts.caption as caption'),
                DB::raw('users.id as author_id'),
                DB::raw('users.name as author_handle'),
                DB::raw('posts.created_at as created_at'),
            ])
            ->join('users', 'posts.author_id', '=', 'users.id')
            ->latest()
            ->limit(50)
            ->get();
    }

    public function create(array $data, array $options = [])
    {
        return DB::table('posts')->insert($data);
    }
}
