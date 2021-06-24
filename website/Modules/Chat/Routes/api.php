<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Chat\Http\Controllers\API\ChatController;
use Modules\Chat\Http\Controllers\API\ChatMemberController;
use Modules\Chat\Http\Controllers\API\ChatMessageController;
use Modules\Chat\Http\Controllers\API\ChatServiceController;
use Modules\Chat\Http\Controllers\API\ChatStatsController;

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

Route::middleware('auth:api')->get('/chat', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:api'], function () {
    #region - Chat - START
        Route::post('get_chat', [ChatServiceController::class, 'getChat']);
        Route::post('delete_chat', [ChatServiceController::class, 'deleteChat']);
        Route::post('get_chats', [ChatServiceController::class, 'getChats']);
        Route::post('update_chat', [ChatServiceController::class, 'updateChat']);
    #endregion - Chat - START

    #region - Chat Member - START
        Route::post('get_chat_member', [ChatMemberController::class, 'getChatMember']);
        Route::post('delete_chat_member', [ChatMemberController::class, 'deleteChatMember']);
        Route::post('get_chat_members', [ChatMemberController::class, 'getChatMembers']);
        Route::post('update_chat_member', [ChatMemberController::class, 'updateChatMember']);
    #endregion - Chat - START

    #region - Chat Member - START
        Route::post('get_chat_message', [ChatMessageController::class, 'getChatMessage']);
        Route::post('delete_chat_message', [ChatMessageController::class, 'deleteChatMessage']);
        Route::post('get_chat_messages', [ChatMessageController::class, 'getChatMessages']);
        Route::post('update_chat_messages', [ChatMessageController::class, 'updateChatMessage']);
    #endregion - Chat - START

    #region - User Chatted List - START
        Route::post('get_user_chatted_list', [ChatStatsController::class, 'getChattedUserList']);
        Route::post('delete_chat_message', [ChatMessageController::class, 'deleteChatMessage']);
        Route::post('get_chat_messages', [ChatMessageController::class, 'getChatMessages']);
        Route::post('update_chat_messages', [ChatMessageController::class, 'updateChatMessage']);
    #endregion - User Chatted List - START


    // chat Routes
    Route::post('initiate-chat', [ChatController::class, 'initiateChat']);
    Route::post('send-chat-message', [ChatController::class, 'sendMessage']);
    Route::post('list-chat-message', [ChatController::class, 'listMessages']);

});
