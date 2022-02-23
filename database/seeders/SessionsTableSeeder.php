<?php

namespace Database\Seeders;

use App\Models\Session;
use Faker\Factory;
use Illuminate\Database\Seeder;

class SessionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Session::query()->delete();

        $faker = Factory::create();

        Session::create([
            'id' => '91b0106b-4280-4162-9797-d1b429236c7f',
            'user_id' => '91b35879-c5b1-4876-a50f-e37a79a98cbb',
            'student_id' => '91c35879-c9b1-4876-a50f-e37a79a96cbb',
            'category_id' => '91b0106b-4280-4162-9797-d1b423236c7c',
            'status' => Session::STARTED,
            'tqg_id' => 1,
            'number_questions' => 10,
            'time_started' => $faker->dateTime
        ]);

        // And now let's generate a few dozen users for our app:
        /*for ($i = 0; $i < 10; $i++) {
            Answer::create([
                'question_id' => Str::orderedUuid(),
                'fraction' => 100,
                'format' => 'html',
                'text' => $faker->text,
            ]);
        }*/
    }
}
