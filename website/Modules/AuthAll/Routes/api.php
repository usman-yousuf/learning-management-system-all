<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\AuthAll\Http\Controllers\API\AuthApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// Authentication Routes
Route::group(['prefix' => 'auth'], function () {
    Route::post('signup', [AuthApiController::class, 'signup']);
    Route::post('verify-user', [AuthApiController::class, 'verifyUser']);
    Route::any('login', [AuthApiController::class, 'login'])->name('api-login'); // for authenticate middleware for API
    Route::post('resend-verification-token', [AuthApiController::class, 'resendVerificationToken']);
    Route::post('forgot-password', [AuthApiController::class, 'forgotPassword']);
    Route::post('validate-token', [AuthApiController::class, 'validateAuthToken']);
    Route::post('reset-password', [AuthApiController::class, 'resetPassword']);

    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('update-password', [AuthApiController::class, 'updatePassword']);
        Route::post('signout', [AuthApiController::class, 'signout']);
    });
});
