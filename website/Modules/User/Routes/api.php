<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\API\AddressController;

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
    Route::post('get_addresses', [AddressController::class, 'getAddresses']);
    Route::post('update_address', [AddressController::class, 'updateAddress']);

    Route::post('get_address', [AddressController::class, 'getAddress']);
    Route::post('delete_address', [AddressController::class, 'deleteAddress']);
});
