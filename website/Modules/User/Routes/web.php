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

    // admin reject teacher profile
    Route::any('reject-teacher', [TeacherController::class, 'rejectTeacherProfile'])->name('rejectTeacherProfile');


    #region - Address Routes - START
        // Route::post('get-addresses', [AddressController::class, 'getAddresses'])->name('getAddresses');
        // Route::post('update-address', [AddressController::class, 'updateAddress'])->name('updateAddress');
        // Route::post('get-address', [AddressController::class, 'getAddressByUUId'])->name('getAddressByUUId');
        // Route::post('delete-address', [AddressController::class, 'deleteAddressByUUId'])->name('deleteAddressByUUId');
    #endregion - Address Routes - END

});
