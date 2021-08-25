<?php

namespace Modules\Course\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\Chat\Http\Controllers\API\ChatController;
use Modules\Common\Services\CommonService;
use Modules\Common\Services\StatsService;
use Modules\Course\Http\Controllers\API\CourseContentController;
use Modules\Course\Http\Controllers\API\CourseDetailController;
use Modules\Course\Http\Controllers\API\CourseOutlineController;
use Modules\Course\Http\Controllers\API\CourseSlotController;
use Modules\Course\Http\Controllers\API\HandoutContentController;
use Modules\Course\Services\CourseSlotService;
use Modules\Student\Http\Controllers\API\StudentCourseEnrollmentController;
use Modules\User\Services\ProfileService;

class CourseController extends Controller
{
    private $commonService;
    private $courseDetailsCtrlObj;
    private $courseOutlineCtrlObj;
    private $courseContentController;
    private $courseHandoutController;
    private $courseSlotController;
    private $chatController;
    private $statsService;
    private $courseSlotService;
    private $profileService;
    private $studentCtrlObj;

    public function __construct(
            CommonService $commonService
            , CourseDetailController $courseDetailsCtrlObj
            , CourseOutlineController $courseOutlineCtrlObj
            , CourseContentController $courseContentController
            , HandoutContentController $courseHandoutController
            , CourseSlotController $courseSlotController
            , ChatController $chatController
            , StatsService $statsService
            , CourseSlotService $courseSlotService
            , ProfileService $profileService
            , StudentCourseEnrollmentController $studentCtrlObj
    )
    {
        $this->commonService = $commonService;
        $this->courseDetailsCtrlObj = $courseDetailsCtrlObj;
        $this->courseOutlineCtrlObj = $courseOutlineCtrlObj;
        $this->courseContentController = $courseContentController;
        $this->courseHandoutController = $courseHandoutController;
        $this->courseSlotController = $courseSlotController;
        $this->chatController = $chatController;
        $this->profileService = $profileService;
        $this->studentCtrlObj = $studentCtrlObj;

        $this->statsService = $statsService;
        $this->courseSlotService = $courseSlotService;
    }

    // public function getTeacherCourseSlots(Request $request)
    // {
    //     $ctrlObj = $this->courseDetailsCtrlObj;
    //     $apiResponse = $ctrlObj->getTeacherCourseSlots($request)->getData();

    //     if ($apiResponse->status) {
    //         $data = $apiResponse->data;
    //         return $this->commonService->getSuccessResponse('Teacher Course Slots Fetched Successfully', $data);
    //     }
    //     return json_encode($apiResponse);
    // }

    /**
     * List all non approved courses [ADMIN ONLY]
     *
     * @param Request $request
     * @return void
     */
    public function approveTeacherCourses(Request $request)
    {
        $request->merge(['approved_only' => '0']);
        $apiResponse = $this->courseDetailsCtrlObj->getCourseDetails($request)->getData();
        if ($apiResponse->status) {
            return view('user::non_approved_courses', ['models' => $apiResponse->data->courses, 'total_models' => $apiResponse->data->total_count]);
        }
        return view('common::errors.500');
    }



    /**
     * Add|Update a Course basics
     *
     * @param Request $request
     * @return void
     */
    public function updateCourseDetail(Request $request)
    {
        $ctrlObj = $this->courseDetailsCtrlObj;
        $request->merge([
            'is_course_free' => isset($request->is_course_free)? $request->is_course_free : '1'
            , 'teacher_uuid' => isset($request->teacher_uuid) ? $request->teacher_uuid : $request->user()->profile->uuid
            // ,'title' => $request->course_title
        ]);
        // dd($request->all());
        if(null == $request->course_uuid || '' ==  $request->course_uuid){
            unset($request['course_uuid']);
        }
        $apiResponse = $ctrlObj->updateCourseDetail($request)->getData();

        if($apiResponse->status){
            $data = $apiResponse->data;
            return $this->commonService->getSuccessResponse('Course Saved Successfully', $data);
        }
        // return json_encode($apiResponse);
        return $this->commonService->getProcessingErrorResponse($apiResponse->message, $apiResponse->data, $apiResponse->responseCode, $apiResponse->exceptionCode);

    }

    /**
     * add|update Course Outline
     *
     * @param Request $request
     * @return void
     */
    public function updateCourseOutline(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'outline_title' => 'required',
            // 'duration_hrs' => 'required|integer',
            // 'duration_mins' => 'required|integer',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }
        $ctrlObj = $this->courseOutlineCtrlObj;
        $request->merge([
            'title' => $request->outline_title,
        ]);
        if(null == $request->course_outline_uuid || '' ==  $request->course_outline_uuid){
            unset($request['course_outline_uuid']);
        }

        $apiResponse = $ctrlObj->updateCourseOutline($request)->getData();

        if($apiResponse->status){
            $data = $apiResponse->data;
            return $this->commonService->getSuccessResponse('Course Outline Saved Successfully', $data) ;
        }
        // return json_encode($apiResponse);
        return $this->commonService->getProcessingErrorResponse($apiResponse->message, $apiResponse->data, $apiResponse->responseCode, $apiResponse->exceptionCode);

    }

    /**
     * Delete Course Outline
     *
     * @param Request $request
     * @return void
     */
    public function deleteCourseOutline(Request $request)
    {
        $ctrlObj = $this->courseOutlineCtrlObj;
        $apiResponse = $ctrlObj->deleteCourseOutline($request)->getData();

        if ($apiResponse->status) {
            $data = $apiResponse->data;
            return $this->commonService->getSuccessResponse('Course Deleted Successfully', $data);
        }
        // return json_encode($apiResponse);
        return $this->commonService->getProcessingErrorResponse($apiResponse->message, $apiResponse->data, $apiResponse->responseCode, $apiResponse->exceptionCode);
    }


    /**
     * add|update Course Outline
     *
     * @param Request $request
     * @return void
     */
    public function updateVideoCourseContent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content_title' => 'required',
        ]);

        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }
        $ctrlObj = $this->courseContentController;
        $request->merge([
            'title' => $request->content_title,
        ]);
        if (null == $request->video_course_content_uuid || '' ==  $request->video_course_content_uuid) {
            unset($request['video_course_content_uuid']);
        }
        else{
            $request->merge([
                'course_content_uuid' => $request->video_course_content_uuid,
            ]);
        }
        // dd($request->all());
        $apiResponse = $ctrlObj->updateCourseContent($request)->getData();
        if ($apiResponse->status) {
            $data = $apiResponse->data;
            return $this->commonService->getSuccessResponse('Course Content Saved Successfully', $data);
        }
        // return json_encode($apiResponse);
        return $this->commonService->getProcessingErrorResponse($apiResponse->message, $apiResponse->data, $apiResponse->responseCode, $apiResponse->exceptionCode);
    }

    /**
     * Delete Course Video Content
     *
     * @param Request $request
     * @return void
     */
    public function deleteCourseVideoContent(Request $request)
    {
        $ctrlObj = $this->courseContentController;
        $apiResponse = $ctrlObj->deleteCourseContennt($request)->getData();

        if ($apiResponse->status) {
            $data = $apiResponse->data;
            return $this->commonService->getSuccessResponse('Course Video Deleted Successfully', $data);
        }
        // return json_encode($apiResponse);
        return $this->commonService->getProcessingErrorResponse($apiResponse->message, $apiResponse->data, $apiResponse->responseCode, $apiResponse->exceptionCode);
    }


    public function updateCourseHandoutContent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'handout_title' => 'required',
        ]);

        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }
        $ctrlObj = $this->courseHandoutController;
        $request->merge([
            'title' => $request->handout_title,
        ]);
        // dd($request->all());
        if (null == $request->handout_content_uuid || '' ==  $request->handout_content_uuid) {
            unset($request['handout_content_uuid']);
        }
        else{
            $request->merge([
                'handout_content_uuid' => $request->handout_content_uuid,
            ]);
        }
        // // dd($request->all());
        $apiResponse = $ctrlObj->updateHandoutContent($request)->getData();
        if ($apiResponse->status) {
            $data = $apiResponse->data;
            return $this->commonService->getSuccessResponse('Course Handout Saved Successfully', $data);
        }
        // return json_encode($apiResponse);
        return $this->commonService->getProcessingErrorResponse($apiResponse->message, $apiResponse->data, $apiResponse->responseCode, $apiResponse->exceptionCode);
    }

    public function deleteCourseHandoutContent(Request $request)
    {
        $ctrlObj = $this->courseHandoutController;
        $apiResponse = $ctrlObj->deleteHandoutContent($request)->getData();

        if ($apiResponse->status) {
            $data = $apiResponse->data;
            return $this->commonService->getSuccessResponse('Course Handout Deleted Successfully', $data);
        }
        // return json_encode($apiResponse);
        return $this->commonService->getProcessingErrorResponse($apiResponse->message, $apiResponse->data, $apiResponse->responseCode, $apiResponse->exceptionCode);
    }



    /**
     * add|update Course Outline
     *
     * @param Request $request
     * @return void
     */
    public function updateCourseSlot(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }
        $ctrlObj = $this->courseSlotController;
        $request->merge([
            'slot_start' => $request->start_date.' '.$request->start_time.':00',
            'slot_end' => $request->end_date . ' ' . $request->end_time . ':00',
        ]);
        if (null == $request->course_slot_uuid || '' ==  $request->course_slot_uuid) {
            unset($request['course_slot_uuid']);
        }

        // dd($request->all());
        $apiResponse = $ctrlObj->updateCourseSlot($request)->getData();
        if ($apiResponse->status) {
            $data = $apiResponse->data;
            return $this->commonService->getSuccessResponse('Course Slot Saved Successfully', $data);
        }
        // return json_encode($apiResponse);
        return $this->commonService->getProcessingErrorResponse($apiResponse->message, $apiResponse->data, $apiResponse->responseCode, $apiResponse->exceptionCode);
    }

    /**
     * Delete Course Slot
     *
     * @param Request $request
     * @return void
     */
    public function deleteCourseSlot(Request $request)
    {
        $ctrlObj = $this->courseSlotController;
        $apiResponse = $ctrlObj->deleteCourseSlot($request)->getData();

        if ($apiResponse->status) {
            $data = $apiResponse->data;
            return $this->commonService->getSuccessResponse('Course Slot Deleted Successfully', $data);
        }
        // return json_encode($apiResponse);
        return $this->commonService->getProcessingErrorResponse($apiResponse->message, $apiResponse->data, $apiResponse->responseCode, $apiResponse->exceptionCode);
    }

    /**
     * Get Course Slots against given course UUID
     *
     * @param Request $request
     * @return void
     */
    public function getCourseSlotByCourse(Request $request)
    {
        $ctrlObj = $this->courseDetailsCtrlObj;
        $request->merge([
            'only_relations' => ['slots']
        ]);

        $apiResponse = $ctrlObj->getCourseWithOnlyRelationsByCourse($request)->getData();

        if ($apiResponse->status) {
            $data = $apiResponse->data;
            $view = '';
            if($data->nature != 'video'){
                foreach ($data->slots as $slot) {
                    $view .= view('course::partials.__slots_col', ['item' => $slot, 'is_activity_listing' => true]);
                }
            }
            $data->slots_view = $view;
            // dd($data);
            return $this->commonService->getSuccessResponse('Data Fetched Successfully', $data);
        }
        // return json_encode($apiResponse);
        return $this->commonService->getProcessingErrorResponse($apiResponse->message, $apiResponse->data, $apiResponse->responseCode, $apiResponse->exceptionCode);
    }


    /**
     * List Top 10 courses from both categories
     *
     * @param Request $request
     *
     * @return void
     */
    public function listTopCourses(Request $request)
    {
        // get All courses stats
        if ($request->user()->profile_type == 'teacher') {
            $result = $this->statsService->getTecherSpecificStats($request);
        } else {
            $result = $this->statsService->getAllCoursesStats($request);
        }
        if (!$result['status']) {
            return abort($result['responseCode'], $result['message']);
        }
        $stats = $result['data'];

        // $courseStats = $this->courseDetailsCtrlObj;
        // $result = $courseStats->getCourseDetails($request);
        // if (!$result['status']) {
        //     return abort($result['responseCode'], $result['message']);
        // }
        // $stats = $result['data'];

        // get top 10 courses
        $request->merge([
            'is_top' => 1,
            'offset' => 0,
            'limit' => 10,
        ]);

        $ctrlObj = $this->courseDetailsCtrlObj;

        // list online courses
        $request->merge(['nature' => 'video']);
        // $request->merge([
        //     'nature' => 'video',
        //     'teacher_uuid' => $request->user()->profile->uuid,
        // ]);
        $result = $ctrlObj->getCourseDetails($request)->getData();
        if (!$result->status) {
            // view("common:errors.500");
            return abort($result->responseCode, $result->message);
        }
        $top_video_courses = $result->data;
        // $stats = $result->data; // stats of course


        // list video courses
        $request->merge(['nature' => 'online']);
        $result = $ctrlObj->getCourseDetails($request)->getData();
        // dd($result);
        if (!$result->status) {
            return abort($result->responseCode, $result->message);
        }
        $top_online_courses = $result->data;

        // dd($top_online_courses, "212");
        return view('course::index', [
            'stats' => $stats
            , 'top_online_courses' => $top_online_courses
            , 'top_video_courses' => $top_video_courses
        ]);
    }

    /**
     * List all coursses from given category
     *
     * @param [type] $nature
     * @param Request $request
     * @return void
     */
    public function listCoursesByNature($nature, Request $request)
    {
        // get All courses stats
        // $result = $this->statsService->getAllCoursesStats($request);

        if($request->user()->profile_type == 'teacher'){
            $result = $this->statsService->getTecherSpecificStats($request);
        }
        else{
            $result = $this->statsService->getAllCoursesStats($request);
        }

        if (!$result['status']) {
            return abort($result['responseCode'], $result['message']);
        }
        $stats = $result['data'];

        // get top 10 courses
        $request->merge([
            'nature' => $request->nature,
            // 'teacher_uuid' =>$request->user()->profile->uuid,
        ]);
        $ctrlObj = $this->courseDetailsCtrlObj;

        // list online courses
        $result = $ctrlObj->getCourseDetails($request)->getData();
        if (!$result->status) {
            return abort($result->responseCode, $result->message);
        }
        $courses = $result->data;
        // $stats = $result->data;

        // dd($courses);
        return view('course::list', [
            'course_nature' => $request->nature
            , 'stats' => $stats
            , 'courses' => $courses
        ]);
    }


    /**
     * List all student enroll/suggested courses
     *
     * @param [type] $nature
     * @param Request $request
     * @return void
     */
    public function listStudentEnrollSuggestNature($call, Request $request)
    {
        // validate if request user is actually a teacher
        $request->merge([
            'profile_id' => $request->user()->profile->id
            , 'profile_uuid' => $request->user()->profile->uuid
            , 'student_uuid' => $request->user()->profile->uuid
            , 'profile_interests' => explode(',', $request->user()->profile->interests)
        ]);

        $result = $this->profileService->checkStudent($request);
        if (!$result['status']) {
            return view('common::errors.403');
        }
        $currentProfile = $result['data'];

        // enrolled and suggested courses for dashboard


        // dd($enrolled_courses);

        if($call == 'enrolled')
        {
            $result = $this->studentCtrlObj->getStudentEnrolledCourses($request)->getData();
            if (!$result->status) {
                return view('common::errors.500');
            }
            $enrolled_courses = $result->data;
            $courses = $enrolled_courses;
        }
        else if($call == 'suggested')
        {
            $suggestionResult = $this->studentCtrlObj->getSuggestedCourses($request)->getData();
            if (!$suggestionResult->status) {
                return view('common::errors.500');
            }
            $suggestion_courses = $suggestionResult->data;
            $courses = $suggestion_courses;
        }

        return view('course::list', [
            'course_nature' => $call
            , 'courses' => $courses
        ]);
    }

    /**
     * View a single Course
     *
     * @param String $uuid
     * @param Request $request
     *
     * @return void
     */
    public function viewCourse($uuid, Request $request)
    {
        $request->merge(['course_uuid' => $uuid]);
        $ctrlObj = $this->courseDetailsCtrlObj;
        $apiResponse = $ctrlObj->checkCourseDetails($request)->getData();
        // dd($apiResponse);
        if ($apiResponse->status) {
            $course = $apiResponse->data;
            return view('course::view', [
                'course' => $course
            ]);
        }
        if(202 == $apiResponse->exceptionCode){
            return view('common::errors.202', ['backUrl' => route('teacher.dashboard')]);
        }
        else{
            return view('common::errors.500');
        }
    }

    /**
     * Preview|view a course based on if student is enrolled in it or not
     *
     * @param [type] $uuid
     * @param Request $request
     * @return void
     */
    public function previewCourse($uuid, Request $request)
    {
        $request->merge(['course_uuid' => $uuid]);
        $ctrlObj = $this->courseDetailsCtrlObj;
        $apiResponse = $ctrlObj->checkCourseDetails($request)->getData();
        // dd($apiResponse);
        if ($apiResponse->status) {
            $course = $apiResponse->data;
            if($request->user() != null){ // case: user is logged in
                if(($request->user()->profile_type == 'parent') || ($request->user()->profile_type == 'student')){ // case: its a parent
                    if (!$course->my_enrollment_count) { // case: its I have not enrolled in it
                        return view('course::preview', [
                            'course' => $course,
                            'page' => 'preview'
                        ]);
                    }
                }
                return view('course::view', [ // case: its a teacher or admin
                    'course' => $course
                ]);
            }
            return view('course::preview', [ // case: its a guest user
                'course' => $course,
                'page' => 'preview'
            ]);
        }
        return $this->commonService->getGeneralErrorResponse($apiResponse->message, $apiResponse->data);
    }

    /**
     * Get details against a single course
     *
     * @param [type] $uuid
     * @param Request $request
     * @return void
     */
    public function getCourse($uuid, Request $request)
    {
        $request->merge(['course_uuid' => $uuid]);
        $ctrlObj = $this->courseDetailsCtrlObj;
        $apiResponse = $ctrlObj->checkCourseDetails($request)->getData();
        if($apiResponse->status){
            return $this->commonService->getSuccessResponse($apiResponse->message, $apiResponse->data);
        }
        else{
            return $this->commonService->getGeneralErrorResponse($apiResponse->message, $apiResponse->data);
        }
    }

    /**
     * get Data of a single Course Slot by slot uuid
     *
     * @param [type] $uuid
     * @param Request $request
     * @return void
     */
    public function getCourseSlot($uuid, Request $request)
    {
        $request->merge(['course_slot_uuid' => $uuid]);
        $ctrlObj = $this->courseSlotController;
        $apiResponse = $ctrlObj->getCourseSlot($request)->getData();
        if ($apiResponse->status) {
            return $this->commonService->getSuccessResponse($apiResponse->message, $apiResponse->data);
        } else {
            return $this->commonService->getGeneralErrorResponse($apiResponse->message, $apiResponse->data);
        }
    }


    /**
     * View a single Course
     *
     * @param String $uuid
     * @param Request $request
     *
     * @return void
    */
    public function sendZoomLink( Request $request)
    {
        // dd( $request->all());
        $request->merge([
            'course_slot_uuid' => $request->slot_uuid,
            'zoom_link' => $request->zoom_meeting_url
        ]);

        // $ctrlObj = $this->courseSlotService;
        // $result = $ctrlObj->getSlotsRecieverIds($request);
        // if (!$result['status']) {
        //     return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        // }
        // $data = $result['data'];
        // return $this->commonService->getSuccessResponse('Success', $data);


        $chat = $this->chatController->sendZoomLinkMessage($request, null, null, null)->getData();
        if (!$chat->status) {
            return $this->commonService->getGeneralErrorResponse($chat->message, $chat->data);
        }

        $ctrlObj = $this->courseSlotController;
        $apiResponse = $ctrlObj->addZoomLink($request)->getData();
        if ($apiResponse->status) {
            return $this->commonService->getSuccessResponse($apiResponse->message, $apiResponse->data);
        } else {
            return $this->commonService->getGeneralErrorResponse($apiResponse->message, $apiResponse->data);
        }

    }




    // approve course approveCourse

    /**
     * Approve Course
     *
     * @param [type] $uuid
     * @param Request $request
     * @return void
     */
    public function approveCourse($uuid, Request $request)
    {
        $apiResponse = $this->courseDetailsCtrlObj->adminApproveCourse($request)->getData();
        if ($apiResponse->status) {
            $data = $apiResponse->data;
            return $this->commonService->getSuccessResponse('Admin approved your Course Successfully', $data);
        }
        // return json_encode($apiResponse);
        return $this->commonService->getProcessingErrorResponse($apiResponse->message, $apiResponse->data, $apiResponse->responseCode, $apiResponse->exceptionCode);
    }

    /**
     * Reject a teacher course
     *
     * @param Request $request
     * @return void
     */
    public function rejectTeacherCourse(Request $request)
    {
        $apiResponse = $this->courseDetailsCtrlObj->adminRejectCourse($request)->getData();
        if ($apiResponse->status) {
            $data = $apiResponse->data;
            return $this->commonService->getSuccessResponse('Admin Rejected your Course Successfully', $data);
        }
        // return json_encode($apiResponse);
        return $this->commonService->getProcessingErrorResponse($apiResponse->message, $apiResponse->data, $apiResponse->responseCode, $apiResponse->exceptionCode);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('course::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('course::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('course::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('course::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
