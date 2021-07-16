<?php

namespace Modules\Common\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Common\Http\Controllers\API\NotificationsController;
use Modules\Common\Services\CommonService;

class NotificationController extends Controller
{
    private $commonService;
    private $notificationCtrlObj;

    public function __construct(
        CommonService $commonService,
        NotificationsController $notificationCtrlObj
    ) {
        $this->commonService = $commonService;
        $this->notificationCtrlObj = $notificationCtrlObj;
    }

    /**
     * List All notifications against current User
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $notifiCtrlObj = $this->notificationCtrlObj;

        // $request->merge(['is_activity' => 0]);
        $apiResponse = $notifiCtrlObj->getProfileNotifications($request)->getData();
        // dd($apiResponse);

        $data = $apiResponse->data;
        // dd($data);
        if($request->getMethod() =='GET'){
            $data->requestFilters = [];
            if(!$apiResponse->status){
                return abort(500, 'Smething went wrong');
            }
        }
        else{
            $data->requestFilters = $request->all();
        }
        // dd($data);
        return view('common::notifications', ['data' => $data]);
    }

    /**
     * mark a notification a Notification
     *
     * @param [type] $uuid
     * @param Request $request
     * @return void
     */
    public function read($uuid, Request $request)
    {
        $request->merge(['notification_uuid' => $uuid,
        'is_read' => 1]);
        $markReadCtrlObj = $this->notificationCtrlObj;
        
        // dd($request->all());

        $apiResponse = $markReadCtrlObj->markNotificationRead($request)->getData();
        //  dd($apiResponse);

        return back();
    }

    /**
     * Delete a Notification
     *
     * @param [type] $uuid
     * @param Request $request
     * @return void
     */
    public function delete($uuid, Request $request)
    {
        $request->merge(['notification_uuid' => $uuid]);
        $markDeleteCtrlObj = $this->notificationCtrlObj;

        $apiResponse = $markDeleteCtrlObj->deleteNotification($request)->getData();
        //  dd($apiResponse);

        if ($apiResponse->status) {
            $data = $apiResponse->data;
            return $this->commonService->getSuccessResponse('Notification Deleted Successfully', $data);
        }
        return json_encode($apiResponse);
    }
}
