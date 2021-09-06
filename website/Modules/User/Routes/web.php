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
use Modules\User\Http\Controllers\TeacherController;
use Modules\User\Http\Controllers\UserController;

Route::group(['middleware' => 'auth'], function () {
    Route::any('profile-setting', [UserController::class, 'updateprofileSetting'])->name('updateprofileSetting');
    Route::any('approve-teacher/{uuid}', [TeacherController::class, 'approveTeacher'])->name('approveTeacher');

    //admin dashboard and approved teacher side bar link
    Route::any('admin-dashboard', [UserController::class, 'adminDashboard'])->name('adminDashboard');
    Route::any('list-non-approved-teachers', [UserController::class, 'listNonApprovedTeachers'])->name('listNonApprovedTeachers');
    Route::any('reject-teacher', [TeacherController::class, 'rejectTeacherProfile'])->name('rejectTeacherProfile'); // admin reject teacher profile



    #region - Stats Routes - START
        Route::any('students', [UserController::class, 'listStudents'])->name('listStudents');
        Route::any('enrolled-students', [UserController::class, 'listEnrolledStudents'])->name('listEnrolledStudents');
        Route::any('free-students', [UserController::class, 'listFreeStudents'])->name('listFreeStudents');
        Route::any('paying-students', [UserController::class, 'listPayingStudents'])->name('listPayingStudents');
        Route::any('parents', [UserController::class, 'listParents'])->name('listParents');
    #endregion - Stats Routes - END

});
