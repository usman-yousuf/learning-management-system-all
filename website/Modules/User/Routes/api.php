<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\API\AddressController;

use Modules\User\Http\Controllers\API\EducationController;
use Modules\User\Http\Controllers\API\ExperienceController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:api'], function () {
    #region - Address Routes - START
        Route::post('get_addresses', [AddressController::class, 'getAddresses']);
        Route::post('update_address', [AddressController::class, 'updateAddress']);
        Route::post('get_address', [AddressController::class, 'getAddress']);
        Route::post('delete_address', [AddressController::class, 'deleteAddress']);
    #endregion - Address Routes - START


    #region - Address Routes - START
        Route::post('get_experiences', [ExperienceController::class, 'getExperiences']);
        Route::post('update_experience', [ExperienceController::class, 'updateExperience']);
        Route::post('get_experience', [ExperienceController::class, 'getExperience']);
        Route::post('delete_experience', [ExperienceController::class, 'deleteExperience']);
    #endregion - Address Routes - START


    #region - Education Routes - START
        Route::post('get_education', [EducationController::class, 'getEducation']);
        Route::post('delete_education', [EducationController::class, 'deleteEducation']);
        Route::post('get_educations', [EducationController::class, 'getEducations']);
        Route::post('update_education', [EducationController::class, 'updateEducation']);
    #region - Education Routes - END


});
