<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Course\Http\Controllers\API\CategoryController;
use Modules\Course\Http\Controllers\API\CourseContentController;
use Modules\Course\Http\Controllers\API\CourseDetailController;
use Modules\Course\Http\Controllers\API\CourseOutlineController;
use Modules\Course\Http\Controllers\API\CourseSlotController;
use Modules\Course\Http\Controllers\API\HandoutContentController;

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
  
    #region - Course->Handout Routes - START
    Route::post('get_course_handout_content', [HandoutContentController::class, 'getHandoutContent']);
    Route::post('delete_course_handout_content', [HandoutContentController::class, 'deleteHandoutContent']);
    Route::post('get_course_handout_contents', [HandoutContentController::class, 'getHandoutContents']);
    Route::post('update_course_handout_content', [HandoutContentController::class, 'updateHandoutContent']);
    #endregion - Course->Handout Routes - START

    #region - Course->Outline Routes - START
    Route::post('get_course_outline', [CourseOutlineController::class, 'getCourseOutline']);
    Route::post('delete_course_outline', [CourseOutlineController::class, 'deleteCourseOutline']);
    Route::post('get_course_outlines', [CourseOutlineController::class, 'getCourseOutlines']);
    Route::post('update_course_outline', [CourseOutlineController::class, 'updateCourseOutline']);
    #endregion - Course->Outline Routes - START

    #region - Course->Content Routes - START
    Route::post('get_course_content', [CourseContentController::class, 'getCourseContent']);
    Route::post('delete_course_content', [CourseContentController::class, 'deleteCourseContennt']);
    Route::post('get_course_contents', [CourseContentController::class, 'getCourseContents']);
    Route::post('update_course_content', [CourseContentController::class, 'updateCourseContent']);
    #endregion - Course->Content Routes - START


        #region - Course->Slot Routes - START
        Route::post('get_course_slot', [CourseSlotController::class, 'getCourseSlot']);
        Route::post('delete_course_slot', [CourseSlotController::class, 'deleteCourseSlot']);
        Route::post('get_course_slots', [CourseSlotController::class, 'getCourseSlots']);
        Route::post('update_course_slot', [CourseSlotController::class, 'updateCourseSlot']);
        #endregion - Course->Slot Routes - START

}); 