<?php

namespace App\Http\Resources;

use App\Http\Resources\Question as QuestionResource;
use App\Models\Question as Question;
use Illuminate\Http\Resources\Json\JsonResource;

class Category extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'moodle_id' => $this->moodle_id,
            'name' => $this->name,
            'info' => $this->info,
            'info_format' => $this->info_format,
            'idnumer' => $this->idnumber,
            'questions' => QuestionResource::collection(Question::where('category_id', $this->id)->get()),
            'categories' => Category::collection(Category::where('category_id', $this->id)->get()),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
