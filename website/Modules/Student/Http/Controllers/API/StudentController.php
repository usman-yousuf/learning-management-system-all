<?php

namespace Modules\Student\Http\Controllers\API;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Assignment\Services\AssignmentService;
use Modules\Common\Services\CommonService;
use Modules\Course\Services\CourseDetailService;
use Modules\Student\Services\StudentAssignmentService;
use Modules\User\Services\ProfileService;
use Modules\User\Services\UserService;

class StudentController extends Controller
{
    private $commonService;
    private $userService;
    private $profileService;
    private $courseDetailService;
    private $assignmentService;
    private $submitAssignmentService;

    public function __construct(CommonService $commonService, UserService $userService, ProfileService $profileService, CourseDetailService $courseDetailService, AssignmentService $assignmentService, StudentAssignmentService $submitAssignmentService)
    {
        $this->commonService = $commonService;
        $this->userService = $userService;
        $this->profileService = $profileService;
        $this->courseDetailService = $courseDetailService;
        $this->assignmentService = $assignmentService;
        $this->submitAssignmentService = $submitAssignmentService;
    }

    /**
     * Get a Single Student based on uuid
     *
     * @param Request $request
     * @return void
     */
    public function getStudent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_uuid' => 'required',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and fetch Student
        $request->merge(['profile_uuid' => $request->student_uuid]);
        $result = $this->profileService->checkStudent($request);
        if(!$result['status']){
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $courseReview = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $courseReview);
    }

    /**
     * Delete Student  by UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteStudent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_uuid' => 'required|exists:profiles,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and fetch Student
        if(isset($request->student_uuid) && ('' !=$request->student_uuid))
        {
            $request->merge(['profile_uuid' => $request->student_uuid]);
            $result = $this->profileService->checkStudent($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
        }

        // validate and delete Student
        $result = $this->profileService->deleteProfile($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $student = $result['data'];
        return $this->commonService->getSuccessResponse('Record Deleted Successfully', []);
    }

    /**
     * Get Students based on given filters
     *
     * @param Request $request
     * @return void
     */
    public function getStudents(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_uuid' => 'exists:users,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        //user_uuid
        if(isset($request->user_uuid) && ('' != $request->user_uuid)){
        $result = $this->userService->getUser($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $user = $result['data'];
        $request->merge(['user_id' => $user->id]);
        }

        $result = $this->profileService->listProfiles($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $student = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $student);
    }

    /**
     * Add|Update Student
     *
     * @param Request $request
     *
     * @return void
     */
    public function updateStudent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_uuid' => 'exists:profiles,uuid',
            'user_uuid' => 'exists:users,uuid',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'gender' => 'required|string',
            'profile_type' => 'required|string',
            'dob' => 'required|date',
            'interests' => 'required|string',
            'phone_code' => 'required|numeric',
            'phone_code' => 'required|numeric',
            'status' => 'required|string',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        //user_uuid
        if(isset($request->user_uuid) && ('' != $request->user_uuid)){
            $result = $this->userService->getUser($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $user = $result['data'];
            $request->merge(['user_id' => $user->id]);
        }

        // find Student by uuid if given
        $profile_id = null;
        if(isset($request->student_uuid) && ('' != $request->student_uuid)){
            $request->merge(['profile_uuid' => $request->student_uuid]);
            $result = $this->profileService->getProfile($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $profile = $result['data'];
            $profile_id = $profile->id;
        }

        $result = $this->profileService->addUpdateProfile($request, $profile_id);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $review = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $review);
    }


    public function submitAssignment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_assignment_uuid' => 'exists:student_assignments,uuid',
            'student_uuid' => 'exists:profiles,uuid',
            'course_uuid' => 'exists:courses,uuid',
            'assignment_uuid' => 'exists:assignments,uuid',
            // 'status' => 'required|string',
          
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // find Student by uuid if given
        if(isset($request->student_uuid) && ('' != $request->student_uuid)){
            $request->merge(['profile_uuid' => $request->student_uuid]);
            $result = $this->profileService->getProfile($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $profile = $result['data'];
            $request->merge(['profile_id' => $profile->id]);
        }

        // course_uuid
        if(isset($request->course_uuid) && ('' != $request->course_uuid)){
            $result = $this->courseDetailService->checkCourseDetail($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $course = $result['data'];
            $request->merge(['course_id' => $course->id]);
        }

        // assignment_uuid
        if(isset($request->assignment_uuid) && ('' != $request->assignment_uuid))
        {
            $result = $this->assignmentService->checkAssignment($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $assignment = $result['data'];
            $request->merge(['assignment_id' => $assignment->id]);
        }

        //student_assignment_id
        $student_assignment_id = null;
        if(isset($request->student_uuid) && ('' != $request->student_uuid)){
            $result = $this->submitAssignmentService->checkStudentAssignment($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $student_assignment = $result['data'];
            $student_assignment_id = $student_assignment->id;
        }

        $result = $this->submitAssignmentService->addUpdateStudentAssignment($request, $student_assignment_id);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $student_assignment = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $student_assignment);
    }
}
