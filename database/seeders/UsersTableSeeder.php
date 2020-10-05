<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Let's clear the users table first
        User::truncate();

        $faker = Factory::create();

        // Let's make sure everyone has the same password and
        // let's hash it before the loop, or else our seeder
        // will be too slow.
        $password = Hash::make('admin');

        User::create([
            'username' => 'admin',
            'email' => 'admin@test.com',
            'firstname' => 'Admin',
            'lastname' => 'Test',
            'idnumber' => '12',
            'password' => $password,
        ]);

        // And now let's generate a few dozen users for our app:
        for ($i = 0; $i < 10; $i++) {
            User::create([
                'username' => $faker->name,
                'email' => $faker->email,
                'firstname' => $faker->name,
                'lastname' => $faker->name,
                'idnumber' => $faker->randomDigit,
                'password' => $password,
            ]);
        }
    }
}
