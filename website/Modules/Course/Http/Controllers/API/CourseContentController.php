<?php

namespace Modules\Course\Http\Controllers\API;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Common\Services\CommonService;
use Modules\Course\Services\CourseContentService;
use Modules\Course\Services\CourseDetailService;

class CourseContentController extends Controller
{
    private $commonService;
    private $courseContentService;
    private $courseDetailService;

    public function __construct(CommonService $commonService, CourseContentService $courseContentService, CourseDetailService $courseDetailService )
    {
        $this->commonService = $commonService;
        $this->courseContentService = $courseContentService;
        $this->courseDetailService = $courseDetailService;
    }

    /**
     * Get a Single Course Content based on uuid
     *
     * @param Request $request
     * @return void
     */
    public function getCourseContent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_content_uuid' => 'required',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and fetch Course Content
        $result = $this->courseContentService->checkCourseContent($request);
        if(!$result['status']){
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $course_content = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $course_content);
    }

    /**
     * Delete Course Content by UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteCourseContennt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_content_uuid' => 'required|exists:course_contents,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and delete Course Content
        $result = $this->courseContentService->deleteCourseContent($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $course_content = $result['data'];
        return $this->commonService->getSuccessResponse('Record Deleted Successfully', []);
    }

    /**
     * Get Course Content based on given filters
     *
     * @param Request $request
     * @return void
     */
    public function getCourseContents(Request $request)
    {
        if(isset($request->courses_uuid) && ('' != $request->courses_uuid)){
            $result = $this->courseDetailService->getCourseDetail($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $course = $result['data'];
            $request->merge(['course_id' => $course->id]);
        }

        $result = $this->courseContentService->getCourseContents($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $course_content = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $course_content);
    }

    /**
     * Add|Update Course Content
     *
     * @param Request $request
     * @return void
     */
    public function updateCourseContent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_content_uuid' => 'exists:course_contents,uuid',
            'courses_uuid' => 'required',
            'title' => 'required|string',
            'duration_hrs' => 'required|string',
            'duration_mins' => 'required|string',
            'url' => 'string',
            'content_image' => 'string'
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // course_uuid
        if(isset($request->courses_uuid) && ('' != $request->courses_uuid)){
            $result = $this->courseDetailService->getCourseDetail($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $course = $result['data'];
            $request->merge(['course_id' => $course->id]);
        }

        // find Course content by uuid if given
        $course_content_id = null;
        if(isset($request->course_content_uuid) && ('' != $request->course_content_uuid)){
            $result = $this->courseContentService->checkCourseContent($request);
            //dd($result);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $course_content = $result['data'];
            $course_content_id = $course_content->id;
        }

        $result = $this->courseContentService->addUpdateCourseContent($request, $course_content_id);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $course_content = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $course_content);
    }
}
