<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([

            [
                'email' => 'admin@gmail.com',
                'name' => 'Admin',
                'password' => Hash::make('alimalik'),
                'role' => 'admin'
            ],
            [
                'email' => 'user@gmail.com',
                'name' => 'User',
                'password' => Hash::make('alimalik'),
                'role' => 'user'
            ]
        ]);
    }
}
