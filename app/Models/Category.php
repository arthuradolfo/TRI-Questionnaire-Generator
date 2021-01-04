<?php

namespace App\Models;

use App\Traits\CategoryId;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, Uuid, CategoryId;

    public $incrementing = false;

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function category()
    {
        return $this->hasOne('App\Models\Category');
    }

    public function questions()
    {
        return $this->hasMany('App\Models\Question');
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
        'name',
        'info',
        'info_format',
        'category_id',
    ];

    /**
     * The attributes that should have deafault values.
     *
     * @var array
     */
    protected $attributes = [
        'moodle_id' => NULL,
        'info' => '',
        'info_format' => 'html',
        'category_id' => NULL,
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
