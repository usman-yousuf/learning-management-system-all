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
     * Add|Update Handout Service
     *
     * @param Request $request
     * @return void
     */
    public function updateHandoutContent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'handout_content_uuid' => 'exists:handout_contents,uuid',
            'courses_uuid' => 'required',
            'title' => 'required|string',
            'url' => 'string',
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

        // find Course Outline by uuid if given
        $course_outline_id = null;
        if(isset($request->course_outline_uuid) && ('' != $request->course_outline_uuid)){
            $result = $this->courseOutlineService->checkCourseOutline($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $course_outline = $result['data'];
            $course_outline_id = $course_outline->id;
        }

        $result = $this->courseOutlineService->addUpdateCourseOultine($request, $course_outline_id);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $course_outline = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $course_outline);
    }
}
