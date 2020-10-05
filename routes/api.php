<?php

use App\Http\Resources\Category as CategoryResource;
use App\Http\Resources\Question as QuestionResource;

use App\Models\Category;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('answers', 'App\Http\Controllers\AnswerController@index');
Route::get('answers/{id}', 'App\Http\Controllers\AnswerController@show');
Route::post('answers', 'App\Http\Controllers\AnswerController@store');
Route::put('answers/{id}', 'App\Http\Controllers\AnswerController@update');
Route::delete('answers/{id}', 'App\Http\Controllers\AnswerController@delete');

Route::get('questions', 'App\Http\Controllers\QuestionController@index');
Route::get('questions/{id}', 'App\Http\Controllers\QuestionController@show');
Route::post('questions', 'App\Http\Controllers\QuestionController@store');
Route::put('questions/{id}', 'App\Http\Controllers\QuestionController@update');
Route::delete('questions/{id}', 'App\Http\Controllers\QuestionController@delete');

Route::get('categories', function() {
    // If the Content-Type and Accept headers are set to 'application/json',
    // this will return a JSON structure. This will be cleaned up later.
    return CategoryResource::collection(Category::all());
});

Route::get('categories/{id}', function($id) {
    return new CategoryResource(Category::find($id));
});

Route::post('categories', function(Request $request) {
    return Category::create($request->all);
});

Route::put('categories/{id}', function(Request $request, $id) {
    $category = Category::findOrFail($id);
    $category->update($request->all());

    return $category;
});

Route::delete('categories/{id}', function($id) {
    Category::find($id)->delete();

    return 204;
});
