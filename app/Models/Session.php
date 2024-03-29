<?php

namespace App\Models;

use App\Traits\CategoryId;
use App\Traits\StudentId;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory, Uuid, CategoryId, StudentId;

    const STARTED = 1;
    const ASKED = 2;
    const ANSWERED = 3;
    const FINISHED = 4;

    public $incrementing = false;

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function student()
    {
        return $this->hasOne('App\Models\Student');
    }

    public function category()
    {
        return $this->hasOne('App\Models\Category');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',
        'student_id',
        'category_id',
        'tqg_id',
        'number_questions',
        'standard_error',
        'status',
        'current_answer_id',
        'current_question',
        'questions_usage',
        'slot',
        'time_started',
        'time_finished',
        'questions'
    ];

    /**
     * The attributes that should have default values.
     *
     * @var array
     */
    protected $attributes = [
        'status' => self::STARTED,
        'current_question' => NULL,
        'questions' => "",
        'standard_error' => 0
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
