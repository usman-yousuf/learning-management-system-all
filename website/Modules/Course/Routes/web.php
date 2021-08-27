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
use Modules\Student\Http\Controllers\StudentController;

Route::group(['middleware' => ['auth']], function () {
    Route::group(['as' => 'course.'], function () {
        Route::any('get-course/{uuid}', [CourseController::class, 'getCourse'])->name('get');
        Route::group(['middleware' => ['auth', 'isTeacher', 'isTeacherVerified']], function () {

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

        Route::any('view-course/{uuid}', [CourseController::class, 'viewCourse'])->name('view');

        Route::post('get-course-slots-by-course', [CourseController::class, 'getCourseSlotByCourse'])->name('get-slots-by-course');
        Route::post('get-slot/{uuid}', [CourseController::class, 'getCourseSlot'])->name('get-slot');


        //route for zoom link
        Route::any('send-zoom-link', [CourseController::class, 'sendZoomLink'])->name('sendZoomLink');
    });


    // approve teacher courses
    Route::any('approve-teacher-courses', [CourseController::class, 'getNonApprovedCourses'])->name('getNonApprovedCourses');
    Route::any('reject-teacher-course', [CourseController::class, 'rejectTeacherCourse'])->name('rejectTeacherCourse');


    //
    Route::any('student/courses/{call}', [CourseController::class, 'listStudentEnrollSuggestNature'])->name('listStudentEnrollSuggestNature');
    // Route::any('student/courses/{natur}', [StudentController::class, 'listStudentEnrollNature'])->name('listStudentEnrollByNature');
    // Route::any('student/suggest/courses/{natur}', [StudentController::class, 'listStudentSuggestedNature'])->name('listStudentSuggestedByNature');

    // approve course
    Route::any('approve-course/{uuid}', [CourseController::class, 'approveCourse'])->name('approveCourse');

    Route::group(['as' => 'query.'], function () {
        Route::group(['middleware' => ['auth', 'isTeacher', 'isTeacherVerified']], function () {
            Route::post('update-query-response', [QueryController::class, 'updateQueryResponse'])->name('update-response');
            Route::post('delete-query-response', [QueryController::class, 'deleteQueryResponse'])->name('delete-response');
        });
    });
});

Route::group(['as' => 'course.'], function () {
    Route::any('preview-course/{uuid}', [CourseController::class, 'previewCourse'])->name('preview');
    Route::any('courses', [CourseController::class, 'listTopCourses'])->name('listTopCourses');
    Route::any('courses/{nature}', [CourseController::class, 'listCoursesByNature'])->name('listCoursesByNature');
});
