<?php

namespace Modules\Course\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\Common\Services\CommonService;
use Modules\Course\Http\Controllers\API\CourseDetailController;
use Modules\Course\Http\Controllers\API\CourseOutlineController;

class CourseController extends Controller
{
    private $commonService;
    private $courseDetailsCtrlObj;
    private $courseOutlineCtrlObj;

    public function __construct(CommonService $commonService, CourseDetailController $courseDetailsCtrlObj, CourseOutlineController $courseOutlineCtrlObj)
    {
        $this->commonService = $commonService;
        $this->courseDetailsCtrlObj = $courseDetailsCtrlObj;
        $this->courseOutlineCtrlObj = $courseOutlineCtrlObj;
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
        ]);
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
        $request->merge(['course_uuid' => '023f94ad-4de6-496f-9c92-a740ab1935fe']);

        $apiResponse = $ctrlObj->updateCourseOutline($request)->getData();

        if($apiResponse->status){
            $data = $apiResponse->data;
            return $this->commonService->getSuccessResponse('Course Saved Successfully', $data);
        }
        return json_encode($apiResponse);
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
