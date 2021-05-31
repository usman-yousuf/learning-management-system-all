<?php

namespace Modules\Chat\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Chat\Entities\Chat;
use Modules\Chat\Entities\ChatMessage;

class ChatMessageService
{

    /**
     * Check if an Chat Message Exists given ID
     *
     * @param Integer $id
     * @return void
     */
    public function getChatMessageById($id)
    {
        $model =  ChatMessage::where('id', $id)->first();
        if(null == $model){
            return getInternalErrorResponse('No Chat Message Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check and fetch and Chat Message against given ID
     *
     * @param Integer $id
     * @return void
     */
    public function checkChatMessageById($id)
    {
        $model =  ChatMessage::where('id', $id)->first();
        if(null == $model){
            return getInternalErrorResponse('No Chat Message Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check if an Chat Message Exists against given $request->chat_uuid
     *
     * @param Request $request
     * @return void
     */
    public function checkChatMessage(Request $request)
    {
        $model = ChatMessage::where('uuid', $request->chat_message_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No Chat Message Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Get an Chat Message against given UUID
     *
     * @param Request $request
     * @return void
     */
    public function getChatMessage(Request $request)
    {
        // dd($request->all());
        $model = ChatMessage::where('uuid', $request->chat_message_uuid)->first();
        return getInternalSuccessResponse($model);
    }

    /**
     * Delete an Chat Message by given UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteChatMessage(Request $request)
    {
        $model = ChatMessage::where('uuid', $request->chat_message_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No Chat Message Found', [], 404, 404);
        }

        try{
            $model->delete();
        }
        catch(\Exception $ex)
        {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode(), 500);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Get ChatMessage based on filters
     *
     * @param Request $request
     * @return void
     */
    public function getChatMessages(Request $request)
    {
        $models = ChatMessage::orderBy('created_at');

        //chat_message_id
        if(isset($request->chat_message_id) && ('' != $request->chat_message_id)){
            $models->where('id', $request->chat_message_id);
        }

        // sender_id 
        if(isset($request->sender_id) && ('' != $request->sender_id)){
            $models->where('sender_id', $request->sender_id);
        }

        // chat_id  
        if(isset($request->chat_id ) && ('' != $request->student_query_uuid)){
            $models->where('chat_id ', $request->student_query_uuid);
        }


        // message
        if (isset($request->message) && ('' != $request->message)) {
            $models->where('message', 'LIKE', "%{$request->message}%");
        }

        // title
        if (isset($request->title) && ('' != $request->title)) {
            $models->where('title', 'LIKE', "%{$request->title}%");
        }

        $cloned_models = clone $models;
        if(isset($request->offset) && isset($request->limit)){
            $models->offset($request->offset)->limit($request->limit);
        }

        $data['chat_messages'] = $models->get();
        $data['total_count'] = $cloned_models->count();

        return getInternalSuccessResponse($data);
    }

    /**
     * Add|Update Chat Message
     *
     * @param Request $request
     * @param Integer $chat_message_id
     * @return void
     */
    public function addUpdateChatMessage(Request $request, $chat_message_id = null)
    {
        if (null == $chat_message_id) {
            $model = new ChatMessage();
            $model->uuid = \Str::uuid();
            $model->created_at = date('Y-m-d H:i:s');
        } else {
            $model = ChatMessage::where('id', $chat_message_id)->first();
        }
        $model->updated_at = date('Y-m-d H:i:s');
        $model->sender_id = $request->sender_id;
        $model->chat_id = $request->chat_id;
        $model->message = $request->message;

        try {
            $model->save();
            $model = ChatMessage::where('id', $model->id)->first();
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }
}
