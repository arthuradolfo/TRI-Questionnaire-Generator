<?php

namespace App\Models;

use App\Traits\StudentId;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAbilityLog extends Model
{
    use HasFactory, StudentId;

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function student()
    {
        return $this->belongsTo('App\Models\Student');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'student_id',
        'ability',
        'time',
    ];
}
