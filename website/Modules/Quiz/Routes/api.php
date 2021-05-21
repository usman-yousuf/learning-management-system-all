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

Route::middleware('auth:api')->get('/quiz', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:api'], function () {

    #region - Quiz - START
        Route::post('get_quiz', [QuizController::class, 'getQuiz']);
        Route::post('delete_quiz', [QuizController::class, 'deleteQuiz']);
        Route::post('get_quizzes', [QuizController::class, 'getQuizzes']);
        Route::post('update_quiz', [QuizController::class, 'addUpdateQuiz']);
    #endregion - Quiz - START

    #region - Quiz Choice- START
        Route::post('get_quiz_choice', [QuizChoiceController::class, 'getQuizChoice']);
        Route::post('delete_quiz_choice', [QuizChoiceController::class, 'deleteQuizChoice']);
        Route::post('get_quizze_choices', [QuizChoiceController::class, 'getQuizChoices']);
        Route::post('update_quiz_choice', [QuizChoiceController::class, 'addUpdateQuizChoice']);
    #endregion - Quiz Choice - START

}); 