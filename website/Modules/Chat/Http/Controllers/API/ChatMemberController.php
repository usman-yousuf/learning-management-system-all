<?php

namespace Modules\Chat\Http\Controllers\API;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Chat\Services\ChatMemberService;
use Modules\Chat\Services\ChatService;
use Modules\Common\Services\CommonService;
use Modules\User\Services\ProfileService;

class ChatMemberController extends Controller
{
    private $commonService;
    private $profileService;
    private $chatService;
    private $chatMemeberService;
    public function __construct(CommonService $commonService, ProfileService $profileService, ChatService $chatService, ChatMemberService $chatMemeberService )
    {
        $this->commonService = $commonService;
        $this->profileService = $profileService;
        $this->chatService = $chatService;
        $this->chatMemeberService = $chatMemeberService;
    }

    /**
     * Get a Chat Member based on uuid
     *
     * @param Request $request
     * @return void
     */
    public function getChatMember(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'chat_member_uuid' => 'required|exists:chat_members,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and fetch Student Query
        $result = $this->chatMemeberService->getChatMember($request);
        if(!$result['status']){
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $chat_member = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $chat_member);
    }

    /**
     * Delete chat Member by UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteChatMember(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'chat_member_uuid' => 'required|exists:chat_members,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and delete chat
        $result = $this->chatMemeberService->deleteChatMember($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $chat = $result['data'];

        return $this->commonService->getSuccessResponse('Record Deleted Successfully', []);
    }

    /**
     * Get Chat Members on given filters
     *
     * @param Request $request
     * @return void
     */
    public function getChatMembers(Request $request)
    {
        //chat_member_uuid
        if(isset($request->chat_member_uuid) && ('' != $request->chat_member_uuid)){
            $result = $this->chatMemeberService->getChatMember($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $chat_member = $result['data'];
            $request->merge(['chat_member_id' => $chat_member->id]);
        }

        //chat_id
        if(isset($request->chat_uuid) && ('' != $request->chat_uuid)){
            $result = $this->chatService->getChat($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $chat = $result['data'];
            $request->merge(['chat_id' => $chat->id]);
        }

        //member_uuid 
        if(isset($request->member_uuid) && ('' != $request->member_uuid)){
            $request->merge(['profile_uuid' => $request->member_uuid]);
            $result = $this->profileService->getProfile($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $profile = $result['data'];
            $request->merge(['member_id' => $profile->id]);
        }
       


        $result = $this->chatMemeberService->getChatMembers($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $chat_member = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $chat_member);
    }

    /**
     * Update Chat Members on given filters
     * @param Request $request
     * @return void
     */
    public function updateChatMember(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'chat_member_uuid' => 'exists:chat_members,uuid',
            'chat_uuid' => 'required|exists:chats,uuid',
            'member_uuid' => 'required|exists:profiles,uuid',
            'member_status' => 'required|string',
            'member_role' => 'required|string',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        //chat_id
        if(isset($request->chat_uuid) && ('' != $request->chat_uuid)){
            $result = $this->chatService->getChat($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $chat = $result['data'];
            $request->merge(['chat_id' => $chat->id]);
        }

        //member_uuid 
        if(isset($request->member_uuid) && ('' != $request->member_uuid)){
            $request->merge(['profile_uuid' => $request->member_uuid]);
            $result = $this->profileService->getProfile($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $profile = $result['data'];
            $request->merge(['member_id' => $profile->id]);
        }
       
        //chat_member_id
        $chat_member_id = null;
        if(isset($request->chat_member_uuid) && ('' != $request->chat_member_uuid)){
            $result = $this->chatMemeberService->getChatMember($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $chat_member = $result['data'];
            $chat_member_id = $chat_member->id;
        }

        $result = $this->chatMemeberService->addUpdateChatMember($request, $chat_member_id);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $chat_member = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $chat_member);
    }
}

