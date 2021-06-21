<?php

use Illuminate\Http\Request;
use Modules\Assignment\Http\Controllers\API\AssignmentController;

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

Route::middleware('auth:api')->get('/assignment', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:api'], function () {

    #region - Assignment - START
        Route::post('get_assignment', [AssignmentController::class, 'getAssignment']);
        Route::post('delete_assignment', [AssignmentController::class, 'deleteAssignment']);
        Route::post('get_assignments', [AssignmentController::class, 'getAssignments']);
        Route::post('update_assignment', [AssignmentController::class, 'addUpdateAssignment']);
    #endregion - Assignment - START

}); 