<?php

namespace App\Http\Controllers;

use App\Http\Resources\Answer as AnswerResource;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AnswerController extends Controller
{
    /**
     * @queryParam moodle_id int
     */
    public function index(Request $request)
    {
        if($request->input('moodle_id'))
        {
            return new AnswerResource(Answer::where([
                ['moodle_id', $request->input('moodle_id')],
                ['user_id', $request->user()->id]
            ])->firstOrFail());
        }
        else {
            return AnswerResource::collection(Answer::where('user_id', $request->user()->id)->get());
        }
    }

    public function show(Request $request, $id)
    {
        $answer = Answer::where([['id', $id], ['user_id', $request->user()->id]])->firstOrFail();
        return new AnswerResource($answer);
    }

    /**
     * @bodyParam Answer object
     */
    public function store(Request $request)
    {
        $aux_request = $request->all();
        if(isset($aux_request['text'])) {
            $aux_request['user_id'] = $request->user()->id;
            if(!is_null($aux_request['moodle_id']) && Answer::where([
                ['moodle_id', $aux_request['moodle_id']],
                ['user_id', $aux_request['user_id']]
            ])->first()) {
                $answer = Answer::where([
                    ['moodle_id', $aux_request['moodle_id']],
                    ['user_id', $aux_request['user_id']]
                ])->first();
                $answer->update($aux_request);
                return $answer;
            }
            if(isset($aux_request['question_moodle_id'])) {
                $question = Question::where([['moodle_id', $aux_request['question_moodle_id']], ['user_id', $request->user()->id]])->firstOrFail();
                $aux_request['question_id'] = $question->id;
            }
            return Answer::create($aux_request);
        }
        else {
            $answers = array();
            foreach ($aux_request as $one_request) {
                $one_request['user_id'] = $request->user()->id;
                if(!is_null($one_request['moodle_id']) && Answer::where([
                    ['moodle_id', $one_request['moodle_id']],
                    ['user_id', $one_request['user_id']]
                ])->first()) {
                    $answer = Answer::where([
                        ['moodle_id', $one_request['moodle_id']],
                        ['user_id', $one_request['user_id']]
                    ])->first();
                    $answer->update($one_request);
                    $answers[] = $answer;
                }
                else {
                    if (isset($one_request['question_moodle_id'])) {
                        $question = Question::where([['moodle_id', $one_request['question_moodle_id']], ['user_id', $request->user()->id]])->firstOrFail();
                        $one_request['question_id'] = $question->id;
                    }
                    $answers[] = Answer::create($one_request);
                }
            }
            return $answers;
        }
    }

    /**
     * @bodyParam Answer object
     */
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

        return response('', 204);
    }
}
