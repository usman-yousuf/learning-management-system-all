<?php

namespace Modules\Chat\Http\Controllers\API;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Chat\Entities\Chat;
use Modules\Chat\Entities\ChatMember;
use Modules\Chat\Services\ChatMemberService;
use Modules\Chat\Services\ChatMessageService;
use Modules\Chat\Services\ChatService;
use Modules\Common\Services\CommonService;
use Modules\Course\Http\Controllers\API\CourseSlotController;
use Modules\Course\Services\CourseSlotService;
use Modules\User\Services\ProfileService;

class ChatController extends Controller
{
    private $commonService;
    private $profileService;
    private $chatService;
    private $chatMessageService;
    private $courseSlotController;
    private $chatMemberService;
    private $couseSlotService;

    public function __construct(CommonService $commonService, ProfileService $profileService, ChatService $chatService, ChatMessageService $chatMessageService, CourseSlotController $courseSlotController, ChatMemberService $chatMemberService, CourseSlotService $courseSlotService)
    {
        $this->commonService = $commonService;
        $this->profileService = $profileService;
        $this->chatService = $chatService;
        $this->chatMessageService = $chatMessageService;
        $this->courseSlotController = $courseSlotController;
        $this->chatMemberService = $chatMemberService;
        $this->couseSlotService = $courseSlotService;
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
    public function sendMessage(Request $request, $reciever_uuid = null, $chat_uuid = null, $response_message_uuid = null)
    {
        $reciver_id = null; $chat_id = null; $response_message_id = null;


        $request->merge(['profile_uuid' => $request->user()->profile->uuid]);
        $result = $this->profileService->checkProfile($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $data = $result['data'];
        $sender_id = $data->id;
        $parent_profile_type = $data->profile_type;
        $request->merge([
            'sender_id' => $sender_id,
            'parent_id' => $sender_id,
        ]);


        // find reciever id
        $profile_id = null;
        if(null == $reciever_uuid)
        {
           $result = $this->courseSlotController->getCourseSlot($request)->getData();
           if (!$result->status) {
                return $this->commonService->getGeneralErrorResponse($result->message, $result->data);
            }
            $data = $result->data;
            // dd($data->last_enrolment->student_id);
            $reciver_id = $data->last_enrolment->student_id;
            $profile_id = $reciver_id;
            // dd($profile_id);
            $request->merge(['member_id' => $profile_id]);
        }

        //check if chat exists
        $chat_exits= Chat::where('parent_id', $sender_id)->with('members', function($q) use($profile_id) {
                $q->where('member_id', $profile_id);
        })->first();
        // dd($chat_exits);
        // $request->merge([
        //     'chat_id' => $chat_exits->id
        // ]);

        $chat_message_id = null;

        $receiverIds = $this->couseSlotService->getSlotsRecieverIds($request);
        $request->merge(['receiverIds' => $receiverIds]);
        
        if($chat_exits)
        {
            $request->merge([
                'chat_id' => $chat_exits->id
            ]);
            // dd($chat_exits->id);
            // dd("chat exists",  $chat_exits->id);

            // $chat_member = ChatMember::where('chat_id', $chat_exits->id)->first();
            $is_zoom_link = true;
            $request->merge(['message' => $request->zoom_link]);
            $result = $this->chatMessageService->addUpdateChatMessage($request, $chat_message_id, $is_zoom_link);
            dd($result['status']);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
            }
            $data = $result['data'];

            return $this->commonService->getSuccessResponse('New message saved Success', $data);


        }

        // check profile type of the reciever
        $result = $this->profileService->getProfileById($profile_id);
        if(!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $reciever_profile_data = $result['data'];
        $reciever_profile_type = $reciever_profile_data->profile_type;
        // dd($data);

        // check chat exists or not
        if(null == $chat_id)
        {
            // $request->merge([
            //     'parent_id' => $sender_id,
            //     'type' => 'single'
            // ]);
            $result = $this->chatService->addUpdateChat($request, $chat_id);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
            }
            $data = $result['data'];
            $chat_id = $data->id;
        }

        // add chat members with member_reciever_id
        $chat_member_id = null;
        $request->merge([
            'member_id' => $reciver_id,
            'member_role' => $reciever_profile_type,
            'chat_id' => $chat_id
        ]);
        $result = $this->chatMemberService->addUpdateChatMember($request, $chat_member_id);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $data = $result['data'];

        // add chat members with member_parent_id
        $chat_member_id = null;
        $request->merge([
            'member_id' => $sender_id,
            'member_role' => $parent_profile_type,
            'chat_id' => $chat_id
        ]);
        $result = $this->chatMemberService->addUpdateChatMember($request, $chat_member_id);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $data = $result['data'];


        //chat_member_id
        $request->merge(['message' => $request->zoom_link]);
        $result = $this->chatMessageService->addUpdateChatMessage($request, $chat_member_id, $is_zoom_link = true);
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

    public function sendChatMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'chat_uuid' => 'exists:chats,uuid',
            'chat_message' => 'required',
            // 'reciever_uuid' => 'required_unless:chat_uuid,null|exists:profiles,uuid',
            'reciever_uuid' => 'exists:profiles,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and get reciver ID
        if(isset($request->reciever_uuid) && ('' != $request->reciever_uuid)){
            $request->merge(['profile_uuid' => $request->reciever_uuid]);
            $result = $this->profileService->checkProfile($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
            }
            $reciever = $result['data'];
            $request->merge(['reciever_id' => $reciever->id]);
            $request->merge(['reciever_role' => $reciever->profile_type]);
            unset($request['profile_uuid']);
        }

        // set sender ID
        $request->merge([
            'sender_id' => $request->user()->profile_id
            , 'sender_role' => $request->user()->profile_type
        ]);

        // case chat uuid is not given
        if(!isset($request->chat_uuid) || ('' == $request->chat_uuid)){
            // validate if existing chat exists between sender and reciever
            $result = $this->chatService->getExistingChat($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
            }
            $existingChat = $result['data'];
            if(null != $existingChat){ // existing chat
                $request->merge([
                    'chat_uuid' => $existingChat->uuid
                    , 'chat_id' => $existingChat->id
                ]);
            }
        }
        else{
            $result = $this->chatService->checkChat($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
            }
            $chat = $result['data'];
            $request->merge([
                'chat_uuid' => $chat->uuid
                , 'chat_id' => $chat->id
            ]);
        }

        // start storing and processing data in model and db
        // \DB::beginTransaction();
        $request->merge(['message' => $request->chat_message]);
        if (!isset($request->chat_uuid) || ('' == $request->chat_uuid)) { // new chat

            // validate and get parent ID
            $request->merge([
                'parent_id' => $request->user()->profile_id,
            ]);

            // add|update Chat model
            $result = $this->chatService->addUpdateChat($request);
            if (!$result['status']) {
                \DB::rollback();
                return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
            }
            $chat = $result['data'];
            $request->merge(['chat_id' => $chat->id]);

            // add members
            $request->merge(['member_id' => $request->reciever_id, 'member_role' => $request->reciever_role]);
            $result = $this->chatMemberService->addUpdateChatMember($request);
            if (!$result['status']) {
                \DB::rollback();
                return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
            }
            $memberReciver = $result['data'];

            // update members stats
            $result = $this->chatMemberService->incrementMemberUnreadCount($request);
            if (!$result['status']) {
                \DB::rollback();
                return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
            }

            // add sender as member
            $request->merge(['member_id' => $request->sender_id, 'member_role' => $request->sender_role]);
            $result = $this->chatMemberService->addUpdateChatMember($request);
            if (!$result['status']) {
                \DB::rollback();
                return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
            }
            $memberSender = $result['data'];

            $request->merge(['members_count' => 2]);
            $result = $this->chatService->updateChatStats($request, $request->chat_id);
            if (!$result['status']) {
                \DB::rollback();
                return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
            }
            $chat = $result['data'];
            // dd($chat);
        }
        else{ // existing chat

            $result = $this->chatService->addUpdateChat($request, $request->chat_id);
            if (!$result['status']) {
                \DB::rollback();
                return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
            }
            $chat = $result['data'];

            // get recivers

            if('single' == $chat->type){
                $reciever = $chat->otherMembers[0]->profile;
            }
            else{
                $reciever = $chat->otherMembers[0]->profile;
            }
            // dd($reciever);

            $request->merge(['reciever_id' => $reciever->id]);
            $request->merge(['reciever_role' => $reciever->profile_type]);

            // update members stats
            $result = $this->chatMemberService->incrementMemberUnreadCount($request);
            if (!$result['status']) {
                \DB::rollback();
                return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
            }
        }

        // send message
        $result = $this->chatMessageService->addUpdateChatMessage($request);
        if (!$result['status']) {
            \DB::rollback();
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $chatMessage = $result['data'];

        // update chat last message ID
        $request->merge(['last_message_id' => $chatMessage->id]);
        $result = $this->chatService->updateChatStats($request, $request->chat_id);
        if (!$result['status']) {
            \DB::rollback();
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $chat = $result['data'];

        \DB::commit();
        return $this->commonService->getSuccessResponse('Success', $chat);
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
