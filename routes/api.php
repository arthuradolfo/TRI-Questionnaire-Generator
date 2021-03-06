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
    Route::post('/login', 'Auth\ApiAuthController@login')->name('login.api');
    Route::post('/register','Auth\ApiAuthController@register')->name('register.api');
    Route::post('/forgot', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('forgot.api');
    Route::post('/reset', 'Auth\ResetPasswordController@reset')->name('reset.api');
});

Route::middleware('auth:api')->group(function() {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/password', 'Auth\ApiAuthController@update')->name('update.api');

    Route::post('/logout', 'Auth\ApiAuthController@logout')->name('logout.api');

    Route::get('answers', 'AnswerController@index');
    Route::get('answers/{id}', 'AnswerController@show');
    Route::post('answers', 'AnswerController@store');
    Route::put('answers/{id}', 'AnswerController@update');
    Route::delete('answers/{id}', 'AnswerController@delete');

    Route::get('questions', 'QuestionController@index');
    Route::get('questions/{id}', 'QuestionController@show');
    Route::post('questions', 'QuestionController@store');
    Route::put('questions/{id}', 'QuestionController@update');
    Route::delete('questions/{id}', 'QuestionController@delete');

    Route::get('categories', 'CategoryController@index');
    Route::get('categories/{id}', 'CategoryController@show');
    Route::post('categories', 'CategoryController@store');
    Route::put('categories/{id}', 'CategoryController@update');
    Route::delete('categories/{id}', 'CategoryController@delete');
});
