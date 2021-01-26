<?php

namespace App\Models;

use App\Traits\CategoryId;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory, Uuid, CategoryId;

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function answers()
    {
        return $this->hasMany('App\Models\Answer');
    }

    public function studentGrades()
    {
        return $this->hasMany('App\Models\StudentGrade');
    }

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'moodle_id',
        'category_id',
        'user_id',
        'type',
        'name',
        'questiontext',
        'questiontext_format',
        'generalfeedback',
        'generalfeedback_format',
        'defaultgrade',
        'penalty',
        'hidden',
        'idnumber',
        'single',
        'ability',
        'discrimination',
        'guess',
        'shuffleanswers',
        'answernumbering',
        'showstandardinstruction',
        'correctfeedback',
        'correctfeedback_format',
        'partiallycorrectfeedback',
        'partiallycorrectfeedback_format',
        'incorrectfeedback',
        'incorrectfeedback_format'
    ];

    /**
     * The attributes that should have deafault values.
     *
     * @var array
     */
    protected $attributes = [
        'moodle_id' => NULL,
        'questiontext' => '',
        'questiontext_format' => '',
        'generalfeedback' => '',
        'generalfeedback_format' => '',
        'defaultgrade' => 1.00000,
        'penalty' => 0.33333,
        'hidden' => 0,
        'idnumber' => NULL,
        'single' => false,
        'shuffleanswers' => false,
        'answernumbering' => '',
        'showstandardinstruction' => 0,
        'correctfeedback' => 'Your answer is correct.',
        'correctfeedback_format' => 'html',
        'partiallycorrectfeedback' => 'Your answer is partially correct.',
        'partiallycorrectfeedback_format' => 'html',
        'incorrectfeedback' => 'Your answer is incorrect.',
        'incorrectfeedback_format' => 'html'
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
