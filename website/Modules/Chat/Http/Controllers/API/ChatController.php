<?php

namespace Modules\Chat\Http\Controllers\API;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
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

    public function getChatMessages(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'chat_uuid' => 'required|exists:chats,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate chat
        if (isset($request->chat_uuid) && ('' != $request->chat_uuid)) {
            $result = $this->chatService->checkChat($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $chat = $result['data'];
            $request->merge(['chat_id' => $chat->id]);
        }


        $result = $this->chatMessageService->getChatMessages($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $chatMessages = $result['data'];
        return $this->commonService->getSuccessResponse('Success', $chatMessages);
    }


    public function sendMessage(Request $request)
    {
        $message = 'Hi there';
        $reciever_uuid = 'Hi there';
        $sender_uuid = $request->sender_uuid;
        $chat_uuid = 'Hi there';

        return (object)[
            'message_body' => 'Hi there'
            , 'sender_id' => 2
            , 'sender_uuid' => 'ed03a977-a430-4805-8ebe-ad8fde94a964'
            , 'sender_first_name' => 'ANB'
            , 'sender_last_name' => 'parent'
            , 'sender_profile_image' => 'profiles/parent.png'

            , 'chat_id' => '1'
            , 'chat_uuid' => '1'
        ];

        $dropProcedure = DB::raw("DROP PROCEDURE IF EXISTS `sp_send_chat_message`;");
        $procedure = DB::raw("
            delimiter ;;
            CREATE PROCEDURE `sp_send_chat_message` (IN message_body VARCHAR(255), IN reciever_uuid VARCHAR(255), IN sender_uuid VARCHAR(255), IN chat_uuid VARCHAR(255))
            BEGIN
                SELECT profile_id FROM profiles WHERE uuid = 'sender_uuid' LIMIT 1 into @sender_id;
                PRINT @sender_id
                -- IF chat_uuid IS NULL THEN
                -- IF EXISTS (SELECT ...)
                    -- BEGIN
                        -- -- PRINT 'Do nothing.';
                    -- END
                -- ELSE
                    -- BEGIN
                        -- INSERT INTO chats
                    -- END
                -- END IF;
                -- ELSE commands
                    -- -- statements ;
                -- END IF;
                -- INSERT INTO users (name, email, password) VALUES (uName, uEmail, uPassword);
            END
            ;;
            delimiter ;
        ");

        $procedure = DB::raw("
            DELIMITER $$
            CREATE PROCEDURE `sp_send_chat_message` (IN message_body VARCHAR(255), IN reciever_uuid VARCHAR(255), IN sender_uuid VARCHAR(255), IN chat_uuid VARCHAR(255))
            BEGIN
                SELECT id FROM profiles WHERE uuid = sender_uuid;
            END$$

            DELIMITER ;
        ");

        dd($dropProcedure, $procedure);

        $result = DB::select (
            "call sp_send_chat_message(?, ?, ?, ?)",
            [
                "{$message}",
                "{$reciever_uuid}",
                "{$sender_uuid}",
                "{$chat_uuid}"
            ]
        );
        dd($result);
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
