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

        $faker = Factory::create();

        Answer::create([
            'question_id' => '91b0106b-4280-4162-9797-d1b429236c7c',
            'fraction' => 100,
            'format' => 'html',
            'text' => $faker->text,
        ]);

        Answer::create([
            'question_id' => '91b0106b-4280-4162-9797-d1b429236c7c',
            'fraction' => 100,
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
