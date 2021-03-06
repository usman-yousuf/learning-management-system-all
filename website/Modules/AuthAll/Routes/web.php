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
    Route::group(['middleware' => 'guest'], function () {
        // Route::get('/', [AuthController::class, 'index']);
        Route::group(['prefix' => 'teacher' ], function(){
            Route::any('/register', [AuthController::class, 'signup'])->name('register');
            Route::any('/login', [AuthController::class, 'login'])->name('login');
            Route::any('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgotPassword');
        });

        //general route for forgot password
        // Route::any('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgotPassword');

        Route::group(['prefix' => 'student'], function(){
            Route::any('/register', [AuthController::class, 'signupStudent'])->name('registerStudent');
            Route::any('/login', [AuthController::class, 'loginStudent'])->name('loginStudent');
            Route::any('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgotStudentPassword');
        });

        Route::group(['prefix' => 'parent'], function(){
            Route::any('/register', [AuthController::class, 'signupParent'])->name('registerParent');
            Route::any('/login', [AuthController::class, 'loginParent'])->name('loginParent');
            Route::any('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgotParentPassword');
            
        });

        //admin routes
        Route::group(['prefix' => 'admin'], function(){
            // Route::any('/register', [AuthController::class, 'signupParent'])->name('registerParent');
            Route::any('/login', [AuthController::class, 'loginAdmin'])->name('loginAdmin');
            Route::any('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgotAdminPassword');
            
        });


        Route::any('/validate-code', [AuthController::class, 'validatePasswordCode'])->name('validatePasswordCode');
        Route::any('/reset-password', [AuthController::class, 'resetPassword'])->name('resetPassword');
        Route::any('/resend-verification-code', [AuthController::class, 'resendVerificationCode'])->name('resendVerificationCode');
    });

    Route::group(['middleware' => 'auth'], function () {
        Route::post('update-password', [AuthController::class, 'updatePassword'])->name('updatePassword');
        Route::any('signout', [AuthController::class, 'signout'])->name('signout');
    });
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
