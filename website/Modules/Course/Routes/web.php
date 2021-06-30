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
use Modules\Course\Http\Controllers\CourseController;
use Modules\Course\Http\Controllers\QueryController;

Route::group(['middleware' => ['auth']], function () {

    Route::group(['as' => 'course.'], function () {

        Route::group(['middleware' => ['auth', 'isTeacher', 'isTeacherVerified']], function () {
            Route::any('get-course/{uuid}', [CourseController::class, 'getCourse'])->name('get');
            Route::post('get-slot/{uuid}', [CourseController::class, 'getCourseSlot'])->name('get-slot');


            Route::post('update-course', [CourseController::class, 'updateCourseDetail'])->name('update');

            Route::post('update-outline', [CourseController::class, 'updateCourseOutline'])->name('outline');
            Route::post('delete-outline', [CourseController::class, 'deleteCourseOutline'])->name('delete-outline');

            Route::post('update-video-course-content', [CourseController::class, 'updateVideoCourseContent'])->name('video-content');
            Route::post('delete-video-course-content', [CourseController::class, 'deleteCourseVideoContent'])->name('delete-video-content');

            Route::post('update-course-handout', [CourseController::class, 'updateCourseHandoutContent'])->name('handout');
            Route::post('delete-course-handout', [CourseController::class, 'deleteCourseHandoutContent'])->name('delete-handout');

            Route::post('update-course-slot', [CourseController::class, 'updateCourseSlot'])->name('slot');
            Route::post('delete-course-slot', [CourseController::class, 'deleteCourseSlot'])->name('delete-slot');
        });

        Route::any('courses', [CourseController::class, 'listTopCourses'])->name('listTopCourses');
        Route::any('courses/{nature}', [CourseController::class, 'listCoursesByNature'])->name('listCoursesByNature');
        Route::any('view-course/{uuid}', [CourseController::class, 'viewCourse'])->name('view');
        Route::post('get-course-slots-by-course', [CourseController::class, 'getCourseSlotByCourse'])->name('get-slots-by-course');
    });

    Route::group(['as' => 'query.'], function () {
        Route::group(['middleware' => ['auth', 'isTeacher', 'isTeacherVerified']], function () {
            Route::post('update-query-response', [QueryController::class, 'updateQueryResponse'])->name('update-response');
            Route::post('delete-query-response', [QueryController::class, 'deleteQueryResponse'])->name('delete-response');
        });
    });
});
