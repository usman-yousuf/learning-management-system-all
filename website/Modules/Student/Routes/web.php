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
use Modules\Student\Http\Controllers\StudentController;

Route::prefix('student')->group(function() {
    Route::get('/', 'StudentController@index');
});


Route::group(['middleware' => 'auth'], function () {

    // Report Routes
    Route::group(['prefix' =>'studnet','as' => 'student.'], function () {
        Route::any('student-list', [StudentController::class, 'studentList'])->name('student-list');
        Route::any('check_slot', [StudentController::class, 'slotExist'])->name('slot-exists');
        Route::any('enroll-student', [StudentController::class, 'enrollStudent'])->name('enroll');
        Route::get('dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
        // Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');

        // Route::any('sales-report', [ReportController::class, 'salesReport'])->name('sales');
    });

    Route::group(['prefix' =>'parent', 'as' => 'parent.'], function () {
        Route::get('dashboard', [StudentController::class, 'parentDashboard'])->name('dashboard');
    });

});
