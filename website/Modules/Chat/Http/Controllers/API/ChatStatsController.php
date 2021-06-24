<?php

namespace Modules\Chat\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\Chat\Services\ChatMessageService;
use Modules\Chat\Services\ChatService;
use Modules\Common\Services\CommonService;
use Modules\User\Services\ProfileService;

class ChatStatsController extends Controller
{
    private $commonService;
    private $profileService;
    private $chatService;
    private $chatMessageService;

    public function __construct(CommonService $commonService, ProfileService $profileService, ChatService $chatService, ChatMessageService $chatMessageService)
    {
        $this->commonService = $commonService;
        $this->profileService = $profileService;
        $this->chatService = $chatService;
        $this->chatMessageService = $chatMessageService;
        $this->chatService = $chatService;
    }

    /**
     * Delete chat by UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteChat(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'chat_uuid' => 'required|exists:chats,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and delete chat
        $result = $this->chatService->deleteChat($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $chat = $result['data'];

        return $this->commonService->getSuccessResponse('Record Deleted Successfully', []);
    }

    /**
     * Get Chat on given filters
     *
     * @param Request $request
     * @return void
     */
    public function getChats(Request $request)
    {
        //chat_uuid
        if(isset($request->chat_uuid) && ('' != $request->chat_uuid)){
            $result = $this->chatService->getChat($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $chat = $result['data'];
            $request->merge(['chat_id' => $chat->id]);
        }

        //parent_uuid
        if(isset($request->parent_uuid) && ('' != $request->parent_uuid)){
            $request->merge(['profile_uuid' => $request->parent_uuid]);
            $result = $this->profileService->getProfile($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $profile = $result['data'];
            $request->merge(['parent_id' => $profile->id]);
        }

        //last_message_uuid
        if(isset($request->last_message_uuid) && ('' != $request->last_message_uuid)){
            $request->merge(['chat_message_uuid' => $request->last_message_uuid]);
            $result = $this->chatMessageService->getChatMessage($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $chat_message = $result['data'];
            $request->merge(['chat_message_id' => $chat_message->id]);
        }


        $result = $this->chatService->getChats($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $handout_content = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $handout_content);
    }

    /**
     * Add|Update Student Query
     *
     * @param Request $request
     * @return void
     */
    public function updateChat(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'chat_uuid' => 'exists:chats,uuid',
            'parent_uuid' => 'required|exists:profiles,uuid',
            'last_message_uuid' => 'required|exists:chat_messages,uuid',
            'title' => 'required|string',
            'type' => 'required|string',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

         //parent_uuid
         if(isset($request->parent_uuid) && ('' != $request->parent_uuid)){
            $request->merge(['profile_uuid' => $request->parent_uuid]);
            $result = $this->profileService->getProfile($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $profile = $result['data'];
            $request->merge(['parent_id' => $profile->id]);
        }

        //last_message_uuid
        if(isset($request->last_message_uuid) && ('' != $request->last_message_uuid)){
            $request->merge(['chat_message_uuid' => $request->last_message_uuid]);
            $result = $this->chatMessageService->getChatMessage($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $chat_message = $result['data'];
            $request->merge(['chat_message_id' => $chat_message->id]);
        }

        // chat_id
        $chat_id = null;
        if(isset($request->chat_uuid) && ('' != $request->chat_uuid)){
            $result = $this->chatService->getChat($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $chat = $result['data'];
            $chat_id = $chat->id;
        }

        $result = $this->chatService->addUpdateChat($request, $chat_id);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $chat = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $chat);
    }
}
