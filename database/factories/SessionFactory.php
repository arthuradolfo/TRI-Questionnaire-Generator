<?php

namespace Database\Factories;

use App\Models\Session;
use Illuminate\Database\Eloquent\Factories\Factory;

class SessionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Session::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'tqg_id' => 1,
            'number_questions' => 10,
            'stat' => Session::STARTED,
            'questions' => "",
            'time_started' => date("Y-m-d H:m:s", time())
        ];
    }
}
