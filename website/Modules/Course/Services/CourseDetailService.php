<?php

namespace Modules\Course\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Common\Services\CommonService;
use Modules\Common\Services\NotificationService;
use Modules\Common\Services\StatsService;
use Modules\Course\Entities\Course;
use Modules\Course\Entities\CourseCategory;

// use Modules\Course\Entities\Course;

class CourseDetailService
{

    private $relations = [
        'teacher'
        , 'category'
        , 'contents'
        , 'handouts'
        , 'outlines'
        , 'slots'
        , 'availableSlots'
        , 'enrolledStudents'
        , 'lastEnrollment'
        , 'reviews'
        , 'queries'
        , 'studentQueries'
        , 'quizzez'
    ];

    /**
     * get Course Models by teacher_id
     *
     * @param Integer $teacher_id
     *
     * @return Array[][] [models, total_models]
     */
    public function getCoursesOnlyByTeacherId($teacher_id = null, $nature = null, $sortOrder = 'DESC')
    {
        $models = Course::where('teacher_id', $teacher_id)->whereNotNull('approver_id');
        if(null != $nature){
            $models->where('nature', $nature);
        }
        $models->with(['slots'])->orderBy('created_at', $sortOrder);
        $cloned_models = clone $models;

        $data['models'] = $models->get();
        $data['total_models'] = $cloned_models->count();

        return getInternalSuccessResponse($data);
    }

    /**
     * Get All Students Ids of a teacher
     *
     * @param Request $request
     * @return void
     */
    public function getTeacherStudentsId(Request $request)
    {
        // DB::enableQueryLog();
        $models = Course::where('teacher_id', $request->profile_id)->whereNotNull('approver_id');
        // further filters
        $models = $models->with(['enrolledStudents'])->get();

        $studentIds = [];
        if($models->count()){
            foreach($models as $model){
                $studentIds = array_merge($studentIds, getCourseEnrolledStudentsIds($model));
            }
            $studentIds = array_unique($studentIds);
        }
        // dd($studentIds);
        // dd(DB::getQueryLog());

        return getInternalSuccessResponse($studentIds);
    }

    /**
     * Approve a teacher ourse - ADMIN ONLY
     *
     * @param Request $request
     *
     * @return void
     */
    public function approveCourse(Request $request)
    {
        try {
            Course::where('id', $request->course_id)->update([
                'approver_id' => $request->user()->profile_id,
                'is_approved' => (int)true,
            ]);
            $model = Course::where('id', $request->course_id)->first();

            $notiService = new NotificationService();
            $receiverIds = [$model->teacher_id];
            $request->merge([
                'notification_type' => listNotficationTypes()['approve_course']
                , 'notification_text' => getNotificationText($request->user()->profile->first_name, 'approve_course')
                , 'notification_model_id' => $model->id
                , 'notification_model_uuid' => $model->uuid
                , 'notification_model' => 'courses'

                , 'additional_ref_id' => null
                , 'additional_ref_uuid' => null
                , 'additional_ref_model_name' => null

                , 'is_activity' => false
                , 'start_date' => null
                , 'end_date' => null
            ]);
            $result =  $notiService->sendNotifications($receiverIds, $request, true);
            if(!$result['status'])
            {
                return $result;
            }

            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            // dd($ex);
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }

    /**
     * Approve a teacher ourse - ADMIN ONLY
     *
     * @param Request $request
     *
     * @return void
     */
    public function rejectCourse(Request $request)
    {
        try {
            Course::where('uuid', $request->course_uuid)->update([
                'approver_id' => null,
                'is_approved' => (int)false,
            ]);
            $model = Course::where('uuid', $request->course_uuid)->first();

            $notiService = new NotificationService();
            $receiverIds = [$model->teacher_id];
            $request->merge([
                'notification_type' => listNotficationTypes()['approve_course']
                , 'notification_text' => getNotificationText($request->user()->profile->first_name
                , 'approve_course'), 'notification_model_id' => $model->id
                , 'notification_model_uuid' => $model->uuid
                , 'notification_model' => 'courses'
                , 'additional_ref_id' => null
                , 'additional_ref_uuid' => null
                , 'additional_ref_model_name' => null
                , 'is_activity' => false
                , 'start_date' => null
                , 'end_date' => null
            ]);
            $result =  $notiService->sendNotifications($receiverIds, $request, true);
            if (!$result['status']) {
                return $result;
            }

            $commonService = new CommonService();
            $result = $commonService->sendTeacherCourseRejectionEmail($model->teacher->user->email, 'Course Rejected', 'authall::email_template.admin_reject_teacher_course_approval', ['email' => $model->teacher->user->email, 'reason' => $request->rejection_description]);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }

            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            // dd($ex);
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }

    /**
     * Check if an Course detail Exists given ID
     *
     * @param Integer $id
     * @return void
     */
    public function getCourseDetailById($id)
    {
        $model =  Course::where('id', $id)->first();
        if(null == $model){
            return getInternalErrorResponse('No Course detail Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check and fetch and Course detail against given ID
     *
     * @param Integer $id
     * @return void
     */
    public function checkCourseDetailById($id)
    {
        $model =  Course::where('id', $id)->first();
        if(null == $model){
            return getInternalErrorResponse('No Course detail Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check if an Course content Exists against given $request->course_uuid
     *
     * @param Request $request
     * @return void
     */
    public function checkCourseDetail(Request $request)
    {
        $model = Course::where('uuid', $request->course_uuid);
        if(isset($request->only_relations) && (!empty($request->only_relations))){
            $model->with($request->only_relations);
        }
        else{
            $model->with($this->relations);
        }

        $model = $model->first();
        if (null == $model) {
            return getInternalErrorResponse('No Course Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Get an Course Detail against given UUID
     *
     * @param Request $request
     * @return void
     */
    public function getCourseDetail(Request $request)
    {
        $model = Course::where('uuid', $request->course_uuid)->with($this->relations)
        ->first();

        return getInternalSuccessResponse($model);
    }

    /**
     * Delete an Course detial by given UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteCourseDetail(Request $request)
    {
        $model = Course::where('uuid', $request->course_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No Course Found', [], 404, 404);
        }

        try{
            $model->delete();
            $this->updateStats($model->nature, $model->is_coruse_free, $mode="minus", $mode="minus");
        }
        catch(\Exception $ex)
        {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode(), 500);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Get list Courses detail based on filters
     *
     * @param Request $request
     * @return void
     */
    public function getCourses(Request $request)
    {
        $specific_columns = $request->specific_columns;

        // \DB::enableQueryLog();
        $models = Course::orderBy('created_at', 'DESC');

        if(null != $request->user()){
            if(($request->user()->profile_type == 'parent') || ($request->user()->profile_type == 'student')){
                $models->whereNotNull('approver_id');
            }
        }

        if (isset($request->specific_columns) && ('' != $request->specific_columns)) {
            $models->select($specific_columns);
        }

        // popular courses
        if (isset($request->is_popular) && ('' != $request->is_popular)) {
            $models->orderBy('rating', 'DESC');
        }

        // teacher should see his own data
        if(!isset($request->approved_only) || ('1' != $request->approved_only)){
            if($request->user() != null){
                if ('admin' != $request->user()->profile_type) { // its not an admin
                    if ('teacher' == $request->user()->profile_type) {
                        $models->where('teacher_id', $request->user()->profile_id);
                    }
                    else{
                        $models->whereNotNull('approver_id')->where('is_approved', 1);
                    }
                }
                else { // this is an Admin
                    $models->whereNull('approver_id')->where('is_approved', 0);
                }
            }
            else { // this is an Guest user
                $models->whereNotNull('approver_id')->where('is_approved', 1);
            }
        }
        else{
            $request->merge(['is_approved' => 1]);
        }

        // top courses
        if (isset($request->is_top) && ('' != $request->is_top)) {
            $models->orderBy('students_count', 'DESC');
        }

        // rating
        if (isset($request->rating) && ('' != $request->rating)) {
            $models->where('rating', '>=', $request->rating);
        }

        if (isset($request->title) && ('' != $request->title)) {
            $models->where('title', 'LIKE', "%{$request->title}%");
        }

        if (isset($request->bulk_fetch_course_ids) && ('' != $request->bulk_fetch_course_ids)) {
            // dd($request->bulk_fetch_course_ids);
            $models->whereIn('id', $request->bulk_fetch_course_ids);
        }


        if (isset($request->bulk_ignore_course_ids) && ('' != $request->bulk_ignore_course_ids)) {
            $models->whereNotIn('id', $request->bulk_ignore_course_ids);
        }

        if (isset($request->bulk_category_ids) && ('' != $request->bulk_category_ids)) {
            $models->whereIn('course_category_id', $request->bulk_category_ids);
        }

        // nature
        if (isset($request->nature) && ('' != $request->nature)) {
            $models->where('nature', 'LIKE', "%{$request->nature}%");
        }

        if (isset($request->start_date) && ('' != $request->start_date)) {
            $models->where('start_date', 'LIKE', $request->start_date);
        }

        if (isset($request->end_date) && ('' != $request->end_date)) {
            $models->where('end_date', 'LIKE', $request->end_date);
        }


        // teacher_id
        if(isset($request->teacher_id) && ('' != $request->teacher_id)){
            $models->where('teacher_id', $request->teacher_id);
        }

        // course_category_id
        if(isset($request->course_category_id) && ('' != $request->course_category_id)){
            $models->where('course_category_id', $request->course_category_id);
        }

        // is_course_free
        if(isset($request->is_course_free) && ('' != $request->is_course_free)){
            $models->where('is_course_free', '=', "{$request->is_course_free}");
        }

        // is_handout_free
        if (isset($request->is_handout_free) && ('' != $request->is_handout_free)) {
            $models->where('is_handout_free', '=', "{$request->is_handout_free}");
        }

        // price_usd
        if (isset($request->price_usd) && ('' != $request->price_usd)) {
            $models->where('price_usd', '=', "{$request->price_usd}");
        }

        //discount_usd
        if (isset($request->discount_usd) && ('' != $request->discount_usd)) {
            $models->where('discount_usd', '=', "{$request->discount_usd}");
        }

        //price_pkr
        if (isset($request->price_pkr) && ('' != $request->price_pkr)) {
            $models->where('price_pkr', '=', "{$request->price_pkr}");
        }

        //discount_pkr
        if (isset($request->discount_pkr) && ('' != $request->discount_pkr)) {
            $models->where('discount_pkr', '=', "{$request->discount_pkr}");
        }

        //total_duration
         if (isset($request->total_duration) && ('' != $request->total_duration)) {
            $models->where('total_duration', '=', "{$request->total_duration}");
        }

         //is_approved
         if (isset($request->is_approved) && ('' != $request->is_approved)) {
            $models->where('is_approved', '=', "{$request->is_approved}");
        }

         //students_count
         if (isset($request->students_count) && (null != $request->students_count) && ('' != $request->students_count)) {
            $models->where('students_count', '>=', "{$request->students_count}");
        }

        //paid_students_count
        if (isset($request->paid_students_count) && (null != $request->paid_students_count)  && ('' != $request->paid_students_count)) {
            $models->where('paid_students_count', '>=', "{$request->paid_students_count}");
        }

        //free_students_count
        if (isset($request->free_students_count) && (null != $request->free_students_count)  && ('' != $request->free_students_count)) {
            $models->where('free_students_count', '>=', "{$request->free_students_count}");
        }

        if(isset($request->keywords) && ('' != $request->keywords)){
            $models->where(function($query) use($request){
                return $query->where('title', 'LIKE', "%{$request->keywords}%")
                ->orWhere('nature', 'LIKE', "%{$request->keywords}%");
            });
        }

        $cloned_models = clone $models;
        if(isset($request->offset) && isset($request->limit)){
            $models->offset($request->offset)->limit($request->limit);
        }

        $data['courses'] = $models
        ->with($this->relations)
        ->get();
        $data['total_count'] = $cloned_models->count();
        // dd(\DB::getQueryLog());
        // dd($data);

        return getInternalSuccessResponse($data);
    }

    /**
     * Add|Update Course detail
     *
     * @param Request $request
     * @param Integer $course_id
     * @return void
     */
    public function addUpdateCourseDetail(Request $request, $course_id = null)
    {
        if (null == $course_id) {
            $model = new Course();
            $model->uuid = \Str::uuid();
            $model->created_at = date('Y-m-d H:i:s');
        } else {
            $model = Course::where('id', $course_id)->first();
        }
        $model->updated_at = date('Y-m-d H:i:s');

        $model->teacher_id = $request->teacher_id;
        $model->course_category_id = $request->course_category_id;
        $model->nature = $request->nature; //nature; either is video course or online
        $model->is_course_free = $request->is_course_free;  //is_course_free
        $model->start_date = $request->start_date;  //start_date
        $model->title = $request->title;  //title

        if (isset($request->end_date) && ('' != $request->end_date)) {
            $model->end_date = $request->end_date;  //end_date
        }

        if (isset($request->course_status) && ('' != $request->course_status)) {
            $model->course_status = $request->course_status;  //course_status
        }

        if (isset($request->is_handout_free) && ('' != $request->is_handout_free)) {
            $model->is_handout_free = $request->is_handout_free;  //is_handout_free
        }

        if(isset($request->currency) && ($request->currency == 'usd'))
        {
            if (isset($request->price) && ('' != $request->price)) {
                $model->price_usd = $request->price;  //price
            }
            if (isset($request->discount) && ('' != $request->discount)) {
                $model->discount_usd = $request->discount;  //discount_usd
            }
            $model->price_pkr = $model->discount_pkr = 0;  // update PKR
            $model->price_euro = $model->discount_euro = 0;  // update Euro
            $model->price_aud = $model->discount_aud = 0;  // update AUD
        }

        if(isset($request->currency) && ($request->currency == 'aud'))
        {
            if (isset($request->price) && ('' != $request->price)) {
                $model->price_aud = $request->price;  //price
            }
            if (isset($request->discount) && ('' != $request->discount)) {
                $model->discount_aud = $request->discount;  //discount_usd
            }
            $model->price_pkr = $model->discount_pkr = 0;  // update PKR
            $model->price_usd = $model->discount_usd = 0;  // update USD
            $model->price_euro = $model->discount_euro = 0;  // update Euro
        }

        if(isset($request->currency) && ($request->currency == 'euro'))
        {
            if (isset($request->price) && ('' != $request->price)) {
                $model->price_euro = $request->price;  //price
            }
            if (isset($request->discount) && ('' != $request->discount)) {
                $model->discount_euro = $request->discount;
            }

            $model->price_pkr = $model->discount_pkr = 0;  // update PKR
            $model->price_usd = $model->discount_usd = 0;  // update USD
            $model->price_aud = $model->discount_aud = 0;  // update AUD
        }

        if(isset($request->currency) && ($request->currency == 'pkr'))
        {
            if (isset($request->price) && ('' != $request->price)) {
                $model->price_pkr = $request->price;  //price
            }
            if (isset($request->discount) && ('' != $request->discount)) {
                $model->discount_pkr = $request->discount;  //discount_usd
            }

            $model->price_usd = $model->discount_usd = 0;  // update PKR
            $model->price_euro = $model->discount_euro = 0;  // update Euro
            $model->price_aud = $model->discount_aud = 0;  // update AUD
        }

        // if (isset($request->price_usd) && ('' != $request->price_usd)) {
        //     $model->price_usd = $request->price_usd;  //price_usd
        // }
        // if (isset($request->discount_usd) && ('' != $request->discount_usd)) {
        //     $model->discount_usd = $request->discount_usd;  //discount_usd
        // }
        // if (isset($request->price_pkr) && ('' != $request->price_pkr)) {
        //     $model->price_pkr = $request->price_pkr;  //price_pkr
        // }
        // if (isset($request->discount_pkr) && ('' != $request->discount_pkr)) {
        //     $model->discount_pkr = $request->discount_pkr;  //discount_pkr
        // }

        if (isset($request->total_duration) && ('' != $request->total_duration)) {
            $model->total_duration = $request->total_duration;  //total_duration
        }

        if (isset($request->is_approved) && ('' != $request->is_approved)) {
            $model->is_approved = $request->is_approved;  //is_approved
        }
        else{
            $model->is_approved = (int)false;
        }

        if (isset($request->approver_id) && ('' != $request->approver_id)) {
            $model->approver_id = $request->approver_id;  //is_approved
        } else {
            $model->approver_id = null;
        }

        if(isset($request->description) && ('' != $request->description)){
            $model->description = $request->description;    // description
        }

         // course_image
         if(isset($request->course_image) && ('' != $request->course_image)){
            $model->course_image = $request->course_image;
         }

        try {
            $model->save();
            if (null == $course_id) {
                $this->updateStats($request->nature, $request->is_course_free);
            }
            $model = Course::where('id', $model->id)->with($this->relations)->first();
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }

    /**
     *  Update Stats Course
     * @param Request $request
     *
     * @return void
     */
    function updateStats($nature, $is_course_free, $mode="add")
    {
        $statsObj = new StatsService();
        $result = $statsObj->addCourseStats($nature, $is_course_free, $mode);
        if(!$result['status']){
            return $result;
        }
        $stats = $result['data'];
        return getInternalSuccessResponse($stats);

    }

    /**
     *  Update Course Stats
     * @param Request $request
     *
     * @return void
     */
    public function updateCourseStats($course_id, $is_free, $mode = "add")
    {
        $model = Course::where('id', $course_id)->first();

        // $model->students_count += 1;
        $model->students_count = ($mode == 'add')? + $model->students_count + 1 : $model->students_count -1;
        if($is_free){
            $model->free_students_count += 1;
        }
        else{
            // $model->paid_students_count += 1;
            $model->paid_students_count = ($mode == 'add')? + $model->paid_students_count + 1 : $model->paid_students_count -1;
        }

        // save stats
        try {
            $model->save();
            // dd($model->getAttributes());
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            // dd($ex);
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }


    /**
     * Update Course Reviews Stats against given course ID
     *
     * @param Integer $course_id
     * @param string OPTIONAL $mode [default - add]
     *
     * @return void
     */
    public function updateCourseReviewStats($course_id,  $mode = 'add')
    {
        $model = Course::where('id', $course_id)->first();
        if($mode == 'add') {
            $model->total_rater_count += 1;
            $model->total_rating_count += 1;
        }
        else if($mode == 'delete') {
            $model->total_rater_count -= 1;
            $model->total_rating_count -= 1;
        }

        try {
            $model->save();
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }

    /**
     * Update Course Outline Stats against given course ID
     *
     * @param Integer $course_id
     * @param string OPTIONAL $mode [default - add]
     *
     * @return void
     */
    public function updateCourseOutlineStats($course_id,  $mode = 'add')
    {
        $model = Course::where('id', $course_id)->first();
        if($mode == 'add') {
            $model->total_outlines_count += 1;
        }
        else {
            $model->total_outlines_count -= 1;
        }

        try {
            $model->save();
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }

    /**
     * Update Course Content Stats against given course ID
     *
     * @param Integer $course_id
     * @param string OPTIONAL $mode [default - add]
     *
     * @return void
     */
    public function updateCourseContentStats($course_id,  $mode = 'add')
    {
        $model = Course::where('id', $course_id)->first();
        if ($mode == 'add') {
            $model->total_videos_count += 1;
        } else {
            $model->total_videos_count -= 1;
        }

        try {
            $model->save();
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }

    /**
     * Update Course Content Outline against given course ID
     *
     * @param Integer $course_id
     * @param string OPTIONAL $mode [default - add]
     *
     * @return void
     */
    public function updateCourseSlotsStats($course_id,  $mode = 'add')
    {
        $model = Course::where('id', $course_id)->first();
        if($mode == 'add') {
            $model->total_slots_count += 1;
        }
        else {
            $model->total_slots_count -= 1;
        }

        try {
            $model->save();
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }

       /**
     * Update Course Handout Content against given course ID
     *
     * @param Integer $course_id
     * @param string OPTIONAL $mode [default - add]
     *
     * @return void
     */
    public function updateCourseHandoutStats($course_id,  $mode = 'add')
    {
        $model = Course::where('id', $course_id)->first();
        if($mode == 'add') {
            $model->total_handouts_count += 1;
        }
        else {
            $model->total_handouts_count -= 1;
        }

        try {
            $model->save();
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }

}
