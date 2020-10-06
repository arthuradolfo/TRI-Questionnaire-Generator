<?php

namespace App\Http\Controllers;

use App\Http\Resources\Category as CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        return CategoryResource::collection(Category::where('user_id', $request->user()->id)->get());
    }

    public function show(Request $request, $id)
    {
        $category = Category::where([['id', $id], ['user_id', $request->user()->id]])->firstOrFail();
        return new CategoryResource($category);
    }

    public function store(Request $request)
    {
        $aux_request = $request->all();
        if(isset($aux_request['name'])) {
            $aux_request['user_id'] = $request->user()->id;
            return Category::create($aux_request);
        }
        else {
            $categories = array();
            foreach ($aux_request as $one_request) {
                $one_request['user_id'] = $request->user()->id;
                $categories[] = Category::create($one_request);
            }
            return $categories;
        }
    }

    public function update(Request $request, $id)
    {
        $category = Category::where([['id', $id], ['user_id', $request->user()->id]])->firstOrFail();
        $category->update($request->all());

        return $category;
    }

    public function delete(Request $request, $id)
    {
        $category = Category::where([['id', $id], ['user_id', $request->user()->id]])->firstOrFail();
        $category->delete();

        return 204;
    }
}
