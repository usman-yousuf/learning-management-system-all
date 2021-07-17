<?php

namespace Modules\Student\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Common\Entities\Stats;
use Modules\Common\Services\NotificationService;
use Modules\Common\Services\StatsService;
use Modules\Course\Entities\Course;
use Modules\Course\Services\CourseCategoryService;
use Modules\Course\Services\CourseDetailService;
use Modules\Payment\Services\PaymentHistoryService;
use Modules\Student\Entities\Review;
use Modules\User\Entities\StudentCourse;
use Modules\User\Services\ProfileService;

class StudentCourseEnrollmentService
{
    private $paymentHistoryService;

    public function __construct(PaymentHistoryService $paymentHistoryService)
    {
        $this->paymentHistoryService = $paymentHistoryService;
    }

    /**
     * Check if an Student Course Exists given ID
     *
     * @param Integer $id
     * @return void
     */
    public function getStudentCourseById($id)
    {
        $model =  StudentCourse::where('id', $id)->first();
        if(null == $model){
            return getInternalErrorResponse('No Student Course Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check Enrollment
     *
     * @param Integer $student_id
     * @param Integer $course_id
     * @return void
     */
    public function checkEnrollment($student_id, $course_id)
    {
        $model =  StudentCourse::where('student_id', $student_id)->where('course_id', $course_id)->first();
        if(null == $model){
            return \getInternalErrorResponse('No Student Course Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check and fetch and Student Course against given ID
     *
     * @param Integer $id
     * @return void
     */
    public function checkStudentCourseById($id)
    {
        $model =  StudentCourse::where('id', $id)->first();
        if(null == $model){
            return getInternalErrorResponse('No Student Course Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check if an Student Course Exists against given $request->student_course_uuid
     *
     * @param Request $request
     * @return void
     */
    public function checkStudentCourse(Request $request)
    {
        $model = StudentCourse::where('uuid', $request->student_course_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No Student Course Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Get an Student Course against given UUID
     *
     * @param Request $request
     * @return void
     */
    public function getStudentCourse(Request $request)
    {
        $model = StudentCourse::where('uuid', $request->student_course_uuid)->first();
        return getInternalSuccessResponse($model);
    }

    /**
     * Delete an Student Course by given UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteStudentCourse(Request $request)
    {
        $model = StudentCourse::where('uuid', $request->student_course_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No Student Course Found', [], 404, 404);
        }

        try{
            $model->delete();
            $result =  $this->updateEnrollmentStats($model->course_id, $model->student_id, $model->course->is_course_free, $model->course->nature, $mode="minus");
            if(!$result['status'])
            {
                return $result;
            }
        }
        catch(\Exception $ex)
        {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode(), 500);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Delete an Student Course by given UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteEnrollment(Request $request)
    {
        $models = StudentCourse::
            where('student_id', $request->student_id)
            ->where('course_id', $request->course_id)->first();
        try {
            $models->delete();
                //update Stats
                $result =  $this->updateEnrollmentStats($models->course_id, $models->student_id, $models->course->is_course_free, $models->course->nature, $mode="minus");
                if(!$result['status'])
                {
                    return $result;
                }
        }
        catch(\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode(), 500);
        }
        return getInternalSuccessResponse();
    }

    /**
     * Get Student Course based on filters
     *
     * @param Request $request
     * @return void
     */
    public function getStudentCourses(Request $request)
    {
        // DB::enableQueryLog();

        $models = StudentCourse::orderBy('created_at');

        //student_course_uuid
        if(isset($request->student_course_uuid) && ('' != $request->student_course_uuid)){
            $models->where('uuid', $request->student_course_uuid);
        }

        // dd($request->all());
        //course_uuid
        if(isset($request->course_id) && ('' != $request->course_id)){
            $models->where('course_id', $request->course_id);
        }

        // student_id
        if(isset($request->student_id) && ('' != $request->student_id)){
            $models->where('student_id', $request->student_id);
        }
        // parent_id
        if (isset($request->parent_id) && ('' != $request->parent_id)) {
            $models->where('parent_id', $request->parent_id);
        }

       // status
       if (isset($request->status) && ('' != $request->status)) {
        $models->where('status', $request->status);
        }
        // joining_date
        if(isset($request->joining_date) && ('' != $request->joining_date)){
            $models->where('joining_date', '=', "{$request->joining_date}");
        }

        if($request->is_date_range == true)
        {
            if(isset($request->startdate) && ('' != $request->startdate) && isset($request->enddate) && ('' != $request->enddate) )
            {
                $startDate = date('Y-m-d H:i:s', strtotime($request->startdate.' 00:00:00'));
                $endDate = date('Y-m-d H:i:s', strtotime($request->enddate.' 23:59:59'));
                $models->whereBetween('created_at', [$startDate, $endDate]);
            }
        }
        $cloned_models = clone $models;
        if(isset($request->offset) && isset($request->limit)){
            $models->offset($request->offset)->limit($request->limit);
        }

        $data['enrollment'] = $models->whereHas('course', function($query) use ($request){
            if(isset($request->nature) && ('' != $request->nature))
            {
                $query->where('nature', 'LIKE',  "%$request->nature%");
            }

            if(isset($request->course_title) && ('' != $request->course_title))
            {
                $query->where('title', 'LIKE',  "%$request->course_title%");
            }
        })->with('parent','student', 'slot','course')->get();
        // dd(DB::getQueryLog());
        $data['total_count'] = $cloned_models->count();

        return getInternalSuccessResponse($data);
    }

    public function getStudentEnrolledCourseIds(Request $request)
    {
        // DB::enableQueryLog();
        $models = StudentCourse::orderBy('created_at', 'DESC');

        // course_id
        if (isset($request->course_id) && ('' != $request->course_id)) {
            $models->where('course_id', $request->course_id);
        }

        // slot_id
        if (isset($request->slot_id) && ('' != $request->slot_id)) {
            $models->where('slot_id', $request->slot_id);
        }

        // student_id
        if (isset($request->student_id) && ('' != $request->student_id)) {
            $models->where('student_id', $request->student_id);
        }

        $models = $models->with(['course'])->get();

        $enrolledCoursesIds = [];
        if ($models->count()) {
            foreach ($models as $model) {
                // if($model->course->approver_id != null){
                    $enrolledCoursesIds[] = $model->course_id;
                // }
            }
        }

        return getInternalSuccessResponse($enrolledCoursesIds);
    }

    /**
     * get Course a Student has Enrolled in
     *
     * @param Request $request
     * @return void
     */
    public function getStudentEnrolledCourses(Request $request)
    {
        $result = $this->getStudentEnrolledCourseIds($request);
        if(!$result['status']){
            return $result;
        }
        $coursesIds = $result['data'];
        $request->merge(['bulk_fetch_course_ids' => $coursesIds]);

        $courseService = new CourseDetailService();
        $result = $courseService->getCourses($request);
        if(!$result['status']){
            return $result;
        }
        $courses = $result['data'];
        return getInternalSuccessResponse($courses);

    }

    /**
     * get Courses Suggestions for a Student
     *
     * @param Request $request
     * @return void
     */
    public function getSuggestedCourses(Request $request)
    {
        unset($request['bulk_fetch_course_ids']); // in case it is already set

        // get Student Enrolled Courses
        $result = $this->getStudentEnrolledCourseIds($request);
        if (!$result['status']) {
            return $result;
        }
        $enrolledCoursesIds = $result['data'];

        // get Categories of courses based on student interest
        $courseCategoryService = new CourseCategoryService();
        $result = $courseCategoryService->getBulkCategoryIdsByStudentInterest($request);
        if (!$result['status']) {
            return $result;
        }
        $categoryIds = $result['data'];
        // dd($categoryIds);

        // get courses against those categories
        $request->merge(['bulk_category_ids' => $categoryIds]);
        $courseService = new CourseDetailService();
        $result = $courseService->getCourses($request);
        if (!$result['status']) {
            return $result;
        }
        $interestedCoursesData = $result['data'];

        // get ids of interested courses
        $interestedCoursesIds = [];
        if($interestedCoursesData['total_count']){
            foreach ($interestedCoursesData['courses'] as $course) {
                $interestedCoursesIds[] = $course->id;
            }
        }
        // dd($interestedCoursesIds, $enrolledCoursesIds);
        $fetchable_courses_Ids = array_diff($interestedCoursesIds, $enrolledCoursesIds);
        // dd($fetchable_courses_Ids);
        unset($request['bulk_category_ids']); // we need only relavent filters
        $request->merge(['bulk_fetch_course_ids' => $fetchable_courses_Ids]); // in case it is already set

        // dd($request->all());
        $result = $courseService->getCourses($request);
        if (!$result['status']) {
            return $result;
        }
        $courses = $result['data'];
        // dd($courses);
        return getInternalSuccessResponse($courses);

        // // $interestedCoursesData['data'];
        // dd($interestedCoursesData);
        // // dd(\DB::getQueryLog());
        // if (!$result['status']) {
        //     return $result;
        // }
        // $courses = $result['data'];




        // dd($coursesIds, $request['bulk_fetch_course_ids']);
        // $request['bulk_fetch_course_ids'] = array_diff($request['bulk_fetch_course_ids'], $coursesIds);
        // dd($request['bulk_fetch_course_ids']);
        // $courseService = new CourseDetailService();
        // // \DB::enableQueryLog();

        // $result = $courseService->getCourses($request);
        // // dd(\DB::getQueryLog());
        // dd($result);
        // if (!$result['status']) {
        //     return $result;
        // }
        $courses = $result['data'];
        return getInternalSuccessResponse($courses);
    }

    /**
     * Get Enrolled Courses
     *
     * @param Request $request
     * @return void
     */
    public function getEnrolledCoursesSlots(Request $request)
    {
        // DB::enableQueryLog();
        $models = StudentCourse::orderBy('created_at');

        // course_id
        if (isset($request->course_id) && ('' != $request->course_id)) {
            $models->where('course_id', $request->course_id);
        }

        // slot_id
        if (isset($request->slot_id) && ('' != $request->slot_id)) {
            $models->where('slot_id', $request->slot_id);
        }

        // student_id
        if (isset($request->student_id) && ('' != $request->student_id)) {
            $models->where('student_id', $request->student_id);
        }

        $models = $models->with(['course'])->get();

        return getInternalSuccessResponse($models);

    }

    public function getEnrolledSlots(Request $request)
    {
        $result = $this->getEnrolledCoursesSlots($request);
        if (!$result['status']) {
            return $result;
        }
        $models = $result['data'];

        $slots = [];
        if ($models->count()) {
            foreach ($models as $model) {
                if(null != $model->slot){
                    $slots[] = $model->slot;
                }
            }
        }

        $data['slots'] = $slots;
        $data['total_slots'] = count($slots);

        return getInternalSuccessResponse($data);
    }

    /**
     * get Teachers Ids for courses a student has Enrolled
     *
     * @param Request $request
     * @return void
     */
    public function getEnrolledCourseTeachersId(Request $request)
    {
        $result = $this->getEnrolledCoursesSlots($request);
        if(!$result['status']){
            return $result;
        }
        $models = $result['data'];

        $teacherIds = [];
        if($models->count()){
            foreach($models as $model){
                $teacherIds[] = $model->course->teacher->id;
            }
        }
        $teacherIds = array_unique($teacherIds);
        // dd($teacherIds);
        // dd(DB::getQueryLog());

        return getInternalSuccessResponse($teacherIds);
    }

    /**
     * Add|Update Student Course
     *
     * @param Request $request
     * @param Integer $student_course_id
     * @return void
     */
    public function addUpdateStudentCourse(Request $request, $student_course_id = null)
    {
        if (null == $student_course_id) {
            $model = new StudentCourse();
            $model->uuid = \Str::uuid();
            $model->created_at = date('Y-m-d H:i:s');
        } else {
            $model = StudentCourse::where('id', $student_course_id)->first();
        }
        $model->updated_at = date('Y-m-d H:i:s');
        $model->course_id = $request->course_id;
        $model->student_id = $request->student_id;
        $model->status = $request->status;
        $model->joining_date = $request->joining_date;

        // slot_id
        if(isset($request->slot_id) && ('' != $request->slot_id)){
            $model->slot_id = $request->slot_id;
        }
        if(isset($request->status) && ('' != $request->status)){
            $model->status = $request->status;
        }
        // parent_id
        if (isset($request->parent_id) && ('' != $request->parent_id)) {
            $model->status = $request->parent_id;
        }

        try {
            $model->save();
            // dd($model);
            $model = StudentCourse::where('id', $model->id)->with([
                'parent',
                'student',
                'course',
                'slot',
            ])->first();

            // validate and save Payment
            $request->merge([
                'ref_id' => $model->id
                , 'ref_model_name' => 'student_courses'

                , 'additional_ref_id' => $model->course->id
                , 'additional_ref_model_name' => 'courses'

                , 'payee_id' => $request->student_id

                , 'additional_ref_id' => (isset($request->parent_id) && ('' != $request->parent_id))? $request->parent_id : ''
                , 'additional_ref_model_name' => (isset($request->parent_id) && ('' != $request->parent_id))? 'profiles' : ''
            ]);
            $result = $this->paymentHistoryService->addUpdatePaymentHistory($request);
            if(!$result['status']){
                return $result;
            }
            $payment = $result['data'];

            if($student_course_id ==  null)
            {
                //update Stats
                $result =  $this->updateEnrollmentStats($model->course_id, $model->student_id, $model->course->is_course_free, $model->course->nature);
                if(!$result['status']){
                    return $result;
                }

                //notification send to teacher
                $notiService = new NotificationService();
                $receiverIds = [$model->course->teacher_id];
                $request->merge([
                    'notification_type' => listNotficationTypes()['enrolled_course'], 'notification_text' => getNotificationText($request->user()->profile->first_name, 'enrolled_course'), 'notification_model_id' => $model->id, 'notification_model_uuid' => $model->uuid, 'notification_model' => 'student_courses', 'additional_ref_id' => $model->course->id, 'additional_ref_uuid' => $model->course->uuid, 'additional_ref_model_name' => 'courses'
                ]);
                $notiService->sendNotifications($receiverIds, $request, true);
            }
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }

    /**
     *  Update Enrollment Stats
     * @param Request $request
     *
     * @return void
     */
    public function updateEnrollmentStats($course_id, $student_id, $is_free, $nature, $mode="add")
    {
        $data = [];

        $statsObj = new StatsService();
        $result =  $statsObj->updateEnrolledStudentStats($is_free, $mode);
        if(!$result['status']) {
            return $result;
        }
        $data['stats'] = $result['data'];


        // update Course Stats
        $courseObj = new CourseDetailService();
        $result =  $courseObj->updateCourseStats($course_id, $is_free, $mode);
        if(!$result['status']) {
            return $result;
        }
        $data['course'] = $result['data'];

        // update profile meta
        $profileObj = new ProfileService();
        $result = $profileObj->updateCourseStudentMetaStats($student_id,$mode);
        if(!$result['status']) {
            return $result;
        }
        $data['course'] = $result['data'];
        return getInternalSuccessResponse($data);

    }

    /**
     * Check slot booking
     *
     * @param Request $request
     * @return void
     */
    public function checkSlotBooking(Request $request)
    {
        // dd($request->all());
        $model = StudentCourse::where('slot_id', $request->slot_id)->where('course_id', $request->course_id)->first();
        if(null != $model)
        {
            if($model->student_id == $request->student_id){
                return getInternalErrorResponse('You cannot update your enrollment info', $model, 403, 403);
            }
            return getInternalErrorResponse('Slot is booked against a Student', $model, 404, 404);
        }
        return getInternalSuccessResponse('slot is available');
    }

    /**
     * Get Enrollment Data
     *
     * @param Request $request
     * @return void
     */
    public function getEnrollmentPaymentGraphData(Request $request)
    {
        $result = $this->paymentHistoryService->getEnrollmentPaymentGraphData($request);
        // return $result;
        if(!$result['status']){
            return $result;
        }
        $paymentHistory = $result['data'];
        $data = [];

        if($paymentHistory['total']){
            foreach ($paymentHistory['models'] as $payment) {
                $month = date('M-Y', strtotime($payment['payment_history_created_at']));
                if(!isset($data[$month]['revenue'])){
                    $data[$month]['revenue'] = 0;
                }
                $data[$month]['revenue'] += $payment['payment_history_amount'];

                if (!isset($data[$month]['online'])) {
                    $data[$month]['online'] = 0;
                }
                if (!isset($data[$month]['video'])) {
                    $data[$month]['video'] = 0;
                }
                if($payment['course_nature'] == 'online'){
                    $data[$month]['online'] += $payment['payment_history_amount'];
                }
                else{
                    $data[$month]['video'] += $payment['payment_history_amount'];
                }
            }
        }
        return getInternalSuccessResponse($data, 'Payment History Data Fetched Successfully');
    }



}
