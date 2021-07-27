<?php

namespace Modules\Common\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Common\Http\Controllers\API\ActivityController as APIActivityController;
use Modules\Common\Http\Controllers\API\NotificationsController;
use Modules\Common\Services\CommonService;
use Modules\Course\Http\Controllers\API\CourseDetailController;

class ActivityController extends Controller
{
    private $commonService;
    private $notificationCtrlObj;
    private $activityCtrlObj;
    // private $courseAPICtrlObj;

    public function __construct(
        CommonService $commonService,
        NotificationsController $notificationCtrlObj,
        APIActivityController $activityCtrlObj
        // CourseDetailController $courseAPICtrlObj
    ) {
        $this->commonService = $commonService;
        $this->notificationCtrlObj = $notificationCtrlObj;
        $this->activityCtrlObj = $activityCtrlObj;
        // $this->courseAPICtrlObj = $courseAPICtrlObj;
    }

    public function getActivityCalendarData(Request $request)
    {

    }

    /**
     * List All Activities against current User
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        // dd($request->all());
        $request->merge([
            'is_activity' => true,
        ]);
        $apiResponse = $this->activityCtrlObj->getActivityCalendarData($request)->getData();
        // dd($apiResponse);
        $data = $apiResponse->data;
        if($request->getMethod() =='GET'){
            $data->requestFilters = [];
            if(!$apiResponse->status){
                return view('common::errors.500', ['message' => 'Something went wrong']);
            }
        }
        else{
            $data->requestFilters = $request->all();
        }

        $data->events = json_encode($data->events);
        // dd($data);
        return view('common::activity_calendar', ['data' => $data]);
    }

    /**
     * Get A Single Activity/Notification details
     *
     * @param String $uuid
     * @param Request $request
     *
     * @return void
     */
    public function getActivity($uuid, Request $request)
    {
        $request->merge([
            'notification_uuid' => $uuid,
        ]);
        $ctrlObj = $this->notificationCtrlObj;
        $apiResponse = $ctrlObj->checkNotification($request)->getData();
        if ($apiResponse->status) {
            $data = $apiResponse->data;
            return $this->commonService->getSuccessResponse('Activity Fetched Successfully', $data);
        }
        // return json_encode($apiResponse);
        return $this->commonService->getProcessingErrorResponse($apiResponse->message, $apiResponse->data, $apiResponse->responseCode, $apiResponse->exceptionCode);

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
        $request->merge([
            'notification_uuid' => $uuid,
            'is_read' => 1
        ]);
        $markReadCtrlObj = $this->notificationCtrlObj;

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
        // return json_encode($apiResponse);
        return $this->commonService->getProcessingErrorResponse($apiResponse->message, $apiResponse->data, $apiResponse->responseCode, $apiResponse->exceptionCode);

    }
}
