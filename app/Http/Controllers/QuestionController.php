<?php

namespace App\Http\Controllers;

use App\Http\Resources\Question as QuestionResource;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index()
    {
        return QuestionResource::collection(Question::all());
    }

    public function show($id)
    {
        return new QuestionResource(Question::find($id));
    }

    public function store(Request $request)
    {
        return Question::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $question = Question::findOrFail($id);
        $question->update($request->all());

        return $question;
    }

    public function delete(Request $request, $id)
    {
        $question = Question::findOrFail($id);
        $question->delete();

        return 204;
    }
}
