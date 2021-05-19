<?php

use Illuminate\Http\Request;
use Modules\Student\Http\Controllers\API\ReviewController;
use Modules\Student\Http\Controllers\API\StudentCourseEnrollmentController;

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

Route::middleware('auth:api')->get('/student', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:api'], function () {

    #region - Course Reviews - START
        Route::post('get_review', [ReviewController::class, 'getReview']);
        Route::post('delete_review', [ReviewController::class, 'deleteReview']);
        Route::post('get_reviews', [ReviewController::class, 'getReviews']);
        Route::post('update_review', [ReviewController::class, 'updateReview']);
    #endregion - Course Reviews - START

      #region - Student Course Enrollment - START
        Route::post('get_listing', [StudentCourseEnrollmentController::class, 'getStudentCourses']);
        Route::post('remove_enrollment_by_uuid', [StudentCourseEnrollmentController::class, 'removeStudentCourseEnrollment']);
        Route::post('remove_enrollment', [StudentCourseEnrollmentController::class, 'removeEnrollment']);
        Route::post('enroll_student', [StudentCourseEnrollmentController::class, 'addUpdateStudentCourseEnroll']);
      #endregion - Student Course Enrollment - START
});