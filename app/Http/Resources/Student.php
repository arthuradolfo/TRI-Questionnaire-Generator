<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\StudentGrade as StudentGradeResource;
use App\Models\StudentGrade;

class Student extends JsonResource
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
            'username' => $this->username,
            'email' => $this->email,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'idnumber' => $this->idnumber,
            'institution' => $this->institution,
            'department' => $this->department,
            'phone1' => $this->phone1,
            'phone2' => $this->phone2,
            'city' => $this->city,
            'url' => $this->url,
            'icq' => $this->icq,
            'skype' => $this->skype,
            'aim' => $this->aim,
            'yahoo' => $this->yahoo,
            'msn' => $this->msn,
            'country' => $this->country,
            'ability' => $this->ability,
            'grades' => StudentGradeResource::collection(StudentGrade::where('student_id', $this->id)->get()),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
