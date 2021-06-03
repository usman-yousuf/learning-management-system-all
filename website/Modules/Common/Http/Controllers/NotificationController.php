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

    public function index(Request $request)
    {
        $notifiCtrlObj = $this->notificationCtrlObj;

        $apiResponse = $notifiCtrlObj->getNotificationsProfile($request)->getData();
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

    public function read($uuid, Request $request)
    {
        $request->merge(['notification_uuid' => $uuid,
                         'is_read' => 1]);
        $markReadCtrlObj = $this->notificationCtrlObj;

        $apiResponse = $markReadCtrlObj->markNotificationRead($request)->getData();
        //  dd($apiResponse);

        return back();
    }
    
    public function delete($uuid, Request $request)
    {
        $request->merge(['notification_uuid' => $uuid]);
        $markDeleteCtrlObj = $this->notificationCtrlObj;

        $apiResponse = $markDeleteCtrlObj->deleteNotification($request)->getData();
        //  dd($apiResponse);

        return back();
    }
}
