<?php

namespace Modules\Common\Services;

use Illuminate\Http\Request;
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
            'sender'
        ];
    }

    /**
     * get Notifications for  profile
     *
     * @param Request $request
     * @return void
     */
    public function getProfileNotifications(Request $request)
    {
        $result = $this->profileService->getProfile($request);
        if(!$result['status']){
            return $result;
        }
        $profile = $result['data'];

        $request->merge([
            'receiver_id' => $profile->id,
            'is_read' => 0,
        ]);
        $result = $this->listNotifications($request);
        if(!$result['status']){
            return $result;
        }
        $listData = $result['data'];

        // return list of notifications
        return getInternalSuccessResponse($listData);
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
        $models = Notification::orderBy('created_at', 'DESC');

        // filter based on receiver_id
        if(isset($request->receiver_id) && ('' != $request->receiver_id)){
            $models->where('receiver_id', $request->receiver_id);
        }

        // filter based on read status
        if (isset($request->is_read) && ('' != $request->is_read)) {
            $models->where('is_read', $request->is_read);
        }

        // offset and limit
        $cloned_models = clone $models;
        if (isset($request->offset) && isset($request->offset)) {
            $models->offset($request->offset)->limit($request->limit);
        }

        $models = $models->get();

        $total_models = $cloned_models->count();
        foreach ($models as $index => $model) {
            $relations = $this->relations;
            switch ($model->noti_model) {
                case 'appointments':
                    $relations = array_merge($relations, [
                        'appointment'
                    ]);
                    break;

                case 'chats':
                    $relations = array_merge($relations, [
                        'chat'
                    ]);
                    break;

                case 'reviews':
                    $relations = array_merge($relations, [
                        'review'
                    ]);
                    break;

                default:
                    # code...
                    break;
            }
            $models[$index] = Notification::where('id', $model->id)->with($relations)->first();
            $models[$index]->update(['is_read' => (int)true]); // update is_read status
        }
        $data = [
            'models' => $models,
            'total_models' => $total_models,
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
    public function deleteNotificationById($notification_id)
    {
        $model = Notification::where('id', $notification_id)->first();
        if(null != $model){
            try{
                $model->forceDelete();
            }
            catch(\Exception $ex){
                return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
            }
        }

        return getInternalSuccessResponse();

    }

    /**
     * Mark Notification as Read
     *
     * @param [type] $notification_id
     * @return void
     */
    public function markNotificationAsRead($notification_id)
    {
        $model = Notification::where('id', $notification_id)->first();
        if(null == $model){
            return getInternalErrorResponse('Record Not Found', [], 404, 404);
        }

        $model->is_read = 1;
        try{
            $model->save();
        }
        catch(\Exception $ex){
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }

        return getInternalSuccessResponse();
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
            $noti->sender_id = $request->sender_id;
            $noti->receiver_id = $id;
            $noti->type_id = $request->notification_model_id;
            $noti->noti_model = $request->notification_model;
            $noti->noti_type = $request->notification_type;
            $noti->noti_text = $request->notification_text;

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
            , 'notification_model' => $request->notification_model
            , 'notification_text' => $request->notification_text
            , 'sender_id' => $request->sender_id
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
