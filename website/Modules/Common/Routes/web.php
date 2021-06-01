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
use Modules\Common\Http\Controllers\API\DocumentController;
use Modules\Common\Http\Controllers\ReportController;

Route::prefix('common')->group(function() {
    Route::get('/', 'CommonController@index');
    Route::post('upload-files', [DocumentController::class, 'uploadFiles'])->name('uploadFiles');
});



Route::group(['middleware' => 'auth', 'as' => 'report.'], function () {
    Route::any('general-report', [ReportController::class, 'report'])->name('general');
    Route::any('sales-report', [ReportController::class, 'salesReport'])->name('sales');
});

