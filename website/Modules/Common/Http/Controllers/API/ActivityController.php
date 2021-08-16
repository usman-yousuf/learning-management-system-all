<?php

namespace Modules\Common\Http\Controllers\API;

use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Modules\Common\Http\Controllers\API\NotificationsController;
use Modules\Common\Services\CommonService;
use Modules\Common\Services\NotificationService;
use Modules\Course\Http\Controllers\API\CourseDetailController;
use Modules\Quiz\Http\Controllers\API\QuizController;
use Modules\User\Services\ProfileService;

class ActivityController extends Controller
{
    private $commonService;
    private $notificationService;
    private $profileService;
    private $notifCtrlObj;
    private $courseAPICtrlObj;
    private $quizControllerService;


    public function __construct(CommonService $commonService
        , NotificationService $notificationService
        , ProfileService $profileService
        , NotificationsController $notifCtrlObj
        , CourseDetailController $courseAPICtrlObj
        , QuizController $quizControllerService
    )
    {
        $this->commonService = $commonService;
        $this->notificationService = $notificationService;
        $this->profileService = $profileService;

        $this->notifCtrlObj = $notifCtrlObj;
        $this->courseAPICtrlObj = $courseAPICtrlObj;
        $this->quizControllerService = $quizControllerService;
    }

    /**
     * Get Activity Calendar data
     *
     * @param Request $request
     * @return void
     */
    public function getActivityCalendarData(Request $request)
    {
        $apiResponse = $this->notifCtrlObj->getProfileNotifications($request)->getData();
        $isStudent = false;
        if($request->user()->profile->profile_type == 'teacher')
        {
            $request->merge(['teacher_uuid' => $request->user()->profile->uuid]);
            $slotsResponse = $this->courseAPICtrlObj->getTeacherCourseSlots($request)->getData();
        }
        else if($request->user()->profile->profile_type == 'student')
        {
            $request->merge(['student_uuid' => $request->user()->profile->uuid]);
            $slotsResponse = $this->courseAPICtrlObj->getStudentCourseSlots($request)->getData();

            $isStudent = true;

            // $quiz = $this->quizControllerService->getQuizz($request)->getData();
        }

        if (!$apiResponse->status || !$slotsResponse->status) {
            return $this->commonService->getGeneralErrorResponse('Something went wrong', $apiResponse->data);
        }
        $activities = ($apiResponse->data->notifications_count)? $apiResponse->data->notifications : [];
        if ($request->user()->profile->profile_type == 'teacher'){
            $activities = array_merge($activities, $apiResponse->data->teacher_activities);
        }
        $slots = ($slotsResponse->data->total_slots)? $slotsResponse->data->slots : [];
        // dd($activities, $slots);

        $events = [];
        if(!empty($activities)){
            // print_array($activities);
            foreach ($activities as $index => $item) {
                // dd($item);
                if('quizzez' == $item->ref_model_name){
                    if(isset($item->quiz) && $item->quiz->questions_count < 1){
                        continue;
                    }
                }

                //check if teacher marked assignment
                $ignored_noti_types = [
                    'marked_assignment' // teacher has marked assignment
                ];
                if(in_array($item->noti_type, $ignored_noti_types)){
                    continue;
                }

                // if assignment uploaded true, it will hide the teacher created assignment
                // if('assignments' ==  $item->ref_model_name){
                //     if(($item->assignment->is_uploaded_assignment))
                //     {
                //         continue;
                //     }
                // }

                if ('quizzez' == $item->ref_model_name) {
                    $description = $item->quiz->description;
                    unset($item->quiz->description);
                    $item->quiz->description = str_replace(array("\n", "\r"), '', $description);
                    $item->course->description = str_replace("'", "\'", $description);
                    // $item->course->description = str_replace('"', '\"', $description);
                }

                if ('assignments' == $item->ref_model_name) {
                    $description = $item->assignment->description;
                    unset($item->assignment->description);
                    $item->assignment->description = str_replace(array("\n", "\r"), '', $description);
                    $item->course->description = str_replace("'", "\'", $description);
                    // $item->course->description = str_replace('"', '\"', $description);
                }
                if ('courses' == $item->ref_model_name) {
                    $description = $item->course->description;
                    unset($item->course->description);
                    $item->course->description = str_replace(array("\n", "\r"), '', $description);
                    $item->course->description = str_replace("'", "\'", $description);
                    // $item->course->description = str_replace('"', '\"', $description);
                    // you're
                }

                // print_array($item);
                $temp = [
                    // dd((('quizzez' == $item->ref_model_name)? $item->quiz->title : 'student_assignments' == $item->ref_model_name)? $item->student_assignment->teacher_assignment->title : $item->assignment->title),
                    'id' => Str::uuid()
                    // , 'title' => ('quizzez' == $item->ref_model_name)? $item->quiz->title : $item->assignment->title
                    , 'title' => ('quizzez' == $item->ref_model_name)? $item->quiz->title : (('student_assignments' == $item->ref_model_name) ?$item->student_assignment->student->first_name . '<br />' . $item->student_assignment->teacher_assignment->title : (('quiz_attempt_stats' == $item->ref_model_name)? $item->student_attempt->student->first_name .'<br />' .$item->student_attempt->quiz->title : $item->assignment->title)) // ? $item->s->teacher_assignment->title : null
                    // , 'start' => ('quizzez' == $item->ref_model_name)? $item->quiz->due_date : $item->assignment->due_date
                    , 'start' => ('quizzez' == $item->ref_model_name)? $item->quiz->due_date : (('student_assignments' == $item->ref_model_name) ? $item->student_assignment->teacher_assignment->due_date : (('quiz_attempt_stats' == $item->ref_model_name) ? $item->student_attempt->quiz->due_date : $item->assignment->due_date))  //$item->assignment->due_date ? $item->student_assignment->teacher_assignment->due_date : null
                    // , 'end' => ('quizzez' == $item->ref_model_name)? $item->quiz->due_date : $item->assignment->due_date
                    , 'end' => ('quizzez' == $item->ref_model_name)? $item->quiz->due_date : (('student_assignments' == $item->ref_model_name) ? $item->student_assignment->teacher_assignment->due_date : (('quiz_attempt_stats' == $item->ref_model_name) ? $item->student_attempt->quiz->due_date : $item->assignment->due_date)) //$item->quiz->due_date : $item->assignment->due_date): $item->student_assignment->teacher_assignment->due_date

                    , 'backgroundColor' => ('quizzez' == $item->ref_model_name)? '#2EAAE0' : (('quiz_attempt_stats' == $item->ref_model_name)? '#F78543' : (('student_assignments' == $item->ref_model_name)? '#b8860b' : '#8E4BB8'))
                    , 'borderColor' => ('quizzez' == $item->ref_model_name) ? '#2EAAE0' : (('quiz_attempt_stats' == $item->ref_model_name) ? '#F78543' : (('student_assignments' == $item->ref_model_name) ? '#b8860b' : '#8E4BB8'))
                    , 'textColor' => '#FFF'
                    , 'isStudent' => ($request->user()->profile->profile_type == 'student')? true : false
                    , 'allDay' => false
                    , 'className' => ['calendar_event-s']
                    , 'extendedProps' => [
                        'url' => route('activity.get-activity', [$item->uuid])
                        , 'sender_name' => $item->sender->first_name . ' ' . $item->sender->last_name
                        , 'sender_uuid' => $item->sender->uuid
                        , 'sender_image' => getFileUrl($item->sender->profile_image)
                        , 'is_read' => $item->is_read
                        , 'ref_model_name' => $item->ref_model_name
                        , 'ref_model' => ('quizzez' == $item->ref_model_name) ? $item->quiz : (('assignments' == $item->ref_model_name) ? $item->assignment : (('student_assignments' == $item->ref_model_name)? $item->student_assignment : (('quiz_attempt_stats' == $item->ref_model_name)? $item->student_attempt : 'orange')))
                        // , 'ref_model' => ('quizzez' == $item->ref_model_name) ? $item->quiz : (('assignments' == $item->ref_model_name) ? $item->assignment : false)
                        // , 'ref_model_uuid' => ('quizzez' == $item->ref_model_name) ? $item->quiz->uuid : $item->assignment->uuid
                        , 'ref_model_uuid' => ('quizzez' == $item->ref_model_name) ? $item->quiz->uuid : (('student_assignments' == $item->ref_model_name)? $item->student_assignment->teacher_assignment->uuid : (('quiz_attempt_stats' == $item->ref_model_name) ? $item->student_attempt->uuid : $item->assignment->uuid))
                        , 'ref_model_url' => ('quizzez' == $item->ref_model_name)? (($isStudent)? route('quiz.viewQuiz', [$item->quiz->uuid]) : route('quiz.viewQuiz', [$item->quiz->uuid])) : (('quiz_attempt_stats' == $item->ref_model_name) ? 'javascript:void(0)' : null)
                        , 'is_quiz_attempted' => ('quizzez' == $item->ref_model_name)? (($isStudent)? $item->quiz->is_attempted : false) : false
                        , 'is_assignment_attempted' => ('assignments' == $item->ref_model_name)? (($isStudent)? $item->assignment->is_attempted : false) : (('student_assignments' == $item->ref_model_name)? (($item->student_assignment != null)) : false)
                        // , 'student_attempt' => ('quizzez' == $item->ref_model_name && 'quiz_attempt_stats' == $item->ref_model_name)? $item->student_attempt : false
                        , 'student_assignment_attempt' => ('assignments' == $item->ref_model_name) ? (($isStudent) ? $item->assignment->my_attempt : null) : null
                        , 'student_quiz_attempt' => ('quizzez' == $item->ref_model_name)? (($isStudent)? $item->quiz->my_attempt : null) : null
                        , 'additional_ref_model_name' => $item->additional_ref_model_name
                        // , 'additional_ref_model_uuid' => ('quizzez' == $item->ref_model_name)? $item->quiz->course->uuid : $item->assignment->uuid
                        // , 'additional_ref_model' => ('quizzez' == $item->additional_ref_model_name) ? $item->quiz->course : (('student_assignments' == $item->additional_ref_model_name) ? $item->student_assignment : (('quiz_attempt_stats' == $item->additional_ref_model_name) ? $item->student_attempt : (('assignments' == $item->additional_ref_model_name)? $item->assignment : null)))
                        , 'additional_ref_model_uuid' => ('quizzez' == $item->ref_model_name)? $item->quiz->course->uuid : (('student_assignments' == $item->ref_model_name)? $item->student_assignment->teacher_assignment->uuid : (('quiz_attempt_stats' == $item->ref_model_name) ? $item->student_attempt->quiz->uuid : $item->assignment->uuid))
                        , 'nature' => ('quizzez' == $item->ref_model_name || 'quiz_attempt_stats' == $item->ref_model_name )? 'quiz' : 'assignment'
                        // , 'has_past' => ('quizzez' == $item->ref_model_name)? $item->quiz->due_date : $item->assignment->due_date
                        , 'is_marked_assignment' => ('student_assignments' == $item->ref_model_name) ? $item->student_assignment->is_marked_assignment : false
                        // , 'has_teacher_marked_assignment' => (('student_assignments' == $item->ref_model_name) && ('marked' == $item->student_assignment->status))

                        , 'is_marked_quiz' => (('quiz_attempt_stats' == $item->ref_model_name) && ('marked' == $item->student_attempt->status))
                        , 'has_teacher_marked_quiz' => (('quiz_attempt_stats' == $item->ref_model_name) && ('marked' == $item->student_attempt->status))
                    ],
                ];

                if('quizzez' == $item->ref_model_name){
                    $temp['extendedProps']['quiz_type'] = $item->quiz->type;
                }
                $events[] = $temp;
            }
        }
        // dd($events);
        // $item = $slots[0];
        // dd($slots);
        if (!empty($slots)) {
            // dd($slots);
            foreach ($slots as  $item) {
                if($item->enrolments_count){
                    $chosenDates = getDatesInRangeWithGivenDays($item->slot_start, $item->slot_end, $item->day_nums);

                    // 2021-07-20 14:14:00
                    foreach ($chosenDates as  $selectedDate) {
                        $date = new DateTime($selectedDate);
                        $now = new DateTime();
                        $diff = $date->diff($now)->format("%d days, %h hours and %i minutes");
                        $hasExpired = ($now > $date);

                        $is_lecture_time = false;
                        if(!$hasExpired){
                            $selectedDate = date('Y-m-d', strtotime($selectedDate));
                            $selected_date_slot_start = $selectedDate . ' '. $item->model_start_time_php;
                            $selected_date_slot_end = $selectedDate . ' ' . $item->model_end_time_php;
                            // dd($selected_date_slot_start, $selected_date_slot_end);
                            $selected_date_slot_start = new DateTime($selected_date_slot_start);
                            $selected_date_slot_end = new DateTime($selected_date_slot_end);
                            $is_lecture_time = (($now >= $selected_date_slot_start) && ($now <= $selected_date_slot_end));
                        }

                        // $date_now = strtotime(date('H:i'));  // for utc time
                        // $start_date = strtotime(date('H:i', strtotime($this->model_start_time_php)));
                        // $slot_end = strtotime(date('H:i', strtotime($this->model_end_time_php)));

                        // if (($date_now >= $start_date) && ($date_now <= $slot_end)) {
                        //     return true;
                        // }
                        // return false;

                        $temp = [
                            'id' => \Str::uuid()
                            , 'title' => $item->course->title
                            , 'start' => $selectedDate //$item->slot_start
                            , 'end' => $selectedDate
                            , 'time_remaing' => $diff
                            , 'has_expired' => $hasExpired
                            , 'backgroundColor' => '#70B547'
                            , 'borderColor' => '#70B547'
                            , 'textColor' => '#FFF'
                            , 'isStudent' => ($request->user()->profile->profile_type == 'student')? true : false
                            , 'allDay' => false
                            , 'className' => ['calendar_event-s']
                            , 'extendedProps' => [
                                'ref_url' => route('course.view', [$item->course->uuid])
                                , 'sender_name' => $item->course->teacher->first_name . ' ' . $item->course->teacher->last_name
                                , 'sender_uuid' => $item->course->teacher->uuid
                                , 'sender_image' => getFileUrl($item->course->teacher->profile_image)
                                , 'nature' => 'course_slot'
                                // , 'slot_start' => $item->slot_start
                                // , 'slot_end' => $item->slot_end
                                , 'slot_uuid' =>$item->uuid
                                , 'slot_start' => $item->model_start_date
                                , 'slot_end' => $item->model_end_date
                                , 'student_uuid'=> $item->last_enrolment->student->uuid
                                , 'student_first_name' => $item->last_enrolment->student->first_name
                                , 'student_last_name' => $item->last_enrolment->student->last_name
                                , 'start_time' => $item->model_start_time
                                , 'end_time' => $item->model_end_time
                                , 'course_title' => $item->course->title
                                , 'course_status' => $item->course->course_status
                                , 'is_course_free' => $item->course->is_course_free
                                , 'is_lecture_time' => $is_lecture_time
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
