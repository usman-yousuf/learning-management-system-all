<?php

namespace Modules\Chat\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Chat\Http\Controllers\API\ChatController AS APIChatController;
use Modules\Common\Services\CommonService;
use Modules\User\Services\ProfileService;

class ChatController extends Controller
{
    private $chatController;

    public function __construct(CommonService $commonService, APIChatController $chatController)
    {
        $this->commonService = $commonService;
        $this->chatController = $chatController;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $ctrlObj = $this->chatController;
        $request->merge(['profile_uuid' => $request->user()->profile->uuid]);

        $chats = $notChattedUsers = [];
        $chattedUsersApiResponse = $ctrlObj->getChattedUserList($request)->getData();
        $notChattedUsersApiResponse = $ctrlObj->getNewUsersListToChat($request)->getData();
        if ($chattedUsersApiResponse->status && $notChattedUsersApiResponse->status) {
            $chats = $chattedUsersApiResponse->data;
            $notChattedUsers = $notChattedUsersApiResponse->data;

            return view('chat::index', ['chats' => $chats, 'newUsers' => $notChattedUsers]);
        }
        else{
            return view('common::errors.500');
        }
    }

    /**
     * Get Users I have chatted with (filter not working)
     *
     * @param Request $request
     * @return void
     */
    public function getChattedUserList(Request $request)
    {
        $ctrlObj = $this->chatController;
        $request->merge(['profile_uuid' => $request->user()->profile->uuid]);
        $chattedUsersApiResponse = $ctrlObj->getChattedUserList($request)->getData();

        if ($chattedUsersApiResponse->status) {
            return $this->commonService->getSuccessResponse('Chat Messages Fetched Successfully', $chattedUsersApiResponse->data);

            // return $chattedUsersApiResponse->data;
            // dd($chats);
            // return view('chat::index', ['chats' => $chats]);
        } else {
            return $this->commonService->getProcessingErrorResponse($chattedUsersApiResponse->message, $chattedUsersApiResponse->data, $chattedUsersApiResponse->responseCode, $chattedUsersApiResponse->exceptionCode);
            // return view('common::errors.500');
        }
    }

    // /**
    //  * Get Users I have not chatted with (filter not working)
    //  *
    //  * @param Request $request
    //  * @return void
    //  */
    public function getNotChattedUsers(Request $request)
    {
        $ctrlObj = $this->chatController;
        $request->merge(['profile_uuid' => $request->user()->profile->uuid]);
        $notChattedUsersApiResponse = $ctrlObj->getNewUsersListToChat($request)->getData();

        if ($notChattedUsersApiResponse->status) {
            return $this->commonService->getSuccessResponse('Chat Messages Fetched Successfully', $notChattedUsersApiResponse->data);

            // return $chattedUsersApiResponse->data;
            // dd($chats);
            // return view('chat::index', ['chats' => $chats]);
        } else {
            return $this->commonService->getProcessingErrorResponse($notChattedUsersApiResponse->message, $notChattedUsersApiResponse->data, $notChattedUsersApiResponse->responseCode, $notChattedUsersApiResponse->exceptionCode);
            // return view('common::errors.500');
        }
    }

    /**
     * get All Message againt Chat UUID
     *
     * @param Request $request
     * @return void
     */
    public function getChatMessages(Request $request)
    {
        $ctrlObj = $this->chatController;
        // $request->merge(['profile_uuid' => $request->user()->profile->uuid]);

        $chats = [];
        $messagesResponse = $ctrlObj->getChatMessages($request)->getData();
        if ($messagesResponse->status) {
            unset($messagesResponse->data->chat->messages);
            $messageData = $messagesResponse->data;
            $chat = $messageData->chat;
            $chat->messages = $messagesResponse->data->chat_messages;
            $chat->total_messages_count = $messagesResponse->data->total_count;
            $html = view('chat::partials.chat_messages_listing', compact('chat'))->render();
            $data['html'] = $html;
            $data['chat'] = $chat;
            return $this->commonService->getSuccessResponse('Chat Messages Fetched Successfully', $data);

        } else {
            return $this->commonService->getProcessingErrorResponse($messagesResponse->message, $messagesResponse->data, $messagesResponse->responseCode, $messagesResponse->exceptionCode);
        }
    }

    /**
     * Delete a Chat by its UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteChat(Request $request)
    {
        $ctrlObj = $this->chatController;
        // $request->merge(['profile_uuid' => $request->user()->profile->uuid]);

        $chats = [];
        $deleteResponse = $ctrlObj->deleteChat($request)->getData();
        if ($deleteResponse->status) {
            $chat = $deleteResponse->data;
            return $this->commonService->getSuccessResponse('Chat Deleted Successfully', $chat);
        } else {
            dd($deleteResponse);
            return $this->commonService->getProcessingErrorResponse($deleteResponse->message, $deleteResponse->data, $deleteResponse->responseCode, $deleteResponse->exceptionCode);
        }

    }

    // public function getExistingUsersbyKeywords(Request $request)
    // {
    //     $ctrlObj = $this->chatController;
    //     $request->merge(['profile_uuid' => $request->user()->profile->uuid]);

    //     $chats = [];
    //     $chattedUsersApiResponse = $ctrlObj->getChattedUserList($request)->getData();

    //     if ($chattedUsersApiResponse->status) {
    //         $chats = $chattedUsersApiResponse->data;
    //         return view('chat::partials.chat_users_listing.blade', ['chats' => $chats]);
    //     } else {
    //         return view('common::errors.500');
    //     }
    // }

    // public function getNewUsersbyKeywords(Request $request)
    // {
    //     $ctrlObj = $this->chatController;
    //     $request->merge(['profile_uuid' => $request->user()->profile->uuid]);

    //     $notChattedUsers = [];
    //     $notChattedUsersApiResponse = $ctrlObj->getNewUsersListToChat($request)->getData();

    //     if ($notChattedUsersApiResponse->status) {
    //         $notChattedUsers = $notChattedUsersApiResponse->data;
    //         return view('chat::partials.chat_users_listing.blade', ['newUsers' => $notChattedUsers]);
    //     } else {
    //         return view('common::errors.500');
    //     }
    // }

    public function sendMessage(Request $request)
    {
        $ctrlObj = $this->chatController;
        $request->merge(['sender_uuid' => $request->user()->profile->uuid]);

        $senMessageResponse = $ctrlObj->sendMessage($request)->getData();
        if ($senMessageResponse->status) {
            $chats = $senMessageResponse->data;
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('chat::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('chat::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('chat::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
