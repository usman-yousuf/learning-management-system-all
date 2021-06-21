<?php

namespace Modules\Course\Http\Controllers\API;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Common\Services\CommonService;
use Modules\Course\Services\CourseDetailService;
use Modules\Course\Services\StudentQueryService;
use Modules\Student\Services\StudentCourseEnrollmentService;
use Modules\User\Services\ProfileService;

class StudentQueryController extends Controller
{
    private $commonService;
    private $studentQueryService;
    private $courseDetailService;
    private $profileService;
    private $studentCourseService;

    public function __construct(CommonService $commonService, StudentQueryService $studentQueryService, CourseDetailService $courseDetailService, ProfileService $profileService, StudentCourseEnrollmentService $studentCourseService )
    {
        $this->commonService = $commonService;
        $this->studentQueryService = $studentQueryService;
        $this->courseDetailService = $courseDetailService;
        $this->profileService = $profileService;
        $this->studentCourseService = $studentCourseService;
    }

    /**
     * Get a Single Student Query based on uuid
     *
     * @param Request $request
     * @return void
     */
    public function getStudentQuery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_query_uuid' => 'required|exists:queries,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and fetch Student Query
        $result = $this->studentQueryService->getStudentQuery($request);
        if(!$result['status']){
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $handout_content = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $handout_content);
    }

    /**
     * Delete Student Query by UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteStudentQuery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_query_uuid' => 'required|exists:queries,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and delete Student Query
        $result = $this->studentQueryService->deleteStudentQuery($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $student_query = $result['data'];

        return $this->commonService->getSuccessResponse('Record Deleted Successfully', []);
    }

    /**
     * Get Handout Student query on given filters
     *
     * @param Request $request
     * @return void
     */
    public function getStudentQueries(Request $request)
    {
        if(isset($request->course_uuid_id) && ('' != $request->course_uuid_id)){
            $result = $this->courseDetailService->getCourseDetail($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $course = $result['data'];
            $request->merge(['course_id' => $course->id]);
        }

        $result = $this->studentQueryService->getStudentQuerys($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $handout_content = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $handout_content);
    }

    /**
     * Add|Update Student Query
     *
     * @param Request $request
     * @return void
     */
    public function updateStudentQuery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_query_uuid' => 'exists:queries,uuid',
            'course_uuid' => 'required|exists:courses,uuid',
            'student_uuid' => 'required|exists:profiles,uuid',
            'body' => 'required|string',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // course_uuid
        $course_id = null;
        if(isset($request->course_uuid) && ('' != $request->course_uuid)){
            $result = $this->courseDetailService->checkCourseDetail($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $course = $result['data'];
            $request->merge(['course_id' => $course->id]);
            $course_id = $course->id;
        }

        // find Student by uuid if given
        $student_id = null;
        if(isset($request->student_uuid) && ('' != $request->student_uuid)){
            $request->merge(['profile_uuid' => $request->student_uuid]);
            $result = $this->profileService->checkStudent($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $student = $result['data'];
            $request->merge(['student_id' => $student->id]);
            $student_id = $student->id;
        }
        // validate if both student and course are related 
        $result = $this->studentCourseService->checkEnrollment($student_id, $course_id);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }

        // find Student Query by uuid if given
        $student_query_id = null;
        if(isset($request->student_query_uuid) && ('' != $request->student_query_uuid)){
            $result = $this->studentQueryService->checkStudentQuery($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $student_query = $result['data'];
            $student_query_id = $student_query->id;
        }

        $result = $this->studentQueryService->addUpdateStudentQuery($request, $student_query_id);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $handout_content = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $handout_content);
    }
}
