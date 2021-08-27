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
use Modules\Teacher\Http\Controllers\TeacherController;
Route::get('view-teacher-profile/{uuid}', [TeacherController::class, 'viewTeacherProfile'])->name('viewTeacherProfile'); // public link

Route::group(['prefix' => 'teacher', 'as'=>'teacher.'], function() {
});

Route::group(['middleware' => ['auth', 'isTeacher','isTeacherVerified']], function () {
    Route::group(['prefix' => 'teacher', 'as'=>'teacher.'], function() {
        Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard');
    });
});
