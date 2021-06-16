<?php

namespace Modules\Common\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Modules\Common\Http\Controllers\API\NotificationsController;
use Modules\Common\Services\CommonService;
use Modules\Common\Services\NotificationService;
use Modules\Course\Http\Controllers\API\CourseDetailController;
use Modules\User\Services\ProfileService;

class ActivityController extends Controller
{
    private $commonService;
    private $notificationService;
    private $profileService;
    private $notifCtrlObj;
    private $courseAPICtrlObj;

    public function __construct(CommonService $commonService
        , NotificationService $notificationService
        , ProfileService $profileService
        , NotificationsController $notifCtrlObj
        , CourseDetailController $courseAPICtrlObj
    )
    {
        $this->commonService = $commonService;
        $this->notificationService = $notificationService;
        $this->profileService = $profileService;

        $this->notifCtrlObj = $notifCtrlObj;
        $this->courseAPICtrlObj = $courseAPICtrlObj;
    }

    public function getActivityCalendarData(Request $request)
    {
        $apiResponse = $this->notifCtrlObj->getProfileNotifications($request)->getData();

        $request->merge(['teacher_uuid' => $request->user()->profile->uuid]);
        $slotsResponse = $this->courseAPICtrlObj->getTeacherCourseSlots($request)->getData();

        if (!$apiResponse->status || !$slotsResponse->status) {
            return $this->commonService->getGeneralErrorResponse('Something went wrong', $apiResponse->data);
        }
        $activities = ($apiResponse->data->notifications_count)? $apiResponse->data->notifications : [];
        $slots = ($slotsResponse->data->total_slots)? $slotsResponse->data->slots : [];

        dd($activities, $slots);
        $data = [];

        // @foreach($data->notifications as $item)
        // {
        //     title : '{{ $item->sender->first_name . ' ' . $item->sender->last_name }}',
        //     start : '{{ (null != $item->start_date)? $item->start_date : $item->created_at }}',
        //     extendedProps: {
        //         url: "{{ route('activity.get-activity', [$item->uuid]) }}",
        //         sender_name : "{{ $item->sender->first_name . ' ' . $item->sender->last_name }}",
        //         sender_uuid : "{{ $item->sender->uuid }}",
        //         is_read : {{ $item->is_read }},
        //         ref_model_name: "{{ $item->ref_model_name }}",
        //         ref_model_uuid: "{{ $item->uuid }}",
        //         nature: "{{ (($item->ref_model_name == 'quizzes') && ($item->quiz->type == 'test'))? 'test' : 'assignment' }}"
        //     },

        //     @if ($item->end_date)
        //         end: '{{ $item->end_date }}',
        //     @endif
        // },
        // @endforeach
        return $this->commonService->getSuccessResponse('calendar data fetched successfully', $data);
    }
}
