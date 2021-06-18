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
use Modules\Assignment\Http\Controllers\AssignmentController;

Route::group(['middleware' => 'auth'], function () {

    // assignment controller
    Route::prefix('assignment')->group(function () {
        Route::get('/', 'AssignmentController@index');
    });

    Route::group(['middleware' => ['isTeacher','isTeacherVerified']], function () {
        // update an assignment
        Route::group(['as' => 'assignment.'], function () {
            Route::post('update-assignment', [AssignmentController::class, 'addUpdateAssignment'])->name('update-assignment');
        });
    });
});
