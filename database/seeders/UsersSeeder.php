<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'email' => 'alice@mail.com',
                'handle' => 'alice',
                'password' => '123456'  // Storing plain text password
            ],
            [
                'email' => 'bob@mail.com',
                'handle' => 'bob',
                'password' => '123456'  // Storing plain text password
            ],
            [
                'email' => 'cherry@mail.com',
                'handle' => 'cherry',
                'password' => '123456'  // Storing plain text password
            ]
        ]);
    }
}
