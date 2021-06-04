<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Modules\Quiz\Http\Controllers\QuizController;

Route::prefix('quiz')->group(function() {
    Route::get('/', 'QuizController@index');
});


Route::group(['middleware' => 'auth'], function () {
    // cms Routes
    Route::group(['as' => 'quiz.'], function () {
        Route::any('all-quizez', [QuizController::class, 'index'])->name('index');
        // Route::any('privacy-policy', [CommonController::class, 'privacyPolicy'])->name('privacy-policy');
    });
});