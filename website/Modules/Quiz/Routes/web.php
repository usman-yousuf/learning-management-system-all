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

use Illuminate\Support\Facades\Route;
use Modules\Quiz\Http\Controllers\QuizController;

Route::prefix('quiz')->group(function() {
    Route::get('/', 'QuizController@index');
});


Route::group(['middleware' => 'auth'], function () {
    // cms Routes
    Route::group(['as' => 'quiz.'], function () {
        Route::any('all-quizez', [QuizController::class, 'index'])->name('index');
        Route::any('update-quizez', [QuizController::class, 'updateQuizzes'])->name('update');
        Route::get('test-question/{uuid}', [QuizController::class, 'testQuestion'])->name('testQuestion');
        Route::post('add-test-question/{uuid}', [QuizController::class, 'addTestQuestion'])->name('addTestQuestion');
        Route::post('delete-test-question', [QuizController::class, 'deleteTestQuestion'])->name('delete-test-question');
        Route::post('add-true-false/{uuid}', [QuizController::class, 'addBooleanQuestion'])->name('boolean-question');
        Route::post('multiple-choice/{uuid}', [QuizController::class, 'addMutlipleChoiceQuestion'])->name('multiple-choice');
        
        Route::any('load-student-answers', [QuizController::class, 'loadStudentAnswers'])->name('load-student-answers');
        Route::any('mark-student-answers', [QuizController::class, 'markStudentAnswers'])->name('mark-student-answers');
        // quiz.mark-student-answers
        Route::post('delete-test-quiz', [QuizController::class, 'deleteTestQuiz'])->name('delete-test-quiz');
    });
});
