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
use Modules\Chat\Http\Controllers\ChatController;

Route::group(['middleware' => ['auth']], function () {
    Route::group(['prefix' => 'chat', 'as' => 'chat.'], function () {
        Route::get('/', [ChatController::class, 'index'])->name('index');
    });
});
