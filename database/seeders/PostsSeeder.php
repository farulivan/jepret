<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostsSeeder extends Seeder
{
    public function run(): void
    {
        $currentTs = now()->timestamp;
        $records = [];
        for ($i = 1; $i <= 3; $i++) {
            $records[] = [
                'id' => $i,
                'photo_url' => 'https://picsum.photos/id/' . $i + 15 . '/600/400',
                'caption' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                'author_id' => $i,
                'created_at' => $currentTs
            ];
        }

        DB::table('posts')->insert($records);
    }
}
