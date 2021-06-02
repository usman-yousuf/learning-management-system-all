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

use Modules\Student\Http\Controllers\StudentController;

Route::prefix('student')->group(function() {
    Route::get('/', 'StudentController@index');
});


Route::group(['middleware' => 'auth'], function () {

    // Report Routes
    Route::group(['as' => 'student.'], function () {
        Route::any('student-list', [StudentController::class, 'studentList'])->name('student-list');
        // Route::any('sales-report', [ReportController::class, 'salesReport'])->name('sales');
    });

});
