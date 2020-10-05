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

        $faker = Factory::create();

        Category::create([
            'id' => '91b0106b-4280-4162-9797-d1b423236c7c',
            'name' => $faker->name,
            'info' => $faker->text,
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
