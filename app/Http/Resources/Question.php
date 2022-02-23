<?php

namespace App\Http\Resources;

use App\Http\Resources\Category as CategoryResource;
use App\Models\Category as Category;
use App\Http\Resources\Answer as AnswerResource;
use App\Models\Answer as Answer;
use Illuminate\Http\Resources\Json\JsonResource;

class Question extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'moodle_id' => $this->moodle_id,
            'category_id' => $this->category_id,
            'type' => $this->type,
            'name' => $this->name,
            'questiontext' => $this->questiontext,
            'questiontext_format' => $this->questiontext_format,
            'generalfeedback' => $this->generalfeedback,
            'generalfeedback_format' => $this->generalfeedback_format,
            'defaultgrade' => $this->defaultgrade,
            'penalty' => $this->penalty,
            'hidden' => $this->hidden,
            'idnumber' => $this->idnumber,
            'single' => $this->single,
            'shuffleanswers' => $this->shuffleanswers,
            'answernumbering' => $this->answernumbering,
            'showstandardinstruction' => $this->showstandardinstruction,
            'correctfeedback' => $this->correctfeedback,
            'correctfeedback_format' => $this->correctfeedback_format,
            'partiallycorrectfeedback' => $this->partiallycorrectfeedback,
            'partiallycorrectfeedback_format' => $this->partiallycorrectfeedback_format,
            'incorrectfeedback' => $this->incorrectfeedback,
            'incorrectfeedback_format' => $this->incorrectfeedback_format,
            'ability' => $this->ability,
            'discrimination' => $this->discrimination,
            'guess' => $this->guess,
            'answers' => AnswerResource::collection(Answer::where('question_id', $this->id)->get()),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
