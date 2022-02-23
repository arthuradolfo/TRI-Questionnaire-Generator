<?php

namespace Database\Seeders;

use App\Models\StudentAbilityLog;
use Faker\Factory;
use Illuminate\Database\Seeder;

class StudentAbilityLogsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StudentAbilityLog::query()->delete();

        $faker = Factory::create();

        StudentAbilityLog::create([
            'user_id' => '91b35879-c5b1-4876-a50f-e37a79a98cbb',
            'student_id' => '91c35879-c9b1-4876-a50f-e37a79a96cbb',
            'ability' => 0,
            'time' => $faker->dateTime
        ]);
    }
}
