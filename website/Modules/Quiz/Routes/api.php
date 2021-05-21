<?php

use Illuminate\Http\Request;
use Modules\Quiz\Http\Controllers\API\QuizChoiceController;
use Modules\Quiz\Http\Controllers\API\QuizController;

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
Route::group(['middleware' => 'auth:api'], function () {

    #region - Quiz - START
        Route::post('get_quizzes', [QuizController::class, 'getQuizzes']);
        Route::post('get_quiz', [QuizController::class, 'getQuiz']);
        Route::post('delete_quiz', [QuizController::class, 'deleteQuiz']);
        Route::post('update_quiz', [QuizController::class, 'addUpdateQuiz']);
    #endregion - Quiz - START

    #region - Quiz Choice- START
        Route::post('get_quizze_choices', [QuizChoiceController::class, 'getQuizChoices']);
        Route::post('get_quiz_choice', [QuizChoiceController::class, 'getQuizChoice']);
        Route::post('delete_quiz_choice', [QuizChoiceController::class, 'deleteQuizChoice']);
        Route::post('update_quiz_choice', [QuizChoiceController::class, 'addUpdateQuizChoice']);
    #endregion - Quiz Choice - START

    // update_assign_quiz_bulk_students

    #region - Quiz Choice- START
        // Route::post('assign_quiz_to_student', [QuizChoiceController::class, 'getQuizChoices']);
        // Route::post('assign_quiz_to_students', [QuizChoiceController::class, 'getQuizChoices']);
    #endregion - Quiz Choice - START

    #region - Quiz Choice- START
        // Route::post('answer_a_quiz_q', [QuizChoiceController::class, 'getQuizChoices']);
        // Route::post('edit_a_quiz_q', [QuizChoiceController::class, 'getQuizChoices']);
    #endregion - Quiz Choice - START

    #region - Quiz Choice- START
        // Route::post('mark_test_quiz_ans', [QuizChoiceController::class, 'getQuizChoices']);
    #endregion - Quiz Choice - START

});
