<?php

namespace Modules\Chat\Services;

use Illuminate\Http\Request;
use Modules\Chat\Entities\Chat;
use Modules\Chat\Entities\ChatMember;
use Modules\Course\Services\CourseDetailService;
use Modules\Student\Services\StudentCourseEnrollmentService;
use Modules\User\Services\ProfileService;

class ChatService
{
    private $profileService;
    private $courseService;
    private $enrollmentService;

    public  function __construct(ProfileService $profileService, CourseDetailService $courseService, StudentCourseEnrollmentService $enrollmentService)
    {
        $this->profileService = $profileService;
        $this->courseService = $courseService;
        $this->enrollmentService = $enrollmentService;
    }

    /**
     * Chatted User List (Chats list basically)
     *
     * @param Integer $id
     * @return void
     */
    public function chattedIndividualUserList(Request $request)
    {
        // dd($request->all());
        $profile_id = $request->profile_id;

        // DB::enableQueryLog();
        $my_chats = Chat::with(['members.profile', 'lastMessage', 'messages', 'messages.chat'])
        ->where('type', 'single')
        ->whereHas('members', function ($query) use ($profile_id, $request) {
            if (isset($request->keywords) && ('' != $request->keywords)) {
                $query->whereHas('profile', function ($subQuery) use ($request) {
                    // dd($request->keywords);
                    $subQuery->where('first_name', 'LIKE', "%{$request->keywords}%");
                });
            } else {
                $query->whereIn('member_id', [$profile_id]);
            }
            // $query->havingRaw('COUNT(*) = 2');
        })
        ->get();

        // setup profile and lastMessage
        if($my_chats->count()){
            foreach ($my_chats as $chat) {
                if($chat->members->count()){
                    foreach($chat->members as $index => $member){
                        if($member->member_id == $profile_id){
                            unset($chat->members[$index]);
                        }
                    }
                }
            }
        }

        // dd('my_chats', $my_chats);
        // dd(DB::getQueryLog());

        $data['chats'] = $my_chats;
        $data['total_chats'] = $my_chats->count();


        return getInternalSuccessResponse($data);
    }

    /**
     * List of users I have not chatted with yet
     *
     * @param Request $request
     *
     * @return void
     */
    public function NotChattedIndividualUserList(Request $request)
    {
        // $my_chats = Chat::with('lastMessage', 'members')->whereHas('members', function ($query) use ($request) {
        //     $query->where('member_id', $request->profile_id)
        //         ->where('is_deleted', false);
        // })->get();

        $profile_id = $request->profile_id;
        $my_chats = Chat::with('members.profile')
        ->where('type', 'single')
        ->whereHas('members', function ($query) use ($profile_id, $request) {
            if (isset($request->keywords) && ('' != $request->keywords)) {
                $query->whereHas('profile', function ($subQuery) use ($request) {
                    // dd($request->keywords);
                    $subQuery->where('first_name', 'LIKE', "%{$request->keywords}%");
                });
            } else {
                $query->whereIn('member_id', [$profile_id]);
            }
            // $query->havingRaw('COUNT(*) = 2');
        })->get();

        // dd($my_chats, 'fsdfjksd');
        $ignoredProfiles = [];
        foreach ($my_chats as $chat) {
            foreach ($chat->members as $member) {
                // array_push($members, $member->member_id);
                $ignoredProfiles[] = $member->member_id;
            }
        }
        $ignoredProfiles = array_unique($ignoredProfiles);
        // dd($ignoredProfiles);

        // dd($ignoredProfiles);
        if($request->profile_type == 'student'){
            $result = $this->enrollmentService->getEnrolledCourseTeachersId($request);
            if (!$result['status']) {
                return $result;
            }
            $teacherIds = $result['data'];
            unset($request['profile_type']);
            $leftIds =  array_diff($teacherIds, $ignoredProfiles);
            $request->merge(['bulk_profile_ids' => $leftIds]);
        } elseif ($request->profile_type == 'teacher') {
            // dd('its ateacher side', $request->all());
            $result = $this->courseService->getTeacherStudentsId($request);
            if (!$result['status']) {
                return $result;
            }
            $studentIds = $result['data'];
            unset($request['profile_type']);
            $leftIds =  array_diff($studentIds, $ignoredProfiles);
            $request->merge(['bulk_profile_ids' => $leftIds]);

            // dd($ignoredProfiles, $studentIds, $leftIds, $request->all());
        }

        // $request->merge(['ignored_profile_ids' => $ignoredProfiles]);
        // dd($request->all());
        $data['profiles'] = [];
        $data['total_profiles'] = 0;
        if(!empty($request->bulk_profile_ids)){
            $result = $this->profileService->listProfiles($request);
            if (!$result['status']) {
                return $result;
            }
            $data['profiles'] = $result['data']['models'];
            $data['total_profiles'] = $result['data']['total_models'];
        }

        return getInternalSuccessResponse($data);
        // $users = Profile::where('user_id', '!=', $request->user()->id)->whereNotIn('id', $members)->where('is_approved', 1);
        // if ($request->search)
        //     $users->where('name', 'LIKE', '%' . $request->search . '%');
        // $data['users'] = $users->get();
    }

    /**
     * Check if an Chat Exists given ID
     *
     * @param Integer $id
     * @return void
     */
    public function getChatById($id)
    {
        $model =  Chat::where('id', $id)->first();
        if(null == $model){
            return getInternalErrorResponse('No Chat Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
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
        $model = Chat::where('uuid', $request->chat_uuid)->with(['messages'])->first();
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
        // dd($request->all());
        if (null == $chat_id) {
            $model = new Chat();
            $model->uuid = \Str::uuid();
            $model->created_at = date('Y-m-d H:i:s');
        } else {
            $model = Chat::where('id', $chat_id)->first();
        }
        $model->updated_at = date('Y-m-d H:i:s');
        $model->parent_id = $request->parent_id;

        if(isset($request->last_message_id) && ('' != $request->last_message_id))
        {
            $model->last_message_id  = $request->last_message_id ;
        }

        if(isset($request->title) && ('' != $request->title))
        {
            $model->title = $request->title;
        }

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
