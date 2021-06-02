<?php

namespace Modules\Course\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\Common\Services\CommonService;
use Modules\Common\Services\StatsService;
use Modules\Course\Http\Controllers\API\CourseContentController;
use Modules\Course\Http\Controllers\API\CourseDetailController;
use Modules\Course\Http\Controllers\API\CourseOutlineController;
use Modules\Course\Http\Controllers\API\CourseSlotController;

class CourseController extends Controller
{
    private $commonService;
    private $courseDetailsCtrlObj;
    private $courseOutlineCtrlObj;
    private $courseContentController;
    private $courseSlotController;

    private $statsService;

    public function __construct(
            CommonService $commonService
            , CourseDetailController $courseDetailsCtrlObj
            , CourseOutlineController $courseOutlineCtrlObj
            , CourseContentController $courseContentController
            , CourseSlotController $courseSlotController

            , StatsService $statsService
    )
    {
        $this->commonService = $commonService;
        $this->courseDetailsCtrlObj = $courseDetailsCtrlObj;
        $this->courseOutlineCtrlObj = $courseOutlineCtrlObj;
        $this->courseContentController = $courseContentController;
        $this->courseSlotController = $courseSlotController;

        $this->statsService = $statsService;
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
        return json_encode($apiResponse);
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
        return json_encode($apiResponse);
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
        return json_encode($apiResponse);
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
        return json_encode($apiResponse);
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
        return json_encode($apiResponse);
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
        return json_encode($apiResponse);
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
        return json_encode($apiResponse);
    }


    public function listTopCourses(Request $request)
    {
        // get All courses stats
        $result = $this->statsService->getAllCoursesStats($request);
        if (!$result['status']) {
            return abort($result['responseCode'], $result['message']);
        }
        $stats = $result['data'];

        // get top 10 courses
        $request->merge([
            'is_top' => 1,
            'offset' => 0,
            'limit' => 10
        ]);

        $ctrlObj = $this->courseDetailsCtrlObj;

        // list online courses
        $request->merge(['nature' => 'video']);
        $result = $ctrlObj->getCourseDetails($request)->getData();
        if (!$result->status) {
            return abort($result->responseCode, $result->message);
        }
        $top_video_courses = $result->data;

        // list video courses
        $request->merge(['nature' => 'online']);
        $result = $ctrlObj->getCourseDetails($request)->getData();
        if (!$result->status) {
            return abort($result->responseCode, $result->message);
        }
        $top_online_courses = $result->data;

        // dd($top_online_courses);
        return view('course::index', [
            'stats' => $stats
            , 'top_video_courses' => $top_video_courses
            , 'top_online_courses' => $top_online_courses
        ]);
    }


    public function listCoursesByNature($nature, Request $request)
    {
        // get All courses stats
        $result = $this->statsService->getAllCoursesStats($request);
        if (!$result['status']) {
            return abort($result['responseCode'], $result['message']);
        }
        $stats = $result['data'];

        // get top 10 courses
        $request->merge([
            'nature' => $request->nature
        ]);
        $ctrlObj = $this->courseDetailsCtrlObj;

        // list online courses
        $result = $ctrlObj->getCourseDetails($request)->getData();
        if (!$result->status) {
            return abort($result->responseCode, $result->message);
        }
        $courses = $result->data;

        dd($courses);
        return view('course::list', [
            'stats' => $stats, 'courses' => $courses
        ]);
    }




    //  web methods

    public function viewCourse($uuid, Request $request)
    {
        $request->merge(['course_uuid' => $uuid]);
        $ctrlObj = $this->courseDetailsCtrlObj;
        $apiResponse = $ctrlObj->checkCourseDetails($request)->getData();

        if ($apiResponse->status) {
            $course = $apiResponse->data;
            return view('course::view', [
                'course' => $course
            ]);
        }
        return abort(404, 'Record Not Found');
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
