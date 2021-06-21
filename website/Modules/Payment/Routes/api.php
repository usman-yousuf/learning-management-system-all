<?php

use Illuminate\Http\Request;
use Modules\Payment\Http\Controllers\API\PaymentHistoryController;

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

Route::middleware('auth:api')->get('/payment', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:api'], function () {
    #region - Payment History- START
        Route::post('get_payment_history', [PaymentHistoryController::class, 'getPaymentHistory']);
        Route::post('delete_payment_history', [PaymentHistoryController::class, 'deletePaymentHistory']);
        Route::post('get_payment_histories', [PaymentHistoryController::class, 'getPaymentHistories']);
        Route::post('update_payment_history', [PaymentHistoryController::class, 'updatePaymentHistory']);
    #endregion -Payment History - START



}); 