<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Session extends JsonResource
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
            'student_id' => $this->student_id,
            'category_id' => $this->category_id,
            'tqg_id' => $this->tqg_id,
            'number_questions' => $this->number_questions,
            'status' => $this->status,
            'last_response' => $this->last_response,
            'current_question' => $this->current_question,
            'questions' => $this->questions,
            'questions_usage' => $this->questions_usage,
            'slot' => $this->slot,
            'time_started' => $this->time_started,
            'time_finished' => $this->time_finished,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
