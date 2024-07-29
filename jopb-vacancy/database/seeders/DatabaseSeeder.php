<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


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
            'username' => 'admin',
            'email' => 'admin@example.com',
            'name' => 'Administrator',
            'photo' => 'default.jpg',
            'role' => 'admin',
            'password' => bcrypt('secret')
        ]);
    }
}
