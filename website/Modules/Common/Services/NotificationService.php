<?php

namespace Modules\Common\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Common\Entities\Notification;
use Modules\User\Services\ProfileService;

class NotificationService
{
    private $profileService;
    private $relations;

    public function __construct()
    {
        $this->profileService = new ProfileService();
        $this->relations = [
            'sender',
            'receiver'
        ];
    }

    /**
     * Check if an Notification Exists given UUID
     *
     * @param Request $request
     * @return void
     */
    public function checkNotification(Request $request)
    {
        $model = Notification::where('uuid', $request->notification_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No notification Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Get an Notification against given UUID
     *
     * @param Request $request
     * @return void
     */
    public function getNotification(Request $request)
    {
        $model = Notification::where('uuid', $request->notification_uuid)->first();
        return getInternalSuccessResponse($model);
    }

    /**
     * get Notifications for profile
     *
     * @param Request $request
     * @return void
     */
    public function getProfileNotifications(Request $request)
    {
        $profile_uuid = (isset($request->profile_uuid) && ('' != $request->profile_uuid)) ? $request->profile_uuid : $request->user()->profile->uuid;
        $request->merge(['profile_uuid' => $profile_uuid]);

        // validate profile
        $result = $this->profileService->getProfile($request);
        if (!$result['status']) {
            return $result;
        }
        $profile = $result['data'];

        // merge request params
        $request->merge([
            'receiver_id' => $profile->id,
            'is_read' => 0,
            'is_activity' => $request->is_activity,
        ]);

        // get profile notifications
        $result = $this->listNotifications($request);
        if (!$result['status']) {
            return $result;
        }
        $notificationData = $result['data'];

        return getInternalSuccessResponse($notificationData);
    }

    /**
     * get Unread Notifications Count
     *
     * @param Request $request
     * @return void
     */
    public function getUnreadNotificationsCount(Request $request)
    {
        $models = Notification::orderBy('created_at', 'DESC');
        // dd($request->receiver_id);
        if (isset($request->receiver_id) && ('' != $request->receiver_id)) {
            $models->where('receiver_id', $request->receiver_id);
        }
        else{
            $models->where('receiver_id', $request->user()->profile_id);
        }

        if (isset($request->is_read) && ('' != $request->is_read)) {
            $models->where('is_read', $request->is_read);
        } else {
            $models->where('is_read', (int)false);
        }

        if (isset($request->is_activity) && ('' != $request->is_activity)) {
            $models->where('is_activity', $request->is_activity);
        } else {
            $models->where('is_activity', (int)false);
        }

        return getInternalSuccessResponse($models->count());
    }

    /**
     * List Notifications nased on filters
     *
     * @param Request $request
     * @return void
     */
    public function listNotifications(Request $request)
    {
        // DB::enableQueryLog();
        $models = Notification::orderBy('created_at', 'DESC');
        // dd($request->receiver_id);
        $reciever_id = $request->receiver_id;
        // dd($reciever_id);
        // filter based on receiver_id
        // if(isset($request->receiver_id) && ('' != $request->receiver_id)){
            $models->where('receiver_id','=', $reciever_id);
        // }

        // filter based on read status
        if (isset($request->is_read) && ('' != $request->is_read)) {
            $models->where('is_read', $request->is_read);
        }

        // dd($request->all());
        // filter based on activity status
        if (isset($request->is_activity)) {
            $models->where('is_activity', '=', (int)$request->is_activity);
        }

        // dd((int)$request->is_activity);

        // offset and limit
        $cloned_models = clone $models;
        if (isset($request->offset) && isset($request->offset)) {
            $models->offset($request->offset)->limit($request->limit);
        }

        $models = $models->get();
        // dd($models);
        // dd(DB::getQueryLog());
        $total_models = $cloned_models->count();
        foreach ($models as $index => $model) {
            $relations = $this->relations;
            switch ($model->ref_model_name) {
                case 'courses':
                    $relations = array_merge($relations, [
                        'course'
                    ]);
                    break;

                case 'assignments':
                    $relations = array_merge($relations, [
                        'assignment'
                    ]);
                    break;

                default:
                    # code...
                    break;
            }
            $models[$index] = Notification::where('id', $model->id)->with($relations)->first();

            // $models[$index]->update(['is_read' => (int)true]); // update is_read status
        }
        $data = [
            'notifications' => $models,
            'notifications_count' => $total_models,
        ];

        // dd($data, $request->all());
        return getInternalSuccessResponse($data);

    }

    /**
     * Delete Notification by notification ID
     *
     * @param Integer $notification_id
     * @return void
     */
    public function deleteNotificationById($notification_id, Request $request)
    {
        // dd($request->all());
        $model = Notification::where('id', $notification_id)->first();

        if(null != $model){
            try{
                $model->delete();
                return getInternalSuccessResponse();
            }
            catch(\Exception $ex){
                return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
            }
        }
        else{
            return getInternalErrorResponse('No Record Found to delete', [], 404, 404);
        }
    }

    /**
     * Mark Notification as Read
     *
     * @param [type] $notification_id
     * @return void
     */
    public function markNotificationAsRead($notification_id, Request $request)
    {
        $model = Notification::where('id', $notification_id)->first();
        // dd($request->all());
        if((isset($request->is_activity) && ($request->is_activity == 1)) && $model->is_activity == 1){
            $model->is_read = 1;
            $model->save();
            return getInternalSuccessResponse();
        }

        if(!isset($request->is_activity) && $model->is_activity == 0)
        {
            $model->is_read = 1;
            $model->save();
            return getInternalSuccessResponse();
        }

        else {
            return getInternalErrorResponse('Record Not Found', [], 404, 404);
        }
        // if(null == $model){
        //     return getInternalErrorResponse('Record Not Found', [], 404, 404);
        // }

        // $model->is_read = 1;
        // try{
        //     $model->save();
        //     return getInternalSuccessResponse();
        // }
        // catch(\Exception $ex){
        //     return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        // }
    }

       /**
     * Mark Profile Notifications as Read
     *
     * @param [type] $notification_id
     * @return void
     */
    public function markProfileNotificationsAsRead($reciever_id, $request)
    {
        $model = Notification::where('receiver_id', $reciever_id);
        $models = clone $model;
        // dd(isset($request->is_activity) && ($request->is_activity == 1));
        if((isset($request->is_activity)) && ($request->is_activity == 1)) {
            $data =  $models->where('is_activity', 1)->update(['is_read'=> 1]);
            return getInternalSuccessResponse($data);
        }

        if(!isset($request->is_activity) && ('' == $request->is_activity))
        {
            $data =  $models->where('is_activity', 0)->update(['is_read'=> 1]);
            return getInternalSuccessResponse($data);
        }

        else {
            return getInternalErrorResponse('Record Not Found', [], 404, 404);
        }

        // $data = null;
        // // dd($models->get());
        // if(null == $model){
        //     return getInternalErrorResponse('Record Not Found', [], 404, 404);
        // }

        // try{
        //    $data =  $models->update(['is_read'=> 1]);
        // }
        // catch(\Exception $ex){
        //     return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        // }
        // return getInternalSuccessResponse($data);
    }


    /**
     * Bulk delete Notification (of profiles Or notification) as Read
     *
     * @param [type] $profile_uuid, $notification_uuid
     * @return void
     */
    public function bulkDeleteNotifications(Request $request, $notification_ids =null)
    {
        // dd($request->notification_id);
        $models = Notification::orderBy('created_at', 'DESC');
        if((isset($request->reciever_id) && ('' !=$request->reciever_id)) && (isset($request->is_activity) && ('' !=$request->is_activity))  )
        {
            // dd($models->where('receiver_id', $request->reciever_id)->where('is_activity', $request->is_activity)->get());
            $models->where('receiver_id', $request->reciever_id)->where('is_activity', $request->is_activity);
        }
        if(isset($request->reciever_id) && ('' !=$request->reciever_id) && $request->is_activity == 0)
        {
            dd(1);
            $models->where('receiver_id', $request->reciever_id)->where('is_activity', 0);
        }

        if((isset($notification_ids) && !empty($notification_ids)) && (isset($request->is_activity) && ('' !=$request->is_activity)))
        {
            // dd($notification_ids);
            $models->whereIn('id', $notification_ids)->where('is_activity', $request->is_activity);
        }

        if(isset($notification_ids) && !empty($notification_ids) && ((!isset($request->is_activity) && $request->is_activity == '')))
        {
            // dd($notification_ids);
            // dd( $models->whereIn('id', $notification_ids)->where('is_activity',0)->count());
            $models->whereIn('id', $notification_ids)->where('is_activity',0);
        }

        if($models->count() == 0){
                return getInternalErrorResponse('Record Not Found', [], 404, 404);
            }
        try{
            // dd($models->id);
            $models->delete();
            return getInternalSuccessResponse();
        }
        catch(\Exception $ex){
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }


    /**
     * Process notification_uuid arrays
     *
     * @param String $notification_uuid
     * @return void
     */
    public function processNotificationsUUID(Request $request)
    {
        // dd($request->all());
        $notification_id = array();
        foreach ($request->notification_uuid as $key => $value) {
            $request->merge(['notification_uuid' => $value]);
            $result = $this->checkNotification($request);

            if(!$result['status'])
            {
                return getInternalErrorResponse('No notification Found', [], 404, 404);
                // return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
            }
            $notification = $result['data'];
            $notification_id[] = $notification->id;
        }
        return getInternalSuccessResponse($notification_id);
    }



    /**
     * Add a new Notification to Application
     *
     * @param Request $request
     *
     * @return void
     */
    public function addUpdateNotification(Request $request)
    {
        $noti = [];
        foreach ($request->receivers as $id) {
            $noti = new Notification;
            $noti->uuid = \Str::uuid();
            $noti->sender_id = $request->sender_id;
            $noti->receiver_id = $id;
            $noti->ref_id = $request->notification_model_id;
            $noti->ref_model_name = $request->notification_model;
            $noti->noti_type = $request->notification_type;
            $noti->noti_text = $request->notification_text;

            if(isset($request->is_activity)){
                $noti->is_activity = (int)$request->is_activity;
            }

            if (isset($request->start_date) && ('' != $request->start_date)) {
                $noti->start_date = $request->start_date;
            }
            if (isset($request->end_date) && ('' != $request->end_date)) {
                $noti->end_date = $request->end_date;
            }

            if(isset($request->additional_ref_id) && ('' != $request->additional_ref_id) ){
                $noti->additional_ref_id = $request->additional_ref_id;
            }
            if (isset($request->additional_ref_model_name) && ('' != $request->additional_ref_model_name)) {
                $noti->additional_ref_model_name = $request->additional_ref_model_name;
            }

            try{
                $noti->save();
            }
            catch(\Exception $ex){
                return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
            }
        }

        if (!empty($request->receivers)) {
            return getInternalSuccessResponse($noti);
        }
        return getInternalSuccessResponse([], 'No Recievers found.');
    }




    /**
     * Send notifications to other users and save in db
     *
     * @param Array $receiverIds
     * @param Request $request
     * @param boolean $canSendNotification
     * @return void
     */
    public function sendNotifications($receiverIds, $request, $canSendNotification = true )
    {
        $request->merge([
            'receivers' => $receiverIds
            , 'notification_type' => $request->notification_type
            , 'notification_model_id' => $request->notification_model_id
            , 'notification_model_uuid' => $request->notification_model_uuid
            , 'notification_model' => $request->notification_model
            , 'notification_text' => $request->notification_text
            , 'sender_id' => $request->user()->profile->id
            , 'sender_uuid' => $request->user()->profile->uuid

            , 'additional_ref_uuid' => $request->additional_ref_uuid
            , 'additional_ref_id' => $request->additional_ref_id
            , 'additional_ref_model_name' => $request->additional_ref_model_name
        ]);
        $result = $this->profileService->getProfileById($request->sender_id);
        if(!$result['status']){
            return $result;
        }
        $sender = $result['data'];
        $request->notification_text = $request->notification_text;

        if($canSendNotification){
            $result = $this->addUpdateNotification($request);

            if(!$result['status']){
                return $result;
            }

            $noti = $result['data'];
            $noti_data = [];
            if(null != $noti){
                // print_array($noti->toJson(), true);

                $noti_data = $noti->getAttributes();
                // $relationKeys = ['sender'];
                $noti_data = array_merge($noti_data, [
                    'sender_uuid' => $noti->sender->uuid,
                    'sender_name' => $noti->sender->first_name . ' ' . $noti->sender->last_name,
                ]);


                if($request->notification_model == 'course'){
                    $noti_data = array_merge($noti_data, [
                        'noti_model_uuid' => $noti->course->uuid
                    ]);
                }
                elseif ($request->notification_model == 'chats'){
                    $noti_data = array_merge($noti_data, [
                        'noti_model_uuid' => $noti->chat->uuid,
                    ]);
                }
                // dd($noti_data);
                // print_array($noti->latest()->with($relationKeys)->first()->toJson(), true);
            }

            // send push notification to all recievers
            if(!empty($receiverIds)){
                // $result = $this->sendPushNotification($receiverIds, $sender->first_name . ' ' . $sender->last_name . ' ' . $request->notification_text, $noti->all()->toJson());
                $result = $this->sendPushNotification($receiverIds, $sender->first_name . ' ' . $sender->last_name . ' ' . $request->notification_text, json_encode($noti_data));
                if(!$result['status']){
                    return $result;
                }
                $noti_status = $result;
            }
            return getInternalSuccessResponse('No Notifications created since there are no recievers');
        }
        return getInternalErrorResponse('You are not Allowed to send notifications');
    }






    /**
     * Send Push notification to other users
     *
     * @param Array $ids[]
     * @param String $text
     * @param string $data
     * @return void
     */
    public function sendPushNotification($ids, $text, $data)
    {
        $filters = [];
        foreach ($ids as $i => $id) {
            if ($i > 0)
                array_push($filters, ["operator" => "OR"]);
            array_push($filters, ["field" => "tag", "key" => "user_id", "relation" => "=", "value" => $id]);
        }

        // \Log::info($filters);

        $content = array( "en" => $text);
        $att1 = str_replace(':', '=>', $data);

        $fields = array(
            'app_id' => config('onesignal.app_id'),
            'filters' => $filters,
            'data' => array("data" => array($att1)),
            'contents' => $content
        );
        $fields = json_encode($fields);
        // Log::info($fields);

        $curlResponse = $this->sendCurlRequest($fields);
        // dd($curlResponse);
        return getInternalSuccessResponse();
    }

    /**
     * Make a CURL request to onesignal API
     *
     * @param Array $fields[]
     * @return void
     */
    public function sendCurlRequest($fields)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Basic ' . config('onesignal.rest_api_key')
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);

        // \Log::info('NotificationsController => function sendPushNotification');
        \Log::info($response);

        return $response;
    }

    public function sendSilentNotifications($ids, $user_id, $text, $data)
    {
        $filters = [];
        array_push($filters, ["field" => "tag", "key" => "user_id", "relation" => "=", "value" => "$user_id"]);
        foreach ($ids as $i => $id) {
            array_push($filters, ["operator" => "OR"]);
            array_push($filters, ["field" => "tag", "key" => "viewer_id", "relation" => "=", "value" => "$id"]);
        }

        \Log::info($filters);

        $fields = array(
            'app_id' => config('onesignal.app_id'),
            'included_segments' => array('Active Users'),
            'filters' => $filters,
            'data' => $data,
            'content_available' => true
        );

        $fields = json_encode($fields);

        $this->sendCurlRequest($fields);
    }






    /**
     * Check appointment Accptance status
     *
     * find all models whoes status is not rejected
     *
     * @param Integer $appointment_id
     * @return void
     */
    public function checkAppointmentAcceptanceStatus($appointment_id)
    {
        $models = Notification::
            where('noti_model', 'appointment')
            ->where('type_id', $appointment_id)
            ->where('noti_type', '<>', 'reject_appointment')
            ->get();

        return getInternalSuccessResponse($models->count());
    }





    public function sendChatMessageNotifications(Request $request, $profiles, $sender)
    {
        if($profiles->count()){
            $receiverIds = [];
            foreach ($profiles as $receiver) {
                if($receiver->member_id != $sender->id){
                    $receiverIds[] = $receiver->member_id;
                }
            }
            // dd($receiverIds, $profiles);
            // $this->sendNotifications()
            $notificationKey  = 'sent_chat_message';
            $request->merge([
                'notification_type' => $notificationKey,
                'notification_model' => 'chats',
                'notification_model_id' => $request->chat_id,
                'notification_text' => getNotificationText($sender->name, $notificationKey),
                'sender_id' => $sender->id
            ]);
            $result = $this->sendNotifications($receiverIds, $request, true);
            if(!$result['status']){
                return $result;
            }
            return getInternalSuccessResponse(null, 'Notifications Sent Successfully');
        }
        else{
            return getInternalErrorResponse('No receivers provided');
        }
    }
}
