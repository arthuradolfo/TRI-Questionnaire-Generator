<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Student;
use App\Models\StudentGrade;
use Illuminate\Database\Seeder;

class StudentGradesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StudentGrade::query()->delete();

        /*StudentGrade::create([
            'id' => '9cb35879-c5b1-4876-a50f-e37a79a98cbb',
            'user_id' => '91b35879-c5b1-4876-a50f-e37a79a98cbb',
            'student_id' => '91c35879-c9b1-4876-a50f-e37a79a96cbb',
            'question_id' => '91b0106b-4280-4162-9797-d1b429236c7c',
            'grade' => 5
        ]);*/

        $questions = Question::where('user_id', '91ec28e5-ab15-4bc3-8e10-56bc2f7896d9')->get();
        $students = Student::where('user_id', '91ec28e5-ab15-4bc3-8e10-56bc2f7896d9')->get();
        foreach ($students as $student)
        {
            $i = 0;
            foreach ($questions as $question) {
                $grade = rand(0, 1);
                if ($i < 30)
                {
                    $grade = (rand(1, 1000) <= 100) ? 0 : 1;
                }
                if ($i >= 30 && $i < 60)
                {
                    $grade = (rand(1, 1000) <= 200) ? 0 : 1;
                }
                if ($i >= 60)
                {
                    $grade = (rand(1, 1000) <= 300) ? 0 : 1;
                }
                StudentGrade::create([
                    'user_id' => '91ec28e5-ab15-4bc3-8e10-56bc2f7896d9',
                    'student_id' => $student->id,
                    'question_id' => $question->id,
                    'grade' => $grade
                ]);
                $i++;
            }
        }

    }
}
