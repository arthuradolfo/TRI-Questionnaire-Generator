<?php

namespace App\Http\Controllers;

use App\Http\Resources\Question as QuestionResource;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        return QuestionResource::collection(Question::where('user_id', $request->user()->id)->get());
    }

    public function show(Request $request, $id)
    {
        $question = Question::where([['id', $id], ['user_id', $request->user()->id]])->firstOrFail();
        return new QuestionResource($question);
    }

    public function store(Request $request)
    {
        $aux_request = $request->all();
        if(isset($aux_request['category_id'])) {
            $aux_request['user_id'] = $request->user()->id;
            return Question::create($aux_request);
        }
        else {
            $questions = array();
            foreach ($aux_request as $one_request) {
                $one_request['user_id'] = $request->user()->id;
                $questions[] = Question::create($one_request);
            }
            return $questions;
        }
    }

    public function update(Request $request, $id)
    {
        $question = Question::where([['id', $id], ['user_id', $request->user()->id]])->firstOrFail();
        $question->update($request->all());

        return $question;
    }

    public function delete(Request $request, $id)
    {
        $question = Question::where([['id', $id], ['user_id', $request->user()->id]])->firstOrFail();
        $question->delete();

        return 204;
    }
}
