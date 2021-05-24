<?php

namespace Modules\Course\Http\Controllers\API;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Common\Services\CommonService;
use Modules\Course\Services\CourseDetailService;
use Modules\Course\Services\CourseOutlineService;

class CourseOutlineController extends Controller
{
    private $commonService;
    private $courseOutlineService;
    private $courseDetailService;

    public function __construct(CommonService $commonService, CourseOutlineService $courseOutlineService, CourseDetailService $courseDetailService )
    {
        $this->commonService = $commonService;
        $this->courseOutlineService = $courseOutlineService;
        $this->courseDetailService = $courseDetailService;
    }

    /**
     * Get a Single Handout Content based on uuid
     *
     * @param Request $request
     * @return void
     */
    public function getCourseOutline(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_outline_uuid' => 'required',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and fetch Course Outline
        $result = $this->courseOutlineService->checkCourseOutline($request);
        if(!$result['status']){
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $handout_content = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $handout_content);
    }

    /**
     * Delete Course Outline by UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteCourseOutline(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_outline_uuid' => 'required|exists:course_outlines,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }
        DB::beginTransaction();
        // validate and delete Course Outline
        $result = $this->courseOutlineService->deleteCourseOutline($request);
        if (!$result['status']) {
            DB::rollBack();
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $course_outline = $result['data'];

        DB::commit();
        return $this->commonService->getSuccessResponse('Record Deleted Successfully', []);
    }

    /**
     * Get Course Outline based on given filters
     *
     * @param Request $request
     * @return void
     */
    public function getCourseOutlines(Request $request)
    {
        if(isset($request->course_uuid) && ('' != $request->course_uuid)){
            $result = $this->courseDetailService->getCourseDetail($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $course = $result['data'];
            $request->merge(['course_id' => $course->id]);
        }

        $result = $this->courseOutlineService->getCourseOutlines($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $handout_content = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $handout_content);
    }

    /**
     * Add|Update Course Outline
     *
     * @param Request $request
     * @return void
     */
    public function updateCourseOutline(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_outline_uuid' => 'exists:course_outlines,uuid',
            'course_uuid' => 'required',
            'title' => 'required|string',
            'duration_hrs' => 'required|integer',
            'duration_mins' => 'required|integer',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // course_uuid
        if(isset($request->course_uuid) && ('' != $request->course_uuid)){
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

        DB::beginTransaction();
        $result = $this->courseOutlineService->addUpdateCourseOultine($request, $course_outline_id);
        if (!$result['status']) {
            DB::rollBack();
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $course_outline = $result['data'];

        DB::commit();
        return $this->commonService->getSuccessResponse('Success', $course_outline);
    }
}
