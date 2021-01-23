<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Student extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function studentGrades()
    {
        return $this->hasMany('App\Models\StudentGrade');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'moodle_id',
        'user_id',
        'username',
        'email',
        'firstname',
        'lastname',
        'idnumber',
        'institution',
        'department',
        'phone1',
        'phone2',
        'city',
        'url',
        'icq',
        'skype',
        'aim',
        'yahoo',
        'msn',
        'country',
    ];

    /**
     * The attributes that should have deafault values.
     *
     * @var array
     */
    protected $attributes = [
        'moodle_id' => NULL,
        'idnumber' => NULL,
        'institution' => '',
        'department' => '',
        'phone1' => '',
        'phone2' => '',
        'city' => '',
        'url' => '',
        'icq' => '',
        'skype' => '',
        'aim' => '',
        'yahoo' => '',
        'msn' => '',
        'country' => '',
        'ability' => 0
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
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
