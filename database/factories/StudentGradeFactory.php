<?php

namespace Database\Factories;

use App\Models\StudentGrade;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentGradeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StudentGrade::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'student_id' => '91b99879-c5b1-4876-a50f-e37a79a98cbb',
            'question_id' => '91b0106b-4280-4162-9797-d1b429236c7c',
            'grade' => $this->faker->randomFloat(1, 0, 10),
        ];
    }
}
