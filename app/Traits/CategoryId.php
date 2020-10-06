<?php

namespace App\Traits;

use App\Models\Category;

trait CategoryId
{
    public static function bootCategoryId()
    {
        static::creating(function ($model) {
            $category = Category::findOrFail($model->category_id);
            if($category->user_id != $model->user_id) abort(401, "Category ID does not exist for this user.");
        });
    }
}
