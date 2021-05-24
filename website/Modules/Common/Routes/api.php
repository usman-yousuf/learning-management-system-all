<?php

use Illuminate\Http\Request;
use Modules\Common\Http\Controllers\API\ReportController;

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

Route::middleware('auth:api')->get('/common', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:api'], function () {
    #region - Student Course Report - START
        Route::post('get_student_course_report', [ReportController::class, 'getStudentCourseReport']);
    #endregion - Student Course Report - START

    #region - Student Course Report - START
        Route::post('get_student_course_report', [ReportController::class, 'getStudentCourseReport']);
    #endregion - Student Course Report - START

}); 