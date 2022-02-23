<?php

namespace App\Traits;

use App\Models\Student;

trait StudentId
{
    public static function bootStudentId()
    {
        static::creating(function ($model) {
            if(!is_null($model->student_id))
            {
                $student = Student::findOrFail($model->student_id);

                if($student->user_id != $model->user_id)
                {
                    abort(401, "Student ID does not exist for this user.");
                }
            }
        });
    }
}
