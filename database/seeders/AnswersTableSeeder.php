<?php

namespace Database\Seeders;

use App\Models\Answer;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AnswersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Answer::query()->delete();

        $faker = Factory::create();

        Answer::create([
            'id' => '91b0106b-4280-4162-9797-d1b429236c7f',
            'question_id' => '91b0106b-4280-4162-9797-d1b429236c7c',
            'user_id' => '91b35879-c5b1-4876-a50f-e37a79a98cbb',
            'fraction' => 100,
            'is_correct' => 1,
            'format' => 'html',
            'text' => $faker->text,
            'moodle_id' => 2,
        ]);

        Answer::create([
            'id' => '91b0106b-4280-4162-9797-d1b429236c7s',
            'question_id' => '91b0106b-4280-4162-9797-d1b429236c7c',
            'user_id' => '91b35879-c5b1-4876-a50f-e37a79a98cbb',
            'fraction' => 100,
            'is_correct' => 0,
            'format' => 'html',
            'text' => $faker->text,
            'moodle_id' => 2,
        ]);

        Answer::create([
            'question_id' => '91b0106b-4280-4162-9797-d1b429236c7c',
            'user_id' => '91b35879-c5b1-4876-a50f-e37a79a98cbb',
            'fraction' => 100,
            'is_correct' => 1,
            'format' => 'html',
            'text' => $faker->text,
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
