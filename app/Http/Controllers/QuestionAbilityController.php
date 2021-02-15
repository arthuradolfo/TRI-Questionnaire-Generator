<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuestionAbilityLog as QuestionAbilityLogResource;
use App\Models\Question;
use App\Models\QuestionAbilityLog;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class QuestionAbilityController extends Controller
{
    public function index(Request $request)
    {
        return QuestionAbilityLogResource::collection(QuestionAbilityLog::where('user_id', $request->user()->id)->get());
    }

    public function show(Request $request, $id)
    {
        return QuestionAbilityLogResource::collection(QuestionAbilityLog::where([
            ['question_id', $id],
            ['user_id', $request->user()->id]
        ])->get());
    }

    public function store(Request $request)
    {
        $aux_request = $request->all();
        if(isset($aux_request['ability'])) {
            $aux_request['user_id'] = $request->user()->id;
            if(isset($aux_request['question_moodle_id'])) {
                $question = Question::where([['moodle_id', $aux_request['question_moodle_id']], ['user_id', $request->user()->id]])->firstOrFail();
                $aux_request['question_id'] = $question->id;
            }
            return QuestionAbilityLog::create($aux_request);
        }
        else {
            $question_abilities = array();
            foreach ($aux_request as $one_request) {
                $one_request['user_id'] = $request->user()->id;
                if(isset($one_request['question_moodle_id'])) {
                    $question = Question::where([['moodle_id', $one_request['question_moodle_id']], ['user_id', $request->user()->id]])->firstOrFail();
                    $one_request['question_id'] = $question->id;
                }
                $question_abilities[] = QuestionAbilityLog::create($one_request);
            }
            return $question_abilities;
        }
    }
}
