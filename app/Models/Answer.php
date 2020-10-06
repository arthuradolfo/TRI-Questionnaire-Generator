<?php

namespace App\Models;

use App\Traits\QuestionId;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory, Uuid, QuestionId;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'question_id',
        'user_id',
        'fraction',
        'format',
        'text',
        'feedback',
        'feedback_format'
    ];

    /**
     * The attributes that should have deafault values.
     *
     * @var array
     */
    protected $attributes = [
        'format' => 'html',
        'text' => '',
        'feedback' => '',
        'feedback_format' => 'html'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
    ];

}
