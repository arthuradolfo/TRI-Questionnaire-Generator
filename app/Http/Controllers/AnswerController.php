<?php

namespace App\Http\Controllers;

use App\Http\Resources\Answer as AnswerResource;
use App\Models\Answer;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    public function index(Request $request)
    {
        return AnswerResource::collection(Answer::where('user_id', $request->user()->id)->get());
    }

    public function show(Request $request, $id)
    {
        $answer = Answer::where([['id', $id], ['user_id', $request->user()->id]])->firstOrFail();
        return new AnswerResource($answer);
    }

    public function store(Request $request)
    {
        $aux_request = $request->all();
        if(isset($aux_request['question_id'])) {
            $aux_request['user_id'] = $request->user()->id;
            return Answer::create($aux_request);
        }
        else {
            $answers = array();
            foreach ($aux_request as $one_request) {
                $one_request['user_id'] = $request->user()->id;
                $answers[] = Answer::create($one_request);
            }
            return $answers;
        }
    }

    public function update(Request $request, $id)
    {
        $answer = Answer::where([['id', $id], ['user_id', $request->user()->id]])->firstOrFail();
        $answer->update($request->all());
        return $answer;
    }

    public function delete(Request $request, $id)
    {
        $answer = Answer::where([['id', $id], ['user_id', $request->user()->id]])->firstOrFail();
        $answer->delete();

        return 204;
    }
}
