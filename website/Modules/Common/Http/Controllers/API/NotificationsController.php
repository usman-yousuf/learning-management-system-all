<?php

namespace Modules\Common\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Modules\Common\Services\CommonService;
use Modules\Common\Services\NotificationService;
use Modules\User\Services\ProfileService;
class NotificationsController extends Controller
{
    private $commonService;
    private $notificationService;
    private $profileService;

    public function __construct(CommonService $commonService, NotificationService $notificationService, ProfileService $profileService)
    {
        $this->commonService = $commonService;
        $this->notificationService = $notificationService;
        $this->profileService = $profileService;
    }


    /**
     * get Profile Notifications
     *
     * @param Request $request
     *
     * @return void
     */
    public function getProfileNotifications(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_uuid' => 'string|exists:profiles,uuid',
            'is_read' => 'integer',
            'is_activity' => 'integer',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }
        if(!isset($request->is_activity)){
            $request->merge(['is_activity' => false]);
        }

        $result = $this->notificationService->getProfileNotifications($request);
        if(!$result['status'])
        {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $profile_notifications = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $profile_notifications);
    }

    public function addUpdateNotificationPermission(Request $request)
    {
        $user_id = ($request->user_id) ? $request->user_id : $request->user()->profile_id;

        if(isset($request['id']))
        {
            $notiPermission = NotificationPermission::find($request->id);

            $notiPermission->update([
                'is_noti_chat' => $request['is_noti_chat'],
                'is_noti_saved_price' => $request['is_noti_saved_price'],
                'is_noti_new_item' => $request['is_noti_new_item'],
                'is_noti_discount' => $request['is_noti_discount'],
                'is_noti_service_purchase' => $request['is_noti_service_purchase'],
                'is_noti_item_purchase' => $request['is_noti_item_purchase'],
                'is_noti_post_like' => isset($request['is_noti_post_like']) ? $request['is_noti_post_like'] : 1,
                'is_noti_post_comment' => isset($request['is_noti_post_comment']) ? $request['is_noti_post_comment'] : 1,
                'is_noti_post_save' => isset($request['is_noti_post_save']) ? $request['is_noti_post_save'] : 1,
                'is_noti_product_save' => isset($request['is_noti_product_save']) ? $request['is_noti_product_save'] : 1,
                'updated_at' =>Carbon::now(),
            ]);
        }
        else
        {
            $notiPermission = new NotificationPermission;
            $notiPermission->user_id  = $request->user_id;
            $notiPermission->is_noti_chat = $request->is_noti_chat;
            $notiPermission->is_noti_saved_price = $request->is_noti_saved_price;
            $notiPermission->is_noti_new_item = $request->is_noti_new_item;
            $notiPermission->is_noti_discount = $request->is_noti_discount;
            $notiPermission->is_noti_service_purchase = $request->is_noti_service_purchase;
            $notiPermission->is_noti_item_purchase = $request->is_noti_item_purchase;
            $notiPermission->is_noti_post_like = $request->is_noti_post_like;
            $notiPermission->is_noti_post_comment = $request->is_noti_post_comment;
            $notiPermission->is_noti_post_save = $request->is_noti_post_save;
            $notiPermission->is_noti_product_save  = $request->is_noti_product_save ;
            $notiPermission->save();
        }



        $data['Notification_Permission'] = NotificationPermission::where('id', $notiPermission->id)->first();

        return sendSuccess("User Notifications Permissions", $data);
    }

    /**
     * get un_read notfications by reciever_id
     *
     * @param Request $request
     * @return void
     */
    public function getUnReadNotificationCount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_uuid' => 'string|exists:profiles,uuid',
            'is_read' => 'integer',
            'is_activity' => 'integer',

        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        //process reciever_uuid to reciever_id
        $result = $this->profileService->getProfile($request);
        if(!$result['status'])
        {
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $profile_id = $result['data'];
        //merge profile_uuid to reciever_id
        $request->merge(['receiver_id' => $profile_id->id]);

        $result= $this->notificationService->getUnreadNotificationsCount($request);
        if(!$result['status'])
        {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $profile_notification = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $profile_notification);

    }

     /**
     * Delete notification by ID
     *
     * @param Request $request
     * @return void
     */
    public function deleteNotification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'notification_uuid' => 'string|exists:notifications,uuid',
            'is_activity' => 'integer'

        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        //get notification_id
        $result = $this->notificationService->checkNotification($request);
        if(!$result['status'])
        {
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $notification = $result['data'];
        $notification_id = $notification->id;


        $result = $this->notificationService->deleteNotificationById($notification_id, $request);
        if(!$result['status'])
        {
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $delete_notification = $result['data'];

        return $this->commonService->getSuccessResponse('Record Deleted Successfully', []);
    }

     /**
     * Mark Notification as read by ID
     *
     * @param Request $request
     * @return void
     */
    public function markNotificationRead(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'notification_uuid' => 'string|exists:notifications,uuid',
            'is_activity' => 'string',

        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }
        $result = $this->notificationService->checkNotification($request);
        if(!$result['status'])
        {
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $notification = $result['data'];
        $notification_id = $notification->id;
        // dd($notification_id);

        $result = $this->notificationService->markNotificationAsRead($notification_id, $request);
        if(!$result['status'])
        {
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $notification_merge = $result['data'];
        return $this->commonService->getSuccessResponse('Success', $notification_merge);
    }


    /**
     * Mark Profile Notifications as read by ID
     *
     * @param Request $request
     * @return void
     */
    public function markProfileNotificationsRead(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_uuid' => 'string|exists:profiles,uuid',
            'is_activity' => 'string'

        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }
        //process reciever_uuid to reciever_id
        $result = $this->profileService->getProfile($request);
        if(!$result['status'])
        {
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $profile = $result['data'];
        $reciever_id = $profile->id;

        $result = $this->notificationService->markProfileNotificationsAsRead($reciever_id, $request);
        if(!$result['status'])
        {
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $notification = $result['data'];
        return $this->commonService->getSuccessResponse('Success', $notification);
    }



     /**
     *Bulk  Delete notification by profiles or notification uuid
     *
     * @param Request $request
     * @return void
     */
    public function bulkDeleteNotification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_uuid' => 'required_without_all:notification_uuid|string|exists:profiles,uuid',
            'notification_uuid' => 'required_without_all:profile_uuid',
            'is_activity' => 'string'
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        //process reciever_uuid to reciever_id
        if(isset($request->profile_uuid) && ('' != $request->profile_uuid)){
            $result = $this->profileService->getProfile($request);
            if(!$result['status'])
            {
                return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
            }
            $profile = $result['data'];
            //merge profile id with reciever_id
            $request->merge(['reciever_id' =>  $profile->id]);
        }


        $notification_ids = array();
        if(isset($request->notification_uuid) && ('' !=$request->notification_uuid))
        {
            $result = $this->notificationService->processNotificationsUUID($request);
            if(!$result['status'])
            {
                return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
            }
            $notification_ids = $result['data'];

        }

      //   get notification_id
        //  $notification_id = array();
        //  $result;
        // foreach ($request->notification_uuid as $key => $value) {
        //     $request->merge(['notification_uuid' => $value]);
        //     $result = $this->notificationService->checkNotification($request);
        //     // dd($result['data']->id);

        //     if(!$result['status'])
        //     {
        //         return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        //     }
        //     $notification = $result['data'];
        //     $notification_id[] = $notification->id;
        //     $request->merge(['notification_ids' => $notification_id]);
        //     // dd($request->notification_id);
        // }

        // dd($notification_id);
        // var_dump($notification_id);

        $result = $this->notificationService->bulkDeleteNotifications($request, $notification_ids);
        if(!$result['status'])
        {
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $delete_notification = $result['data'];

        return $this->commonService->getSuccessResponse('Record Deleted Successfully', []);
    }



}
