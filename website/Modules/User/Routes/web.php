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
use Modules\User\Http\Controllers\UserController;

Route::group(['middleware' => 'auth'], function () {
    Route::get('/profile-setting', [UserController::class, 'updateprofileSetting'])->name('updateprofileSetting');
});



#region - Authentication Routes - START
// Route::group(['prefix' => 'auth'], function () {
    // Route::post('signup', [AuthApiController::class, 'signup']);
//     Route::post('verify-user', [AuthApiController::class, 'verifyUser']);
//     Route::any('login', [AuthApiController::class, 'login'])->name('api-login'); // for authenticate middleware for API
//     Route::post('resend-verification-token', [AuthApiController::class, 'resendVerificationToken']);
//     Route::post('forgot-password', [AuthApiController::class, 'forgotPassword']);
//     Route::post('validate-token', [AuthApiController::class, 'validateAuthToken']);
//     Route::post('reset-password', [AuthApiController::class, 'resetPassword']);

//     Route::group(['middleware' => 'auth:api'], function () {
//         Route::post('update-password', [AuthApiController::class, 'updatePassword']);
//         Route::post('signout', [AuthApiController::class, 'signout']);
//     });
// });
// #endregion - Authentication Routes - END
