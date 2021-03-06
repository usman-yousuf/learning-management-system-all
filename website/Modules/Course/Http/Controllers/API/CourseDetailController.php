<?php

namespace Modules\Course\Http\Controllers\API;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Common\Services\CommonService;
use Modules\Course\Services\CourseCategoryService;
use Modules\Course\Services\CourseDetailService;
use Modules\Student\Services\StudentCourseEnrollmentService;
use Modules\User\Services\ProfileService;

class CourseDetailController extends Controller
{
    private $commonService;
    private $courseDetailService;
    private $enrollmentService;
    private $profileService;
    private $categoryService;

    public function __construct(CommonService $commonService, CourseDetailService $courseDetailService, ProfileService $profileService, CourseCategoryService $categoryService, StudentCourseEnrollmentService $enrollmentService )
    {
        $this->commonService = $commonService;
        $this->courseDetailService = $courseDetailService;
        $this->enrollmentService = $enrollmentService;
        $this->profileService = $profileService;
        $this->categoryService = $categoryService;
    }

    /**
     * Get a Course Detail based on uuid
     *
     * @param Request $request
     * @return void
     */
    public function getCourseDetail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_uuid' => 'required',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and fetch Course Detail
        $result = $this->courseDetailService->checkCourseDetail($request);
        if(!$result['status']){
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $courseDetail = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $courseDetail);
    }

    /**
     * Delete an Course Detail by UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteCourseDetail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_uuid' => 'required|exists:courses,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and delete an Address
        $result = $this->courseDetailService->deleteCourseDetail($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $courseDetail = $result['data'];

        return $this->commonService->getSuccessResponse('Record Deleted Successfully', []);
    }

    /**
     * Get list of Course Detail based on given filters
     *
     * @param Request $request
     * @return void
     */
    public function getCourseDetails(Request $request)
    {
        // dd($request->all());
        if(isset($request->teacher_uuid) && ('' != $request->teacher_uuid)){
            $result = $this->profileService->checkTeacher($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $teacher = $result['data'];
            $request->merge(['teacher_id' => $teacher->id]);
        }

        if(isset($request->course_category_uuid) && (''!= $request->course_category_uuid)) {
            $result = $this->categoryService->checkCourseCateogry($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $category = $result['data'];
            $request->merge(['course_category_id' => $category->id]);
        }

        $result = $this->courseDetailService->getCourses($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $courses = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $courses);
    }

    /**
     * Check a course details if found or return  error response
     *
     * @param Request $request
     *
     * @return void
     */
    public function checkCourseDetails(Request $request)
    {
        $result = $this->courseDetailService->checkCourseDetail($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $model = $result['data'];
        if(($request->user() != null) && ($request->user()->profile->approver_id != null)){
            if ($model->approver_id == null) {
                return $this->commonService->getNotApprovedErrorResponse('Please wait while your course is approved', $model);
            }
        }

        return $this->commonService->getSuccessResponse('Success', $model);
    }

    /**
     * Add|Update Course Detail
     *
     * @param Request $request
     * @return void
     */
    public function updateCourseDetail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_uuid' => 'exists:courses,uuid',
            'course_category_uuid' => 'required',
            'teacher_uuid' => 'required',
            'description' => 'string',
            'course_image' => 'string',
            'nature' => 'required|string',
            'is_course_free' => 'required|in:0,1',
            'is_handout_free' => 'required_if:is_course_free,0|in:0,1',
            // 'price_usd' => 'required_if:is_course_free,0',
            // 'discount_usd' => 'required_if:is_course_free,0',
            // 'price_pkr' => 'required_if:is_course_free,0',
            // 'discount_pkr' => 'required_if:is_course_free,0',
            'price' => 'required_if:is_course_free,0',
            'discount' => 'required_if:is_course_free,0',
            'total_duration' => 'numeric',
            'is_approved' => 'in:0,1',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        //  course_category_uuid

        if(isset($request->course_category_uuid) && (''!= $request->course_category_uuid)) {
            $result = $this->categoryService->checkCourseCateogry($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $category = $result['data'];
            $request->merge(['course_category_id' => $category->id]);
        }
        // teacher_uuid
        if(isset($request->teacher_uuid) && (''!= $request->teacher_uuid)) {
            $request->merge(['profile_uuid'=> $request->teacher_uuid]);
            $result = $this->profileService->checkTeacher($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $teacher = $result['data'];
            $request->merge(['teacher_id' => $teacher->id]);
        }

        // find courses by uuid if given
        $course_id = null;
        if(isset($request->course_uuid) && ('' != $request->course_uuid)){
            $result = $this->courseDetailService->checkCourseDetail($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $course = $result['data'];
            $course_id = $course->id;
        }

        DB::beginTransaction();
        $result = $this->courseDetailService->addUpdateCourseDetail($request, $course_id);
        if (!$result['status']) {
            DB::rollBack();
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $course = $result['data'];
        DB::commit();

        return $this->commonService->getSuccessResponse('Success', $course);
    }

    /**
     * Get Course Slots by Course UUID
     *
     * @param Request $request
     *
     * @return void
     */
    public function getCourseWithOnlyRelationsByCourse(Request $request)
    {
        $result = $this->courseDetailService->checkCourseDetail($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $course = $result['data'];
        $slots = $course->slots;
        $course->slots = [];
        // unset($course['slots']);
        foreach ($slots as $index => $slot) {
            if(!$slot->enrolments_count){
                unset($slots[$index]);
            }
        }
        $course->slots = $slots;

        return $this->commonService->getSuccessResponse('Course Relations Fetched Successfully', $course);
    }

    /**
     * get All Slots againt a teacher
     *
     * @param Request $request
     *
     * @return void
     */
    public function getTeacherCourseSlots(Request $request)
    {
        // validation rules
        $validator = Validator::make($request->all(), [
            'teacher_uuid' => 'required|exists:profiles,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }
        $request->merge(['profile_uuid' => $request->teacher_uuid]);


        // validate teacher
        $result = $this->profileService->checkTeacher($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $teacher = $result['data'];


        // fetch teacher courses
        $result = $this->courseDetailService->getCoursesOnlyByTeacherId($teacher->id, 'online');
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $coursesData = $result['data'];

        $slots = [];
        if($coursesData['total_models']) {
            foreach($coursesData['models'] as $model) {
                if($model->slots->count()){
                    foreach($model->slots as $slot){
                        $slots[] = $slot;
                    }
                }
            }
        }
        $data['slots'] = $slots;
        $data['total_slots'] = count($slots);

        return $this->commonService->getSuccessResponse('Teacher All Courses Slots Fetched Successfully', $data);
    }

    /**
     * Get All Slots a student is enrolled in
     *
     * @param Request $request
     * @return void
     */
    public function getStudentCourseSlots(Request $request)
    {
        // validation rules
        $validator = Validator::make($request->all(), [
            'student_uuid' => 'required|exists:profiles,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }
        $request->merge(['profile_uuid' => $request->student_uuid]);


        // validate teacher
        $result = $this->profileService->checkStudent($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $student = $result['data'];
        $student_id = $student ? $student->id : '';
        $request->merge(['student_id' => $student_id]);

        // get enrolled slots
        $result = $this->enrollmentService->getEnrolledSlots($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $data = $result['data'];
        return $this->commonService->getSuccessResponse('Student All Courses Slots Fetched Successfully', $data);
    }

    /**
     *
     * Admin Approve  Courses
     *
     */
    public function adminApproveCourse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_uuid' => 'required|exists:courses,uuid',
        ]);

        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        //check if logged in user is Admin
        $request->merge(['profile_uuid' => $request->user()->profile->uuid]);
        $result = $this->profileService->checkAdmin($request);
        if(!$result['status'])
        {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }

        //check if the course exist
        $result = $this->courseDetailService->checkCourseDetail($request);
        if(!$result['status'])
        {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $course = $result['data'];
        $request->merge(['course_id' => $course->id]);

        //Approve
        \DB::beginTransaction();
        $result = $this->courseDetailService->approveCourse($request);
        if(!$result['status'])
        {
            \DB::rollback();
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $course = $result['data'];

        \DB::commit();
        return $this->commonService->getSuccessResponse('Success', $course);
    }

    /**
     * Reject a course by teacher [ADMIN ONLY]
     *
     * @param Request $request
     * @return void
     */
    public function adminRejectCourse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_uuid' => 'exists:courses,uuid',
            'rejection_description' => 'string',
        ]);

        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        //check if logged in user is Admin
        // dd($request->user()->profile->id);
        $request->merge(['profile_uuid' =>  $request->user()->profile->uuid]);
        $result = $this->profileService->checkAdmin($request);
        if (!$result['status']) {
            return $this->commonService->getNotAuthorizedResponse('You are not Authorized to perform this action');
        }

        // //check if the teacher id is valid
        $result = $this->courseDetailService->checkCourseDetail($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $course_id = $result['data']->id;
        $request->merge(['course_id', $course_id]);

        //Approve
        \DB::beginTransaction();
        $result = $this->courseDetailService->rejectCourse($request, $course_id);
        if (!$result['status']) {
            \DB::rollback();
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $rejected = $result['data'];
        // \DB::commit();

        return $this->commonService->getSuccessResponse('Course Rejected Successfully', $rejected);
    }
}
