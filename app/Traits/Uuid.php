<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait Uuid
{
    public static function bootUuid()
    {
        static::creating(function ($model) {
            if(is_null($model->id))
            {
                $model->id = Str::orderedUuid();
            }
        });
    }
}
