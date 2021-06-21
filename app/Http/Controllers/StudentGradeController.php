<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Session;
use App\Models\Student;
use App\Models\StudentGrade;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\StudentGrade as StudentGradeResource;
use Symfony\Component\HttpKernel\Exception\HttpException;

class StudentGradeController extends Controller
{
    public function index(Request $request)
    {
        return StudentGradeResource::collection(StudentGrade::where('user_id', $request->user()->id)->get());
    }

    public function show(Request $request, $id)
    {
        $question = StudentGrade::where([
            ['id', $id],
            ['user_id', $request->user()->id]
        ])->firstOrFail();
        return new StudentGradeResource($question);
    }

    /**
     * @bodyParam StudentGrade object
     */
    public function store(Request $request)
    {
        $user = User::where('id', $request->user()->id)->first();
        $aux_request = $request->all();
        if(isset($aux_request['grade'])) {
            $aux_request['user_id'] = $request->user()->id;
            if(isset($aux_request['student_moodle_id'])) {
                $student = Student::where([['moodle_id', $aux_request['student_moodle_id']], ['user_id', $request->user()->id]])->first();
                if($student !== null)
                {
                    $aux_request['student_id'] = $student->id;
                }
            }
            if(isset($aux_request['question_moodle_id'])) {
                $question = Question::where([['moodle_id', $aux_request['question_moodle_id']], ['user_id', $request->user()->id]])->firstOrFail();
                $aux_request['question_id'] = $question->id;
            }
            if(!is_null($aux_request['student_id']) && $student_grade = StudentGrade::where([
                    ['user_id', $aux_request['user_id']],
                    ['student_id', $aux_request['student_id']],
                    ['question_id', $aux_request['question_id']]
                ])->first()) {
                $student_grade->grade = ($aux_request['grade'] > $user->threshold) ? 1 : 0;
                $student_grade->update();
                return $student_grade;
            }
            $aux_request['grade'] = ($aux_request['grade'] > $user->threshold) ? 1 : 0;
            return StudentGrade::create($aux_request);
        }
        else {
            $student_grades = array();
            foreach ($aux_request as $one_request) {
                $one_request['user_id'] = $request->user()->id;
                if(isset($one_request['student_moodle_id'])) {
                    $student = Student::where([['moodle_id', $one_request['student_moodle_id']], ['user_id', $request->user()->id]])->first();
                    if($student !== null)
                    {
                        $one_request['student_id'] = $student->id;
                    }
                }
                if(isset($one_request['question_moodle_id'])) {
                    $question = Question::where([['moodle_id', $one_request['question_moodle_id']], ['user_id', $request->user()->id]])->firstOrFail();
                    $one_request['question_id'] = $question->id;
                }
                if(!is_null($one_request['student_id']) && $student_grade = StudentGrade::where([
                        ['user_id', $one_request['user_id']],
                        ['student_id', $one_request['student_id']],
                        ['question_id', $one_request['question_id']]
                    ])->first()) {
                    $student_grade->grade = ($one_request['grade'] > $user->threshold) ? 1 : 0;
                    $student_grade->update();
                    $student_grades[] = $student_grade;
                    continue;
                }
                $one_request['grade'] = ($one_request['grade'] > $user->threshold) ? 1 : 0;
                $student_grades[] = StudentGrade::create($one_request);
            }
            return $student_grades;
        }
    }

    /**
     * @bodyParam Student object
     */
    public function update(Request $request, $id)
    {
        $student_grade = StudentGrade::where([['id', $id], ['user_id', $request->user()->id]])->firstOrFail();
        $student_grade->update($request->all());

        return $student_grade;
    }

    public function delete(Request $request, $id)
    {
        $student_grade = StudentGrade::where([['id', $id], ['user_id', $request->user()->id]])->firstOrFail();
        $student_grade->delete();

        return response(json_encode(['message' => 'Deleted.']), 204);
    }

    public function calculate_model(Request $request, $id)
    {
        $session = Session::where('id', $id)->firstOrFail();
        echo shell_exec("Rscript ../R/model_irt.R ".$request->user()->id." ".$session->category_id);

        return response(json_encode(['message' => 'Calculated.']), 200);
    }
}
