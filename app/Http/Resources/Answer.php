<?php

namespace App\Http\Resources;

use App\Models\Question as Question;
use App\Http\Resources\Question as QuestionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class Answer extends JsonResource
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
            'question_id' => $this->question_id,
            'fraction' => $this->fraction,
            'format' => $this->format,
            'text' => $this->text,
            'feedback' => $this->feedback,
            'feedback_format' => $this->feedback_format,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
