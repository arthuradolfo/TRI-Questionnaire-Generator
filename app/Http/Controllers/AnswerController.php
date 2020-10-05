<?php

namespace App\Http\Controllers;

use App\Http\Resources\Answer as AnswerResource;
use App\Models\Answer;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    public function index()
    {
        return AnswerResource::collection(Answer::all());
    }

    public function show($id)
    {
        return new AnswerResource(Answer::find($id));
    }

    public function store(Request $request)
    {
        return Answer::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $answer = Answer::findOrFail($id);
        $answer->update($request->all());

        return $answer;
    }

    public function delete(Request $request, $id)
    {
        $answer = Answer::findOrFail($id);
        $answer->delete();

        return 204;
    }
}
