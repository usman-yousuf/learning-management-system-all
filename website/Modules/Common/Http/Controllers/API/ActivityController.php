<?php

namespace Modules\Common\Http\Controllers\API;

use DateInterval;
use DatePeriod;
use DateTime;
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

        // if($request->user()->profile->profile_type == 'teacher')
        // {
            $request->merge(['teacher_uuid' => $request->user()->profile->uuid]);
            $slotsResponse = $this->courseAPICtrlObj->getTeacherCourseSlots($request)->getData();
        // }
        // else if($request->user()->profile->profile_type == 'student')
        // {
        //     $request->merge(['student_uuid' => $request->user()->profile->uuid]);
        //     $slotsResponse = $this->courseAPICtrlObj->getTeacherCourseSlots($request)->getData();
        // }

        if (!$apiResponse->status || !$slotsResponse->status) {
            return $this->commonService->getGeneralErrorResponse('Something went wrong', $apiResponse->data);
        }
        $activities = ($apiResponse->data->notifications_count)? $apiResponse->data->notifications : [];
        $slots = ($slotsResponse->data->total_slots)? $slotsResponse->data->slots : [];

        $events = [];
        if(!empty($activities)){
            foreach ($activities as  $item) {
                $temp = [
                    'id' => \Str::uuid()
                    , 'title' => ('quizzez' == $item->ref_model_name)? $item->quiz->title : $item->assignment->title
                    , 'start' => ('quizzez' == $item->ref_model_name)? $item->quiz->due_date : $item->assignment->due_date
                    , 'end' => ('quizzez' == $item->ref_model_name)? $item->quiz->due_date : $item->assignment->due_date
                    , 'backgroundColor' => ('quizzez' == $item->ref_model_name)? '#2EAAE0' : '#8E4BB8'
                    , 'borderColor' => ('quizzez' == $item->ref_model_name) ? '#2EAAE0' : '#8E4BB8'
                    , 'textColor' => '#FFF'
                    , 'allDay' => false
                    , 'className' => ['calendar_event-s']
                    , 'extendedProps' => [
                        'url' => route('activity.get-activity', [$item->uuid])
                        , 'sender_name' => $item->sender->first_name . ' ' . $item->sender->last_name
                        , 'sender_uuid' => $item->sender->uuid
                        , 'sender_image' => getFileUrl($item->sender->profile_image)
                        , 'is_read' => $item->is_read
                        , 'ref_model_name' => $item->ref_model_name
                        , 'ref_model_uuid' => ('quizzez' == $item->ref_model_name) ? $item->quiz->uuid : $item->assignment->uuid
                        , 'ref_model_url' => ('quizzez' == $item->ref_model_name)? route('quiz.viewQuiz', [$item->quiz->uuid]) : null
                        , 'additional_ref_model_name' => $item->additional_ref_model_name
                        , 'additional_ref_model_uuid' => ('quizzez' == $item->ref_model_name)? $item->quiz->course->uuid : $item->assignment->uuid
                        , 'nature' => ('quizzez' == $item->ref_model_name)? 'quiz' : 'assignment'
                        // , 'has_past' => ('quizzez' == $item->ref_model_name)? $item->quiz->due_date : $item->assignment->due_date
                    ],
                ];

                if('quizzez' == $item->ref_model_name){
                    $temp['extendedProps']['quiz_type'] = $item->quiz->type;
                }

                $events[] = $temp;
            }
        }
        // dd($slots[0]);
        // $item = $slots[0];

        if (!empty($slots)) {
            foreach ($slots as  $item) {
                if($item->enrolments_count){
                    $chosenDates = getDatesInRangeWithGivenDays($item->slot_start, $item->slot_end, $item->day_nums);
                    foreach ($chosenDates as  $selectedDate) {
                        $temp = [
                            'id' => \Str::uuid()
                            , 'title' => $item->course->title
                            , 'start' => $selectedDate //$item->slot_start
                            , 'end' => $selectedDate
                            , 'backgroundColor' => '#70B547'
                            , 'borderColor' => '#70B547'
                            , 'textColor' => '#FFF'
                            , 'allDay' => false
                            , 'className' => ['calendar_event-s']
                            , 'extendedProps' => [
                                'ref_url' => route('course.view', [$item->course->uuid])
                                , 'sender_name' => $item->course->teacher->first_name . ' ' . $item->course->teacher->last_name
                                , 'sender_uuid' => $item->course->teacher->uuid
                                , 'sender_image' => getFileUrl($item->course->teacher->profile_image)
                                , 'nature' => 'course_slot'
                                , 'slot_start' => $item->slot_start
                                , 'slot_start' => $item->slot_end
                                , 'url' => route('course.get-slot', [$item->uuid])

                            ],
                        ];
                        $events[] = $temp;
                    }
                }
            }
        }


        // dd($activities, $slots, $events);
        $data['events'] = $events;

        // setup events

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
