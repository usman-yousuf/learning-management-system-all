<?php

namespace Modules\Chat\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Chat\Entities\Chat;
use Modules\Chat\Entities\ChatMember;
use Modules\User\Services\ProfileService;

class ChatStatsService
{

    private $profileService;

    public  function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    /**
     * Chatted User List
     *
     * @param Integer $id
     * @return void
     */
    public function chattedIndividualUserList(Request $request)
    {
        // dd($request->profile_id);
        $profile_id = $request->profile_id;
        // DB::enableQueryLog();
        $chats = Chat::with('members.profile')
            ->where('type', 'single')
            ->whereHas('members', function ($query) use ($profile_id, $request) {
                if(isset($request->keywords) && ('' != $request->keywords)){
                    $query->whereHas('profile', function($subQuery) use ($request){
                        // dd($request->keywords);
                       $subQuery->where('first_name', 'LIKE', "%{$request->keywords}%");
                    });
                }
                else{
                    $query->whereIn('member_id', [$profile_id]);
                }
                // $query->havingRaw('COUNT(*) = 2');
            })->get();
            // dd(DB::getQueryLog());

            if(!$chats->count())
            {
                return getInternalErrorResponse('No Chat Found', [], 404, 404);
            }
            dd($chats);
            $chat_members = ChatMember::where('chat_id', $chat->id);
                $chat_members->whereNotIn('member_id', [$profile_id]);
            $chat_members->get();

            $member_ids = [];
            foreach ($chat_members as $item) {
                $member_ids[] = $item->member_id;
            }
            $request->merge(['bulk_profile_ids' => $member_ids]);
            // $request->merge(['ignored_profile_ids' => $member_ids]);
            $result = $this->profileService->listProfiles($request);
            if(!$result['status'])
            {
                return $result;
            }
            $data['profiles'] = $result['data']['models'];
            $data['total_profiles'] = $result['data']['total_models'];
            return getInternalSuccessResponse($data);
    }

    /**
     * Check and fetch and Chat against given ID
     *
     * @param Integer $id
     * @return void
     */
    public function checkChatById($id)
    {
        $model =  Chat::where('id', $id)->first();
        if(null == $model){
            return getInternalErrorResponse('No Chat Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check if an Chat Exists against given $request->chat_uuid
     *
     * @param Request $request
     * @return void
     */
    public function checkChat(Request $request)
    {
        $model = Chat::where('uuid', $request->chat_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No Chat Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Get an Chat against given UUID
     *
     * @param Request $request
     * @return void
     */
    public function getChat(Request $request)
    {
        $model = Chat::where('uuid', $request->chat_uuid)->first();
        return getInternalSuccessResponse($model);
    }

    /**
     * Delete an Chat by given UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteChat(Request $request)
    {
        $model = Chat::where('uuid', $request->chat_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No Chat Found', [], 404, 404);
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
     * Get Chat based on filters
     *
     * @param Request $request
     * @return void
     */
    public function getChats(Request $request)
    {
        $models = Chat::orderBy('created_at');

        //chat_id
        if(isset($request->chat_id) && ('' != $request->chat_id)){
            $models->where('id', $request->chat_id);
        }

        // parent_id
        if(isset($request->parent_id) && ('' != $request->parent_id)){
            $models->where('parent_id', $request->parent_id);
        }

        // last_message_id
        if(isset($request->last_message_id) && ('' != $request->last_message_id)){
            $models->where('last_message_id', $request->last_message_id);
        }


        // type
        if (isset($request->type) && ('' != $request->type)) {
            $models->where('type', 'LIKE', "%{$request->type}%");
        }

        // title
        if (isset($request->title) && ('' != $request->title)) {
            $models->where('title', 'LIKE', "%{$request->title}%");
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
     * @param Integer $chat_id
     * @return void
     */
    public function addUpdateChat(Request $request, $chat_id = null)
    {
        if (null == $chat_id) {
            $model = new Chat();
            $model->uuid = \Str::uuid();
            $model->created_at = date('Y-m-d H:i:s');
        } else {
            $model = Chat::where('id', $chat_id)->first();
        }
        $model->updated_at = date('Y-m-d H:i:s');
        $model->parent_id = $request->parent_id;
        $model->last_message_id  = $request->last_message_id ;
        $model->title = $request->title;
        $model->type = $request->type;

        try {
            $model->save();
            $model = Chat::where('id', $model->id)->first();
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }
}
