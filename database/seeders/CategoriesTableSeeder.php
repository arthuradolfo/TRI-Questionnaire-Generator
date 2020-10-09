<?php

namespace Database\Seeders;

use App\Models\Category;
use Faker\Factory;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Category::query()->delete();

        $faker = Factory::create();

        Category::create([
            'id' => '91b0106b-4280-4162-9797-d1b423236c7c',
            'user_id' => '91b35879-c5b1-4876-a50f-e37a79a98cbb',
            'name' => $faker->name,
            'info' => $faker->text,
        ]);

        Category::create([
            'id' => '91b0106b-4280-4162-a797-d1b993236c7c',
            'user_id' => '91b35879-c5b1-4876-a50f-e37a79a98cbb',
            'name' => $faker->name,
            'info' => $faker->text,
            'category_id' => '91b0106b-4280-4162-9797-d1b423236c7c',
        ]);

        // And now let's generate a few dozen users for our app:
        /*for ($i = 0; $i < 10; $i++) {
            Category::create([
                'name' => $faker->name,
                'info' => $faker->text,
            ]);
        }*/
    }
}
