<?php

namespace Database\Seeders;

use App\Models\Student;
use Faker\Factory;
use Illuminate\Database\Seeder;

class StudentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Student::query()->delete();

        // Student::create([
        //     'id' => '91b99879-c5b1-4876-a50f-e37a79a98cbb',
        //     'moodle_id' => '4',
        //     'user_id' => '91b35879-c5b1-4876-a50f-e37a79a98cbb',
        //     'username' => 'admin',
        //     'firstname' => 'Admin',
        //     'lastname' => 'Test',
        //     'email' => 'admin@test.com',
        // ]);

        // Student::create([
        //     'id' => '91c35879-c9b1-4876-a50f-e37a79a97cbb',
        //     'moodle_id' => '2',
        //     'user_id' => '91b35879-c5b1-4876-a50f-e37a79a98cbb',
        //     'username' => 'admin',
        //     'firstname' => 'Admin',
        //     'lastname' => 'Test',
        //     'email' => 'admin1@test.com',
        // ]);

        // Student::create([
        //     'id' => '91c35879-c9b1-4876-a50f-e37a79a96cbb',
        //     'moodle_id' => '3',
        //     'user_id' => '91b35879-c5b1-4876-a50f-e37a79a98cbb',
        //     'username' => 'admin',
        //     'firstname' => 'Admin',
        //     'lastname' => 'Test',
        //     'email' => 'admin2@test.com',
        // ]);

        Student::create([
            'id' => '91c35819-c9b1-4876-a50f-e37a79a96cbb',
            'moodle_id' => '4',
            'user_id' => '91b35879-c5b1-4876-a50f-e37a79a98cbb',
            'username' => 'admin',
            'firstname' => 'Admin',
            'lastname' => 'Test',
            'email' => 'admin3@test.com',
        ]);

        $faker = Factory::create();

        for ($i = 0; $i < 30; $i++)
        {
            Student::create([
                'id' => $faker->uuid,
                'moodle_id' => $faker->randomNumber(),
                'user_id' => '91b35879-c5b1-4876-a50f-e37a79a98cbb',
                'username' => $faker->userName,
                'firstname' => $faker->firstName,
                'lastname' => $faker->lastName,
                'email' => $faker->email,
            ]);
        }
    }
}
