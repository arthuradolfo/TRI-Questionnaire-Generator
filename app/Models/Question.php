<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory, Uuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'category_id',
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