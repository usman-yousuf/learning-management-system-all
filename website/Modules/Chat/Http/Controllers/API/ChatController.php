<?php

namespace Modules\Chat\Http\Controllers\API;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\Chat\Services\ChatMessageService;
use Modules\Chat\Services\ChatService;
use Modules\Common\Services\CommonService;
use Modules\User\Services\ProfileService;

class ChatController extends Controller
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
    }



    /**
     * Get Users I have chatted with
     *
     * @param Request $request
     * @return void
     */
    public function getChattedUserList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_uuid' => 'required|exists:profiles,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        //member_uuid
        if (isset($request->profile_uuid) && ('' != $request->profile_uuid)) {
            $result = $this->profileService->getProfile($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $profile = $result['data'];
            $request->merge(['profile_id' => $profile->id]);
            $request->merge(['profile_type' => $profile->profile_type]);
        }


        // validate and fetch Student Query
        $result = $this->chatService->chattedIndividualUserList($request);
        if (!$result['status']) {
            if (404 == $result['exceptionCode']) {
                return $this->commonService->getProcessingErrorResponse('No User Found', [], 404, 404);
            }
            return $this->commonService->getProcessingErrorResponse($result['message'], [], $result['responseCode'], $result['exceptionCode']);
        }
        $profiles = $result['data'];
        return $this->commonService->getSuccessResponse('Success', $profiles);
    }

    /**
     * Get Users I have Not chatted with yet
     *
     * @param Request $request
     * @return void
     */
    public function getNewUsersListToChat(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_uuid' => 'required|exists:profiles,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        //member_uuid
        if (isset($request->profile_uuid) && ('' != $request->profile_uuid)) {
            $result = $this->profileService->getProfile($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $profile = $result['data'];
            $request->merge(['profile_id' => $profile->id]);
            $request->merge(['profile_type' => $profile->profile_type]);
        }


        // validate and fetch Student Query
        $result = $this->chatService->NotChattedIndividualUserList($request);
        if (!$result['status']) {
            if (404 == $result['exceptionCode']) {
                return $this->commonService->getProcessingErrorResponse('No User Found', [], 404, 404);
            }
            return $this->commonService->getProcessingErrorResponse($result['message'], [], $result['responseCode'], $result['exceptionCode']);
        }
        $people = $result['data'];
        return $this->commonService->getSuccessResponse('Success', $people);
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
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $chat_message = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $chat_message);
    }


}
