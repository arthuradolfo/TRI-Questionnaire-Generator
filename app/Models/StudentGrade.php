<?php

namespace App\Models;

use App\Traits\StudentId;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentGrade extends Model
{
    use HasFactory, Uuid, StudentId;

    public $incrementing = false;

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function student()
    {
        return $this->belongsTo('App\Models\Student');
    }

    public function question()
    {
        return $this->belongsTo('App\Models\Question');
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
        'question_id',
        'grade',
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
