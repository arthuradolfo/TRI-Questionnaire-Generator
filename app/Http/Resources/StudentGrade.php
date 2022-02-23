<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentGrade extends JsonResource
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
            'question_id' => $this->question_id,
            'grade' => $this->grade,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
