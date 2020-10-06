<?php

namespace Database\Seeders;

use App\Models\Question;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class QuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = Factory::create();

        Question::create([
            'id' => '91b0106b-4280-4162-9797-d1b429236c7c',
            'category_id' => '91b0106b-4280-4162-9797-d1b423236c7c',
            'user_id' => '91b35879-c5b1-4876-a50f-e37a79a98cbb',
            'type' => $faker->name,
            'name' => $faker->name,
            'questiontext' => $faker->text,
            'questiontext_format' => 'html',
        ]);

        // And now let's generate a few dozen users for our app:
        /*for ($i = 0; $i < 10; $i++) {
            Question::create([
                'category_id' => Str::orderedUuid(),
                'type' => $faker->name,
                'name' => $faker->name,
                'questiontext' => $faker->text,
                'questiontext_format' => 'html',
            ]);
        }*/
    }
}
