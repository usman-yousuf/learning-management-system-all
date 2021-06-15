<?php

namespace Modules\Assignment\Http\Controllers\API;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Assignment\Services\AssignmentService;
use Modules\Common\Services\CommonService;
use Modules\Course\Services\CourseDetailService;
use Modules\Course\Services\CourseSlotService;
use Modules\User\Services\ProfileService;

class AssignmentController extends Controller
{
    private $commonService;
    private $courseDetailService;
    private $courseSlotService;
    private $profileService;
    private $assignmentService;
    public function __construct(CommonService $commonService, AssignmentService $assignmentService, CourseDetailService $courseDetailService, CourseSlotService $courseSlotService, ProfileService $profileService)
    {
        $this->commonService = $commonService;
        $this->courseDetailService = $courseDetailService;
        $this->courseSlotService = $courseSlotService;
        $this->profileService = $profileService;
        $this->assignmentService = $assignmentService;
    }

    /**
     * Get a Single Assignment  based on uuid
     *
     * @param Request $request
     * @return void
     */
    public function getAssignment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'assignment_uuid' => 'required|exists:assignments,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and fetch  Assignment
        $result = $this->assignmentService->getAssignment($request);
        if(!$result['status']){
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $Assignment = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $Assignment);
    }

    /**
     * Delete Assignment by UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteAssignment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'assignment_uuid' => 'required|exists:assignments,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and delete Assignment
        $result = $this->assignmentService->deleteAssignment($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $Assignment = $result['data'];

        return $this->commonService->getSuccessResponse('Record Deleted Successfully', []);
    }

    /**
     * Get getAssignments on given filters
     *
     * @param Request $request
     * @return void
     */
    public function getAssignments(Request $request)
    {
        //course_id
        if(isset($request->course_uuid) && ('' != $request->course_uuid)){
            $result = $this->courseDetailService->checkCourseDetail($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $course = $result['data'];
            $request->merge(['course_id' => $course->id]);
        }

        //assignee_id
        if(isset($request->assignee_uuid) && ('' != $request->assignee_uuid)){
            $request->merge(['profile_uuid' => $request->assignee_uuid]);
            $result = $this->profileService->checkTeacher($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $assignee = $result['data'];
            $request->merge(['assignee_id' => $assignee->id]);
        }


        $result = $this->assignmentService->getAssignments($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $Assignment = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $Assignment);
    }

    /**
     * Add|Update Assignment
     *
     * @param Request $request
     * @return void
     */
    public function addUpdateAssignment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'assignment_uuid' => 'exists:assignments,uuid',
            'course_uuid' => 'required|exists:courses,uuid',
            'assignee_uuid' => 'required|exists:profiles,uuid',
            'title' => 'string',
            'description' => 'string',

            'total_marks' => 'required|numeric',
            'due_date' => 'required|date_format:Y-m-d',
            'extended_date' => 'required|date_format:Y-m-d',
            'media_1' => 'required|string',
            'media_2' => 'string',
            'media_3' => 'string',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        //course_id
        if(isset($request->course_uuid) && ('' != $request->course_uuid)){
            $result = $this->courseDetailService->checkCourseDetail($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $course = $result['data'];
            $request->merge(['course_id' => $course->id]);
        }

        //slot_id
        if(isset($request->course_slot_uuid) && ('' != $request->course_slot_uuid)){
            $result = $this->courseSlotService->checkCourseSLot($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $slot = $result['data'];
            $request->merge(['slot_id' => $slot->id]);
        }

        //assignee_id
        if(isset($request->assignee_uuid) && ('' != $request->assignee_uuid)){
            $request->merge(['profile_uuid' => $request->assignee_uuid]);
            $result = $this->profileService->checkTeacher($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $assignee = $result['data'];
            $request->merge(['assignee_id' => $assignee->id]);
        }
        // find  Assignment by uuid if given
        $assignment_id = null;
        if(isset($request->assignment_uuid) && ('' != $request->assignment_uuid)){
            $result = $this->assignmentService->checkAssignment($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $assignment = $result['data'];
            $assignment_id = $assignment->id;
        }

        //correct_Assignment_id

        $result = $this->assignmentService->addUpdateAssignment($request, $assignment_id);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $assignment = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $assignment);
    }
}
