<?php

namespace Modules\Student\Http\Controllers\API;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Common\Services\CommonService;
use Modules\Course\Services\CourseDetailService;
use Modules\Student\Services\StudentAssignmentService;
use Modules\User\Services\ProfileService;

class StudentAssignmentController extends Controller
{
    private $commonService;
    private $courseDetailService;
    private $profileService;
    private $studentAssignmentService;

    public function __construct(CommonService $commonService, CourseDetailService $courseDetailService, ProfileService $profileService, StudentAssignmentService $studentAssignmentService )
    {
        $this->commonService = $commonService;
        $this->courseDetailService = $courseDetailService;
        $this->profileService = $profileService;
        $this->studentAssignmentService = $studentAssignmentService;
    }

    /**
     * Add|Update Student Assignment 
     *
     * @param Request $request
     * @return void
     */
    public function updateStudentAssignment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_assignment_uuid' => 'exists:student_assignments,uuid',
            'obtained_marks' => 'required|integer',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // student_assignment_uuid
        $student_assignment_id = null;
        if(isset($request->student_assignment_uuid) && ('' != $request->student_assignment_uuid)){
            $result = $this->studentAssignmentService->checkStudentAssignment($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $student_assignment = $result['data'];
            $student_assignment_id = $student_assignment->id;
            $request->merge(['status' => 'marked']);
        }

        $result = $this->studentAssignmentService->addUpdateStudentAssignment($request, $student_assignment_id);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $student_assignment = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $student_assignment);
    }
}
