<?php

namespace Modules\Chat\Http\Controllers\API;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Chat\Services\ChatMemberService;
use Modules\Chat\Services\ChatMessageService;
use Modules\Chat\Services\ChatService;
use Modules\Common\Services\CommonService;
use Modules\Course\Http\Controllers\API\CourseSlotController;
use Modules\User\Services\ProfileService;

class ChatController extends Controller
{
    private $commonService;
    private $profileService;
    private $chatService;
    private $chatMessageService;
    private $courseSlotService;
    private $chatMemberService;

    public function __construct(CommonService $commonService, ProfileService $profileService, ChatService $chatService, ChatMessageService $chatMessageService, CourseSlotController $courseSlotService, ChatMemberService $chatMemberService)
    {
        $this->commonService = $commonService;
        $this->profileService = $profileService;
        $this->chatService = $chatService;
        $this->chatMessageService = $chatMessageService;
        $this->courseSlotService = $courseSlotService;
        $this->chatMemberService = $chatMemberService;
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
     * Get View containing chat and html
     *
     * @param Request $request
     * @return void
     */
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
        $result = $this->chatService->checkChat($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $chat = $result['data'];
        $request->merge(['chat_id' => $chat->id]);
        // dd($request->all());
        $total_chat_messages_count = 0;
        if (isset($request->offset) && isset($request->limit)) {
            $total_chat_messages_count = $chat->messages->count();
            unset($chat->messages);
            $chat->messages = $chat->messages->skip($request->offset)->take($request->limit);
            // dd($chat->messages);
        }

        $chatMessages['chat'] = $chat;
        // dd($chat->messages);
        // dd($chatMessages['chat']->messages);
        $chatMessages['chat_messages'] = $chat->messages;
        $chatMessages['total_count'] = $total_chat_messages_count;
        // dd($chatMessages);
        // $chatMessages = (object)$chatMessages;
        // $result = $this->chatMessageService->getChatMessages($request);
        // if (!$result['status']) {
        //     return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        // }
        // $chatMessages = $result['data'];
        // dd($chatMessages);
        // dd($chatMessages['chat']);
        return $this->commonService->getSuccessResponse('Success', $chatMessages);
    }


    public function sendMessage_test(Request $request)
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
     * Send message
     *
     * @param Request $request
     * @return void
     */
    public function sendMessage(Request $request, $reciever_uuid = null, $chat_uuid, $response_message_uuid=null)
    {   
        $reciver_id = null; $chat_id = null; $response_message_id = null;

        
        $request->merge(['profile_uuid' => $request->user()->profile->uuid]);
        $result = $this->profileService->checkProfile($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $data = $result['data'];
        $sender_id = $data->id;
        $request->merge(['sender_id' => $sender_id]);

        
        // find reciever id
        $profile_id = null;
        if(null == $reciever_uuid)
        {
           $result = $this->courseSlotService->getCourseSlot($request)->getData();
           if (!$result->status) {
                return $this->commonService->getGeneralErrorResponse($result->message, $result->data);
            }
            $data = $result->data;
            // dd($data->last_enrolment->student_id);
            $reciver_id = $data->last_enrolment->student_id;
            $profile_id = $reciver_id;
        }

        // check profile type of the reciever 
        $result = $this->profileService->getProfileById($profile_id);
        if(!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $profile_data = $result['data'];
        $profile_type = $profile_data->profile_type;
        // dd($data);



        // check chat exists or not
        if(null == $chat_id)
        {
            $request->merge([
                'parent_id' => $sender_id,
                'type' => 'single'
            ]);
            $result = $this->chatService->addUpdateChat($request, $chat_id);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
            }
            $data = $result['data'];
            $chat_id = $data->id;
        }

        // add chat members
        $chat_member_id = null;
        $request->merge([
            'member_id' => $reciver_id,
            'member_role' => $profile_type,
            'chat_id' => $chat_id
        ]);
        // dd($request->all(), $chat_id);
        $result = $this->chatMemberService->addUpdateChatMember($request, $chat_member_id);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $data = $result['data'];


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


        $request->merge(['message' => $request->zoom_link]);
        $result = $this->chatMessageService->addUpdateChatMessage($request, $chat_member_id);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $data = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $data);


        // if($chat_uuid !=null)
        // {
        //     if(isset($chat_uuid) && ('' != $chat_uuid)){
        //         $request->merge(['chat_uuid' => $chat_uuid]);
        //         $result = $this->chatService->checkChat($request);
        //         $result = $this->profileService->checkStudent($request);
        //         if (!$result['status']) {
        //             return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        //         }
        //         $data = $result['data'];
        //         $chat_id = $data->id;
        //     }
        // }

        // if($response_message_uuid !=null)
        // {
        //     if(isset($response_message_uuid) && ('' != $response_message_uuid)){
        //         $request->merge(['response_message_uuid' => $response_message_uuid]);
        //         $result = $this->chatService->checkChat($request);
        //         $result = $this->profileService->checkStudent($request);
        //         if (!$result['status']) {
        //             return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        //         }
        //         $data = $result['data'];
        //         $chat_id = $data->id;
        //     }
        // }


            // $result = $this->chatService->addUpdateChat($request, $chat_id);
            // if (!$result['status']) {
            //     return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
            // }
            // $chat = $result['data'];
            // $chat_message_id = $chat->id;


            // $result = $this->chatMessageService->addUpdateChatMessage($request, $chat_message_id);
            // if (!$result['status']) {
            //     return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
            // }
            // $chat_message = $result['data'];
            // $chat_message_id = $chat_message->id;


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
        $result = $this->chatMessageService->checkChatMessage($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $chat_message = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $chat_message);
    }

    /**
     * Delete a Chat
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

        DB::beginTransaction();
        $result = $this->chatService->deleteChat($request);
        if (!$result['status']) {
            DB::rollBack();
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $chat = $result['data'];
        // dd($chat);
        // DB::commit();
        return $this->commonService->getSuccessResponse('Success', $chat);
    }


}
