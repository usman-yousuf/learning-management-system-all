<?php

namespace Modules\Chat\Http\Controllers\API;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\Chat\Services\ChatMessageService;
use Modules\Common\Services\CommonService;
use Modules\User\Services\ProfileService;

class ChatController extends Controller
{
    private $commonService;
    private $profileService;
    private $chatMessageService;
    public function __construct(CommonService $commonService, ProfileService $profileService, ChatMessageService $chatMessageService)
    {
        $this->commonService = $commonService;
        $this->profileService = $profileService;
        $this->$chatMessageService = $chatMessageService;
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
