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
use Modules\AuthAll\Http\Controllers\AuthController;

Route::group(['prefix' => 'auth'], function(){
    // Route::get('/', [AuthController::class, 'index'])->name('welcome');
    Route::get('/register', [AuthController::class, 'signup'])->name('register');
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot_password');
    Route::get('/validate-code', [AuthController::class, 'validatePasswordCode'])->name('validate_code');
    Route::get('/change-password', [AuthController::class, 'changePassword'])->name('change_password');
    Route::get('/resend-verification-code', [AuthController::class, 'resendVerificationCode'])->name('resend_verification_code');
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
