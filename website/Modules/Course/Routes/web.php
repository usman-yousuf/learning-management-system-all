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

Route::group(['middleware' => 'auth', 'as' => 'course.'], function () {
    Route::post('update-course', [CourseController::class, 'updateCourseDetail'])->name('update');
    Route::post('update-outline', [CourseController::class, 'updateCourseOutline'])->name('outline');

    // Route::post('add', [AuthController::class, 'updatePassword'])->name('updatePassword');
    // Route::any('signout', [AuthController::class, 'signout'])->name('signout');
});
