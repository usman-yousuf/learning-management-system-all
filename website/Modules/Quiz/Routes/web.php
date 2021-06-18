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
        Route::group(['middleware' => ['isTeacher','isTeacherVerified']], function () {
            Route::any('get-student-quiz-result', [QuizController::class, 'getStudentQuizResult'])->name('get-student-result');

            Route::any('update-quizez', [QuizController::class, 'updateQuizzes'])->name('update');

            Route::post('add-test-question/{uuid}', [QuizController::class, 'addTestQuestion'])->name('addTestQuestion');
            Route::post('delete-test-question', [QuizController::class, 'deleteTestQuestion'])->name('delete-test-question');
            Route::post('add-true-false/{uuid}', [QuizController::class, 'addBooleanQuestion'])->name('boolean-question');
            Route::post('multiple-choice/{uuid}', [QuizController::class, 'addMutlipleChoiceQuestion'])->name('multiple-choice');

            Route::any('load-student-answers', [QuizController::class, 'loadStudentAnswers'])->name('load-student-answers');
            Route::any('mark-student-answers', [QuizController::class, 'markStudentAnswers'])->name('mark-student-answers');
            // quiz.mark-student-answers
            Route::post('delete-quiz-question', [QuizController::class, 'deleteQuizQuestion'])->name('delete-quiz-question');
        });
        Route::any('all-quizez', [QuizController::class, 'index'])->name('index');
        Route::get('view-quiz/{uuid}', [QuizController::class, 'viewQuiz'])->name('viewQuiz');
    });
});
