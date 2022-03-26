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
            }
        });
    }
}
