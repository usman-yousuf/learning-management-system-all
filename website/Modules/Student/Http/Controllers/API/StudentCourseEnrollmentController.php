<?php

namespace Modules\Student\Http\Controllers\API;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Common\Services\CommonService;
use Modules\Course\Services\CourseDetailService;
use Modules\Course\Services\CourseSlotService;
use Modules\Student\Services\StudentCourseEnrollmentService;
use Modules\User\Services\ProfileService;

class StudentCourseEnrollmentController extends Controller
{
    private $commonService;
    private $courseDetailService;
    private $profileService;
    private $studentCourseService;
    private $courseSlotService;

    public function __construct(CommonService $commonService, StudentCourseEnrollmentService $studentCourseService, CourseDetailService $courseDetailService, ProfileService $profileService, CourseSlotService $courseSlotService)
    {
        $this->commonService = $commonService;
        $this->studentCourseService = $studentCourseService;
        $this->courseDetailService = $courseDetailService;
        $this->profileService = $profileService;
        $this->courseSlotService = $courseSlotService;
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
            'enrollment_uuid' => 'required',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }
        // validate and remove Student Course Enroll
        $request->merge(['student_course_uuid' => $request->enrollment_uuid]);
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

        //slot_uuid
        if (isset($request->slot_uuid) && ('' != $request->slot_uuid)) {
            $request->merge(['profile_uuid' => $request->slot_uuid]);
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

    public function getStudentEnrolledCourses(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_uuid' => 'exists:courses,uuid',
            'student_uuid' => 'exists:profiles,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        //student_uuid
        if (isset($request->student_uuid) && ('' != $request->student_uuid)) {
            $request->merge(['profile_uuid' => $request->student_uuid]);

            $result = $this->profileService->checkStudent($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $student = $result['data'];
            $request->merge(['student_id' => $student->id]);
        }

        $result = $this->studentCourseService->getStudentEnrolledCourses($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $data = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $data);
    }

    /**
     * Listing Course Student Enroll based on given filters
     *
     * @param Request $request
     * @return void
     */
    public function getStudentCourses(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_uuid' => 'exists:courses,uuid',
            'student_uuid' => 'exists:profiles,uuid',
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

            $result = $this->profileService->checkStudent($request);
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
     * Get data for Graphs in Dashboard page
     *
     * @param Request $request
     * @return void
     */
    public function getEnrollmentPaymentGraphData(Request $request)
    {
        $result = $this->studentCourseService->getEnrollmentPaymentGraphData($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }

        $foundData = $result['data'];
        $data = [];
        if(!empty($foundData)){
            $data['months'] = array_keys($foundData);

            foreach ($foundData as $month => $arr) {
                foreach ($arr as $key => $amount) {
                    if('online' == $key){
                        $data['online_courses'][] = (float)$amount;
                    }
                    else if('video' == $key){
                        $data['video_courses'][] = (float)$amount;
                    }
                    else if ('revenue' == $key) {
                        $data['total_revenue'][] = (float)$amount;
                    }
                }
            }
        }
        return $this->commonService->getSuccessResponse('Graph Data Fetched Successfully', $data);
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

            // 'status' => 'required|string',
            'joining_date' => 'required|date_format:Y-m-d H:i:s',


            // slot_uuid

            'amount' => 'required|numeric',
            'stripe_trans_id' => 'string',
            'stripe_trans_status' => 'string',
            'card_uuid' => 'string|exists:cards,uuid',
            'easypaisa_trans_id' => 'string',
            'easypaisa_trans_status' => 'string',
            'payment_method' => 'required|string',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate course_uuid
        $result = $this->courseDetailService->getCourseDetail($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $course = $result['data'];
        $request->merge(['course_id' => $course->id]);

        // validate if slot is given against a course for enrollment
        if($course->nature == 'online')
        {
            // validate slot_uuid
            if (isset($request->slot_uuid) && ('' != $request->slot_uuid)) {
                $request->merge(['course_slot_uuid' => $request->slot_uuid]);
                $result = $this->courseSlotService->checkCourseSLot($request);
                if (!$result['status']) {
                    return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
                }
                $slot = $result['data'];
                $request->merge(['slot_id' => $slot->id]);

                if($slot->course_id != $course->id){
                    return $this->commonService->getValidationErrorResponse('slot_uuid is invalid', [
                        ['slot_uuid' => 'Slot is Invalid']
                    ]);
                }
            }
            else{
                return $this->commonService->getValidationErrorResponse('slot_uuid is required', [
                    ['slot_uuid' => 'Slot is Requird']
                ]);
            }
        }
        else{
            unset($request['slot_uuid']);
        }

        // validate student_uuid
        if(isset($request->student_uuid) && ('' != $request->student_uuid)){
            $request->merge(['profile_uuid' => $request->student_uuid]);
            $result = $this->profileService->checkStudent($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $student = $result['data'];
            $request->merge(['student_id' => $student->id]);
        }

        // validate Student Course ID by uuid
        $student_course_id = null;
        if(isset($request->enrollment_uuid) && ('' == $request->enrollment_uuid)) {
            $request->merge(['student_course_uuid' => $request->enrollment_uuid]);
            $result = $this->studentCourseService->checkStudentCourse($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $student_course = $result['data'];
            $student_course_id = $student_course->id;
        }
        else { // validate if slot is booked against given course
            $result = $this->studentCourseService->checkSlotBooking($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
        }


        // add|update enrollment
        DB::beginTransaction();
        $request->merge([
            'status' =>'active',
        ]);
        $result = $this->studentCourseService->addUpdateStudentCourse($request, $student_course_id);
        if (!$result['status']) {
            DB::rollBack();
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $course_slot = $result['data'];

        DB::commit();
        return $this->commonService->getSuccessResponse('Success', $course_slot);

        // find Student Course by uuid
        // $student_course_id = null;
        // $request->merge(['student_course_uuid' => $request->enrollment_uuid]);
        // $result = $this->studentCourseService->checkStudentCourse($request);
        // if (!$result['status']) {
        //     return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        // }
        // $student_course = $result['data'];
        // $student_course_id = $student_course->id;

        // $result = $this->studentCourseService->addUpdateStudentCourse($request, $student_course_id);
        // if (!$result['status']) {
        //     return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        // }
        // $course_slot = $result['data'];

        // return $this->commonService->getSuccessResponse('Success', $course_slot);
    }
}
