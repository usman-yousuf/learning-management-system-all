<?php

namespace Modules\Course\Http\Controllers\API;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Common\Services\CommonService;
use Modules\Course\Services\HandoutContentService;
use Modules\Course\Services\CourseDetailService;

class HandoutContentController extends Controller
{
    private $commonService;
    private $handoutContentService;
    private $courseDetailService;
    public function __construct(CommonService $commonService, HandoutContentService $handoutContentService, CourseDetailService $courseDetailService )
    {
        $this->commonService = $commonService;
        $this->handoutContentService = $handoutContentService;
        $this->courseDetailService = $courseDetailService;
    }

    /**
     * Get a Single Handout Content based on uuid
     *
     * @param Request $request
     * @return void
     */
    public function getHandoutContent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'handout_content_uuid' => 'required',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and fetch Handout Content
        $result = $this->handoutContentService->checkCourseHandout($request);
        if(!$result['status']){
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $handout_content = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $handout_content);
    }

    /**
     * Delete Handout Content by UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteHandoutContent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'handout_content_uuid' => 'required|exists:handout_contents,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and delete Handout Content
        $result = $this->handoutContentService->deleteCourseHandout($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $handout_content = $result['data'];

        return $this->commonService->getSuccessResponse('Record Deleted Successfully', []);
    }

    /**
     * Get Handout Content based on given filters
     *
     * @param Request $request
     * @return void
     */
    public function getHandoutContents(Request $request)
    {
        if(isset($request->course_uuid_id) && ('' != $request->course_uuid_id)){
            $result = $this->courseDetailService->getCourseDetail($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $course = $result['data'];
            $request->merge(['course_id' => $course->id]);
        }

        $result = $this->handoutContentService->getCourseHandouts($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $handout_content = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $handout_content);
    }

    /**
     * Add|Update Handout Service
     *
     * @param Request $request
     * @return void
     */
    public function updateHandoutContent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'handout_content_uuid' => 'exists:handout_content,uuid',
            'is_default' => 'required|in:0,1',
            'course_uuid' => 'exists:courses,uuid',
            'title' => 'required|string',
            'url' => 'string',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // get/set course_uuid
        $uuid = null;
        if(isset($request->course_uuid) && ('' != $request->course_uuid)){
            $uuid = $request->course_uuid;
        }
        $request->merge(['course_uuid' => $uuid]);

        // valiadate and fetch profile
        $result = $this->courseDetailService->getCourseDetail($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $course = $result['data'];
        $request->merge(['course_id' => $course->id]);

        // find Handout Content by uuid if given
        $course_handout_id = null;
        if(isset($request->handout_content_uuid) && ('' != $request->handout_content_uuid)){
            $result = $this->handoutContentService->checkCourseHandout($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $handout_content = $result['data'];
            $course_handout_id = $handout_content->id;
        }

        $result = $this->handoutContentService->addUpdateCourseHandout($request, $course_handout_id);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $handout_content = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $handout_content);
    }
}
