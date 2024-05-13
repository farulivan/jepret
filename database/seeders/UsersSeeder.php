<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
                'password' => Hash::make('123456')
            ],
            [
                'email' => 'bob@mail.com',
                'handle' => 'bob',
                'password' => Hash::make('123456')
            ],
            [
                'email' => 'cherry@mail.com',
                'handle' => 'cherry',
                'password' => Hash::make('123456')
            ]
        ]);
    }
}
