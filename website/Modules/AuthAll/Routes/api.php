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

Route::middleware('auth:api')->get('/authall', function (Request $request) {
    return $request->user();
});


Route::middleware('auth:api')->get('/authall', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthApiController::class, 'login']);
    // Route::post('signup', [AuthApiController::class, 'signup']);
    // Route::post('verify-user', [AuthApiController::class, 'verifyUser']);
    // Route::post('resend-verification-token', [AuthApiController::class, 'resendVerificationToken']);
    // Route::post('forgot-password', [AuthApiController::class, 'forgotPassword']);
    // Route::post('validate-token', [AuthApiController::class, 'validateAuthToken']);
    // Route::post('reset-password', [AuthApiController::class, 'resetPassword']);

    // Route::group(['middleware' => 'auth:api'], function () {
    //     Route::post('signout', [AuthApiController::class, 'signout']);
    //     Route::post('update-password', [AuthApiController::class, 'updatePassword']);

    //     // Route::post('change_social_password', 'App\Http\Controllers\AuthApiController@changeSocialLoginPassword');
    // });
});
