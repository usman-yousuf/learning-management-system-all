<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Course\Http\Controllers\API\CategoryController;
use Modules\Course\Http\Controllers\API\CourseDetailController;

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

Route::middleware('auth:api')->get('/course', function (Request $request) {
    return $request->user();
});


Route::group(['middleware' => 'auth:api'], function () {
    #region - Course->Category Routes - START
    Route::post('get_course_category', [CategoryController::class, 'getCategory']);
    Route::post('delete_course_category', [CategoryController::class, 'deleteCategory']);
    Route::post('get_course_categories', [CategoryController::class, 'getCategories']);
    Route::post('update_course_category', [CategoryController::class, 'updateCategory']);
    #endregion - Course->Category Routes - START

      #region - Course->Detail Routes - START
      Route::post('get_course', [CourseDetailController::class, 'getCourseDetail']);
      Route::post('delete_course', [CourseDetailController::class, 'deleteCourseDetail']);
      Route::post('get_courses', [CourseDetailController::class, 'getCourseDetails']);
      Route::post('update_course', [CourseDetailController::class, 'updateCourseDetail']);
      #endregion - Course->Detail Routes - START
  

}); 