<?php

namespace Modules\Chat\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Chat\Entities\ChatMember;

class ChatMemberService
{

    /**
     * Check if an Chat  Member Exists given ID
     *
     * @param Integer $id
     * @return void
     */
    public function getChatMemberById($id)
    {
        $model =  ChatMember::where('id', $id)->first();
        if(null == $model){
            return getInternalErrorResponse('No Chat  Member Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check and fetch and Chat Memberagainst given ID
     *
     * @param Integer $id
     * @return void
     */
    public function checkChatMemberById($id)
    {
        $model =  ChatMember::where('id', $id)->first();
        if(null == $model){
            return getInternalErrorResponse('No Chat MemberFound', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check if an Chat MemberExists against given $request->chat_uuid
     *
     * @param Request $request
     * @return void
     */
    public function checkChatMember(Request $request)
    {
        $model = ChatMember::where('uuid', $request->chat_member_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No Chat MemberFound', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Get an Chat Member against given UUID
     *
     * @param Request $request
     * @return void
     */
    public function getChatMember(Request $request)
    {
        $model = ChatMember::where('uuid', $request->chat_member_uuid)->first();
        return getInternalSuccessResponse($model);
    }

    /**
     * Delete an Chat Memberby given UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteChatMember(Request $request)
    {
        $model = ChatMember::where('uuid', $request->chat_member_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No Chat Member Found', [], 404, 404);
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
     * Get Chat Member based on filters
     *
     * @param Request $request
     * @return void
     */
    public function getChatMembers(Request $request)
    {
        $models = ChatMember::orderBy('created_at');

        //chat_member_id
        if(isset($request->chat_member_id) && ('' != $request->chat_member_id)){
            $models->where('id', $request->chat_member_id);
        }

        // chat_id  
        if(isset($request->chat_id ) && ('' != $request->chat_id )){
            $models->where('chat_id ', $request->chat_id );
        }

        // member_id  
        // dd($request->all());
        if(isset($request->member_id) && ('' != $request->member_id)){
            $models->where('member_id', $request->member_id);
        }


        // member_status
        if (isset($request->member_status) && ('' != $request->member_status)) {
            $models->where('member_status', '=', $request->member_status);
        }

        // member_role
        if (isset($request->member_role) && ('' != $request->member_role)) {
            $models->where('member_role', '=', $request->member_role);
        }

        $cloned_models = clone $models;
        if(isset($request->offset) && isset($request->limit)){
            $models->offset($request->offset)->limit($request->limit);
        }

        $data['chats'] = $models->get();
        $data['total_count'] = $cloned_models->count();

        return getInternalSuccessResponse($data);
    }

    /**
     * Add|Update Chats
     *
     * @param Request $request
     * @param Integer $chat_member_id
     * @return void
     */
    public function addUpdateChatMember(Request $request, $chat_member_id = null)
    {
        // dd($request->all());
        if (null == $chat_member_id) {
            $model = new ChatMember();
            $model->uuid = \Str::uuid();
            $model->created_at = date('Y-m-d H:i:s');
        } else {
            $model = ChatMember::where('id', $chat_member_id)->first();
        }
        $model->updated_at = date('Y-m-d H:i:s');
        $model->chat_id = $request->chat_id;
        $model->member_id  = $request->member_id;
        if(isset($request->member_status) && ('' != $request->member_status))
        {
            $model->member_status = $request->member_status;
        }
        $model->member_role = $request->member_role;

        try {
            $model->save();
            $model = ChatMember::where('id', $model->id)->first();
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }
}
