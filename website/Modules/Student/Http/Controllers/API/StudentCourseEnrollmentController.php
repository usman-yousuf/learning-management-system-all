<?php

namespace Modules\Student\Http\Controllers\API;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Common\Services\CommonService;
use Modules\Course\Services\CourseDetailService;
use Modules\Student\Services\StudentCourseEnrollmentService;
use Modules\User\Services\ProfileService;

class StudentCourseEnrollmentController extends Controller
{
    private $commonService;
    private $courseDetailService;
    private $profileService;
    private $studentCourseService;

    public function __construct(CommonService $commonService, StudentCourseEnrollmentService $studentCourseService, CourseDetailService $courseDetailService, ProfileService $profileService )
    {
        $this->commonService = $commonService;
        $this->studentCourseService = $studentCourseService;
        $this->courseDetailService = $courseDetailService;
        $this->profileService = $profileService;
    }

    /**
     * Remove Student Course Enrollment  by UUID
     *
     * @param Request $request
     * @return void
     */
    public function removeStudentCourseEnrollment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_course_uuid' => 'required|exists:student_courses,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and remove Student Course Enroll
        $result = $this->studentCourseService->deleteStudentCourse($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $std_course_enroll = $result['data'];

        return $this->commonService->getSuccessResponse('Record Deleted Successfully', []);
    }

      /**
     * Remove Student Course Enrollment  by Student_uuid and Course_uuid
     *
     * @param Request $request
     * @return void
     */
    public function removeEnrollment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_uuid' => 'required|exists:courses,uuid',
            'student_uuid' => 'required|exists:profiles,uuid',
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

        //student_uuid
        if(isset($request->student_uuid) && ('' != $request->student_uuid)){
            $request->merge(['profile_uuid' => $request->student_uuid]);
            $result = $this->profileService->getProfile($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $student = $result['data'];
            $request->merge(['student_id' => $student->id]);
        }

        // validate and remove Student Course Enroll
        $result = $this->studentCourseService->deleteEnrollment($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $std_course_enroll = $result['data'];

        return $this->commonService->getSuccessResponse('Record Deleted Successfully', []);
    }

    /**
     * Listing Course Student Enroll based on given filters
     *
     * @param Request $request
     * @return void
     */
    public function getStudentCourses(Request $request)
    {
        if(isset($request->course_uuid) && ('' != $request->course_uuid)){
            $result = $this->courseDetailService->getCourseDetail($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $course = $result['data'];
            $request->merge(['course_id' => $course->id]);
        }

           //student_uuid
           if(isset($request->student_uuid) && ('' != $request->student_uuid)){
            $request->merge(['profile_uuid' => $request->student_uuid]);

            $result = $this->profileService->getProfile($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $student = $result['data'];
            $request->merge(['student_id' => $student->id]);
        }

        $result = $this->studentCourseService->getStudentCourses($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $course_slot = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $course_slot);
    }

    /**
     * Add|Update Student Course Enroll
     *
     * @param Request $request
     * @return void
     */
    public function addUpdateStudentCourseEnroll(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_course_uuid' => 'exists:student_courses,uuid',
            'student_uuid' => 'required|exists:profiles,uuid',
            'course_uuid' => 'required|exists:courses,uuid',
            'status' => 'required|string',
            'joining_date' => 'required|date_format:Y-m-d H:i:s',
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

        //student_uuid
        if(isset($request->student_uuid) && ('' != $request->student_uuid)){
            $request->merge(['profile_uuid' => $request->student_uuid]);

            $result = $this->profileService->getProfile($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $student = $result['data'];
            $request->merge(['student_id' => $student->id]);
        }
        // find Student Course by uuid if given
        $student_course_id = null;
        if(isset($request->student_course_uuid) && ('' != $request->student_course_uuid)){
            $result = $this->studentCourseService->checkStudentCourse($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $student_course = $result['data'];
            $student_course_id = $student_course->id;
        }

        $result = $this->studentCourseService->addUpdateStudentCourse($request, $student_course_id);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $course_slot = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $course_slot);
    }
}