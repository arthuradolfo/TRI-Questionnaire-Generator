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

    Route::get('student_grades_output', 'StudentGradeController@output');

    Route::get('sessions/{id}/get_next_question', 'SessionController@get_next_question');

    Route::get('sessions', 'SessionController@index');
    Route::get('sessions/{id}', 'SessionController@show');
    Route::post('sessions', 'SessionController@store');
    Route::put('sessions/{id}', 'SessionController@update');
    Route::delete('sessions/{id}', 'SessionController@delete');

    Route::get('calculate_model/{id}', 'StudentGradeController@calculate_model');

    Route::get('student_grades', 'StudentGradeController@index');
    Route::get('student_grades/{id}', 'StudentGradeController@show');
    Route::post('student_grades', 'StudentGradeController@store');
    Route::put('student_grades/{id}', 'StudentGradeController@update');
    Route::delete('student_grades/{id}', 'StudentGradeController@delete');

    Route::get('students', 'StudentController@index');
    Route::get('students/{id}', 'StudentController@show');
    Route::post('students', 'StudentController@store');
    Route::put('students/{id}', 'StudentController@update');
    Route::delete('students/{id}', 'StudentController@delete');

    Route::get('answers', 'AnswerController@index');
    Route::get('answers/{id}', 'AnswerController@show');
    Route::post('answers', 'AnswerController@store');
    Route::put('answers/{id}', 'AnswerController@update');
    Route::delete('answers/{id}', 'AnswerController@delete');

    Route::get('questions', 'QuestionController@index');
    Route::get('questions/{id}', 'QuestionController@show');
    Route::post('questions', 'QuestionController@store');
    Route::put('questions', 'QuestionController@updateBatch');
    Route::put('questions/{id}', 'QuestionController@update');
    Route::delete('questions/{id}', 'QuestionController@delete');

    Route::get('categories', 'CategoryController@index');
    Route::get('categories/{id}', 'CategoryController@show');
    Route::post('categories', 'CategoryController@store');
    Route::put('categories/{id}', 'CategoryController@update');
    Route::delete('categories/{id}', 'CategoryController@delete');
});
