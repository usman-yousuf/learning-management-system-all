<?php

namespace Modules\Chat\Http\Controllers\API;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Chat\Services\ChatMemberService;
use Modules\Chat\Services\ChatMessageService;
use Modules\Chat\Services\ChatService;
use Modules\Common\Services\CommonService;
use Modules\User\Services\ProfileService;

class ChatMessageController extends Controller
{
    private $commonService;
    private $profileService;
    private $chatService;
    private $chatMemeberService;
    private $chatMessageService;
    public function __construct(CommonService $commonService, ProfileService $profileService, ChatService $chatService, ChatMemberService $chatMemeberService, ChatMessageService $chatMessageService )
    {
        $this->commonService = $commonService;
        $this->profileService = $profileService;
        $this->chatService = $chatService;
        $this->chatMemeberService = $chatMemeberService;
        $this->chatMessageService = $chatMessageService;
    }

    /**
     * Get a Chat Message based on uuid
     *
     * @param Request $request
     * @return void
     */
    public function getChatMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'chat_message_uuid' => 'required|exists:chat_messages,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and fetch chat message
        $result = $this->chatMessageService->getChatMessage($request);
        if(!$result['status']){
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $chat_message = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $chat_message);
    }

    /**
     * Delete chat Member by UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteChatMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'chat_message_uuid' => 'required|exists:chat_messages,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and delete chat message
        $result = $this->chatMessageService->deleteChatMessage($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $chat = $result['data'];

        return $this->commonService->getSuccessResponse('Record Deleted Successfully', []);
    }

    /**
     * Get Chat Messages on given filters
     *
     * @param Request $request
     * @return void
     */
    public function getChatMessages(Request $request)
    {
        //chat_message_uuid
        if(isset($request->chat_message_uuid) && ('' != $request->chat_message_uuid)){
            $result = $this->chatMessageService->getChatMessage($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $chat_message = $result['data'];
            $request->merge(['chat_message_id' => $chat_message->id]);
        }

        //chat_uuid
        if(isset($request->chat_uuid) && ('' != $request->chat_uuid)){
            $result = $this->chatService->getChat($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $chat = $result['data'];
            $request->merge(['chat_id' => $chat->id]);
        }

        //sender_uuid 
        if(isset($request->sender_uuid) && ('' != $request->sender_uuid)){
            $request->merge(['profile_uuid' => $request->sender_uuid]);
            $result = $this->profileService->getProfile($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $profile = $result['data'];
            $request->merge(['sender_id' => $profile->id]);
        }
       


        $result = $this->chatMessageService->getChatMessages($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $chat_member = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $chat_member);
    }

    /**
     * Update Chat Messages on given filters
     * @param Request $request
     * @return void
     */
    public function updateChatMessage(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'chat_message_uuid' => 'exists:chat_messages,uuid',
            'chat_uuid' => 'required|exists:chats,uuid',
            'sender_uuid' => 'required|exists:profiles,uuid',
            'message' => 'required|string',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }
        //chat_uuid
        if(isset($request->chat_uuid) && ('' != $request->chat_uuid)){
            $result = $this->chatService->getChat($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $chat = $result['data'];
            $request->merge(['chat_id' => $chat->id]);
        }

        //sender_uuid 
        if(isset($request->sender_uuid) && ('' != $request->sender_uuid)){
            $request->merge(['profile_uuid' => $request->sender_uuid]);
            $result = $this->profileService->getProfile($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $profile = $result['data'];
            $request->merge(['sender_id' => $profile->id]);
        }
       
        //chat_message_id
        $chat_message_id = null;
        if(isset($request->chat_message_uuid) && ('' != $request->chat_message_uuid)){
            $result = $this->chatMessageService->getChatMessage($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $chat_message = $result['data'];
            dd($chat_message->id);
            $chat_message_id = $chat_message->id;
        }

        $result = $this->chatMessageService->addUpdateChatMessage($request, $chat_message_id);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $chat_message = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $chat_message);
    }
}

