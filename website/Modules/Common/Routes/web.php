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

Route::prefix('common')->group(function() {
    Route::get('/', 'CommonController@index');
    Route::post('upload-files', [DocumentController::class, 'uploadFiles'])->name('uploadFiles');
});
