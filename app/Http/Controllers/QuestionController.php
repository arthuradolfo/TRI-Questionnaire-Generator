<?php

namespace App\Http\Controllers;

use App\Http\Resources\Question as QuestionResource;
use App\Models\Question;
use App\Models\Category;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        if($request->input('moodle_id'))
        {
            return new QuestionResource(Question::where([
                ['moodle_id', $request->input('moodle_id')],
                ['user_id', $request->user()->id]
            ])->firstOrFail());
        }
        else {
            return QuestionResource::collection(Question::where('user_id', $request->user()->id)->get());
        }
    }

    public function show(Request $request, $id)
    {
        $question = Question::where([
            ['id', $id],
            ['user_id', $request->user()->id]
        ])->firstOrFail();
        return new QuestionResource($question);
    }

    public function store(Request $request)
    {
        $aux_request = $request->all();
        if(isset($aux_request['name'])) {
            $aux_request['user_id'] = $request->user()->id;
            if(!is_null($aux_request['moodle_id']) && Question::where([
                ['moodle_id', $aux_request['moodle_id']],
                ['user_id', $aux_request['user_id']]
            ])->first()) {
                throw new HttpException(401, "Already exists.");
            }
            if(isset($aux_request['category_moodle_id'])) {
                $category = Category::where([['moodle_id', $aux_request['category_moodle_id']], ['user_id', $request->user()->id]])->firstOrFail();
                $aux_request['category_id'] = $category->id;
            }
            return Question::create($aux_request);
        }
        else {
            $questions = array();
            foreach ($aux_request as $one_request) {
                $one_request['user_id'] = $request->user()->id;
                if(!is_null($one_request['moodle_id']) && Question::where([
                    ['moodle_id', $one_request['moodle_id']],
                    ['user_id', $one_request['user_id']]
                ])->first()) {
                    throw new HttpException(401, "Already exists.");
                }
                if(isset($one_request['category_moodle_id'])) {
                    $category = Category::where([['moodle_id', $one_request['category_moodle_id']], ['user_id', $request->user()->id]])->firstOrFail();
                    $one_request['category_id'] = $category->id;
                }
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

        return response(json_encode(['message' => 'Deleted.']), 204);
    }
}
