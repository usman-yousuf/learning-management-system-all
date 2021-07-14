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
        Route::post('get-chat-messages', [ChatController::class, 'getChatMessages'])->name('getChatMessages');
        Route::post('delete-chat', [ChatController::class, 'deleteChat'])->name('deleteChat');
        // Route::post('get-chat-messages', [ChatController::class, 'getChatMessages'])->name('getChatMessages');
        Route::get('send-message', [ChatController::class, 'sendMessage'])->name('sendMessage');

        Route::post('get-chatted-users', [ChatController::class, 'getChattedUserList'])->name('getChattedUsers');
        Route::post('get-not-chatted-users', [ChatController::class, 'getNotChattedUsers'])->name('getNotChattedUsers');

    });
});
