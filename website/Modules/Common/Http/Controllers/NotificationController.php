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
        dd($apiResponse);

        if($request->getMethod()== 'GET')
        {
           
        }
        return view('common::notifications');
    }
}
