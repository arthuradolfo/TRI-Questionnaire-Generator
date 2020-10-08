<?php

namespace App\Traits;

use App\Models\Question;

trait QuestionId
{
    public static function bootQuestionId()
    {
        static::creating(function ($model) {
            $question = Question::findOrFail($model->question_id);
            if($question->user_id != $model->user_id)
            {
                abort(401,"Question ID does not exist for this user.");
            }
        });
    }
}
