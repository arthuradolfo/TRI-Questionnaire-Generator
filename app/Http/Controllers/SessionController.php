<?php

namespace App\Http\Controllers;

use App\Http\Resources\Session as SessionResource;
use App\Http\Resources\Question as QuestionResource;
use App\Models\Category;
use App\Models\Question;
use App\Models\Session;
use App\Models\Student;
use App\Models\StudentGrade;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function index(Request $request)
    {
        return SessionResource::collection(Session::where('user_id', $request->user()->id)->get());
    }

    public function show(Request $request, $id)
    {
        $session = Session::where([
            ['id', $id],
            ['user_id', $request->user()->id]
        ])->firstOrFail();
        return new SessionResource($session);
    }

    public function store(Request $request)
    {
        $aux_request = $request->all();
        $aux_request['user_id'] = $request->user()->id;
        if(isset($aux_request['category_moodle_id'])) {
            $category = Category::where([['moodle_id', $aux_request['category_moodle_id']], ['user_id', $request->user()->id]])->firstOrFail();
            $aux_request['category_id'] = $category->id;
        }
        if(isset($aux_request['student_moodle_id'])) {
            $student = Student::where([['moodle_id', $aux_request['student_moodle_id']], ['user_id', $request->user()->id]])->firstOrFail();
            $aux_request['student_id'] = $student->id;
        }
        return Session::create($aux_request);
    }

    public function update(Request $request, $id)
    {
        $session = Session::where([['id', $id], ['user_id', $request->user()->id]])->firstOrFail();
        $session->update($request->all());
        if($session->status === Session::ANSWERED) {
            $student = Student::where('id', $session->student_id)->firstOrFail();
            $question = Question::where([['moodle_id', $session->current_question], ['user_id', $request->user()->id]])->firstOrFail();
            if ($session->last_response == 1) {
                $student->ability = $question->ability;
                $student->update();
            } else if ($student->ability > $question->ability) {
                $student->ability = $question->ability;
                $student->update();
            }
            $student_grade = StudentGrade::where([
                ['student_id', $student->id],
                ['question_id', $question->id],
                ['user_id', $request->user()->id]
            ])->first();
            if ($student_grade === null) {
                $student_grade = new StudentGrade();
                $student_grade->user_id = $request->user()->id;
                $student_grade->student_id = $student->id;
                $student_grade->question_id = $question->id;
                $student_grade->grade = $session->last_response;
                $student_grade->save();
            } else {
                $student_grade->grade = $session->last_response;
                $student_grade->update();
            }
        }

        return $session;
    }

    public function delete(Request $request, $id)
    {
        $session = Session::where([['id', $id], ['user_id', $request->user()->id]])->firstOrFail();
        $session->delete();

        return response(json_encode(['message' => 'Deleted.']), 204);
    }

    public function get_next_question(Request $request, $id)
    {
        $session = Session::where([['id', $id], ['user_id', $request->user()->id]])->firstOrFail();
        if($session->last_response == 1)
        {
            $comparison_method = '>';
            $order_by = 'ASC';
        }
        else
        {
            $comparison_method = '<';
            $order_by = 'DESC';
        }
        $student = Student::where('id', $session->student_id)->first();
        $question = Question::where(
            [
                ['category_id', $session->category_id],
                ['user_id', $request->user()->id],
                ['ability', $comparison_method, $student->ability]
            ]
        )
        ->whereNotIn('moodle_id', explode(',', $session->questions))
        ->orderBy('ability', $order_by)->first();

        if(is_null($question))
        {
            if($order_by == "ASC")
            {
                $order_by = "DESC";
            }
            else
            {
                $order_by = "ASC";
            }
            $question = Question::where(
                [
                    ['category_id', $session->category_id],
                    ['user_id', $request->user()->id]
                ]
            )
                ->whereNotIn('moodle_id', explode(',', $session->questions))
                ->orderBy('ability', $order_by)->firstOrFail();
        }
        return new QuestionResource($question);
    }
}
