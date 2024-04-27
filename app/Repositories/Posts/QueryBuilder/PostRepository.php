<?php

namespace App\Repositories\Posts\QueryBuilder;

use App\Repositories\BaseRepositoryInterface;
use App\Repositories\Posts\PostRepositoryInterface;
use Illuminate\Support\Facades\DB;

class PostRepository implements PostRepositoryInterface, BaseRepositoryInterface
{
    public function get():  \Illuminate\Support\Collection
    {
        return DB::table('posts')
            ->select([
                DB::raw('posts.id as id'),
                DB::raw('posts.photo_url as photo_url'),
                DB::raw('posts.caption as caption'),
                DB::raw('users.id as author_id'),
                DB::raw('users.handle as author_handle'),
                DB::raw('posts.created_at as created_at'),
            ])
            ->join('users', 'posts.author_id', '=', 'users.id')
            ->latest()
            ->limit(50)
            ->get();
    }

    public function create(array $data, array $options = []): object
    {
        $id = DB::table('posts')->insertGetId($data);

        return DB::table('posts')
            ->select([
                DB::raw('posts.id as id'),
                DB::raw('posts.photo_url as photo_url'),
                DB::raw('posts.caption as caption'),
                DB::raw('users.id as author_id'),
                DB::raw('users.handle as author_handle'),
                DB::raw('posts.created_at as created_at'),
            ])
            ->join('users', 'posts.author_id', '=', 'users.id')
            ->where('id', $id)
            ->first();
    }
}
