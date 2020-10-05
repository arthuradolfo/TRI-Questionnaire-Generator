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


Route::group(['middleware' => ['cors', 'json.response']], function () {
    // public routes
    Route::post('/login', 'App\Http\Controllers\Auth\ApiAuthController@login')->name('login.api');
    Route::post('/register','App\Http\Controllers\Auth\ApiAuthController@register')->name('register.api');
});

Route::middleware('auth:api')->group(function() {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', 'App\Http\Controllers\Auth\ApiAuthController@logout')->name('logout.api');

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

    Route::get('categories', 'App\Http\Controllers\CategoryController@index');
    Route::get('categories/{id}', 'App\Http\Controllers\CategoryController@show');
    Route::post('categories', 'App\Http\Controllers\CategoryController@store');
    Route::put('categories/{id}', 'App\Http\Controllers\CategoryController@update');
    Route::delete('categories/{id}', 'App\Http\Controllers\CategoryController@delete');
});
