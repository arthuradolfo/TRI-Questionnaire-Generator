<?php

namespace App\Http\Controllers;

use App\Http\Resources\Session as SessionResource;
use App\Http\Resources\Question as QuestionResource;
use App\Models\Category;
use App\Models\Question;
use App\Models\Session;
use App\Models\Student;
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
        $question = Question::where([['category_id', $session->category_id],['user_id', $request->user()->id]])->firstOrFail();
        return new QuestionResource($question);
    }
}
