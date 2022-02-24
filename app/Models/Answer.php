<?php

namespace App\Models;

use App\Traits\QuestionId;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory, Uuid, QuestionId;

    public $incrementing = false;

    public function question()
    {
        return $this->belongsTo('App\Models\Question');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'moodle_id',
        'question_id',
        'user_id',
        'fraction',
        'ability',
        'format',
        'text',
        'is_correct',
        'feedback',
        'feedback_format'
    ];

    /**
     * The attributes that should have deafault values.
     *
     * @var array
     */
    protected $attributes = [
        'moodle_id' => NULL,
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
