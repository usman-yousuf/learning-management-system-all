<?php

namespace Modules\Common\Services;

use Modules\Common\Entities\Stats;
use Illuminate\Http\Request;

class StatsService
{

    /**
     * Update New User Stats (Signup)
     *
     * @param Request $request
     * @return void
     */
    public function updateNewUserStats(Request $request)
    {
        // get|set model
        $model = Stats::orderBy('created_at', 'desc')->first();
        if(null == $model){
            $model = new Stats();
            $model->created_at = date('Y-m-d H:i:s');
        }
        $model->updated_at = date('Y-m-d H:i:s');

        // social based users count
        if($request->is_social){
            if($request->social_type == 'google'){
                $model->google_users_count += 1;
            }
            else if ($request->social_type == 'apple') {
                $model->apple_users_count += 1;
            }
            else if ($request->social_type == 'facebook') {
                $model->facebook_users_count += 1;
            }
            else if ($request->social_type == 'twitter') {
                $model->twitter_users_count += 1;
            }
        }

        // profile type based users count
        if (isset($request->profile_type)){
            if('teacher' == $request->profile_type) {
                $model->total_teachers_count += 1;
            }
            else if ('parent' == $request->profile_type) {
                $model->total_parents_count += 1;
            }
            else if ('student' == $request->profile_type) {
                $model->total_students_count += 1;
            }
        }

        // device types based stats
        if($request->device_type == 'web')
        {
            $model->web_users_count += 1;
        }
        else{
            if($request->device_type == 'andriod')
            {
                $model->andriod_users_count += 1;
            }
            else{
                $model->ios_users_count += 1;
            }
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

    public function getTecherSpecificStats(Request $request)
    {
        $teacher_id = $request->user()->profile_id;
        $temp = \DB::select(
            "
            SELECT
                (SELECT COUNT(*) FROM courses WHERE courses.teacher_id=?) AS total_courses

                , (SELECT COUNT(*) FROM courses WHERE courses.nature='video' AND courses.teacher_id=?) AS video_courses
                , (SELECT COUNT(*) FROM courses WHERE courses.nature='video' AND courses.is_course_free = 1 AND courses.teacher_id=?) AS free_video_courses
                , (SELECT COUNT(*) FROM courses WHERE courses.nature='video' AND courses.is_course_free = 0 AND courses.teacher_id=?) AS paid_video_courses

                , (SELECT COUNT(*) FROM courses WHERE courses.nature='online' AND courses.teacher_id=?) AS online_courses
                , (SELECT COUNT(*) FROM courses WHERE courses.nature='online' AND courses.is_course_free = 1 AND courses.teacher_id=?) AS free_online_courses
                , (SELECT COUNT(*) FROM courses WHERE courses.nature='online' AND courses.is_course_free = 0 AND courses.teacher_id=?) AS paid_online_courses

                , (SELECT COUNT(*) FROM `student_courses` enrolment INNER JOIN courses c ON enrolment.course_id = c.id WHERE c.teacher_id=?) AS total_enrollments
                , (SELECT COUNT(*) FROM `student_courses` enrolment INNER JOIN courses c ON enrolment.course_id = c.id WHERE c.is_course_free = 1 AND c.teacher_id=?) AS free_enrollments_count
                , (SELECT COUNT(*) FROM `student_courses` enrolment INNER JOIN courses c ON enrolment.course_id = c.id WHERE c.is_course_free = 0 AND c.teacher_id=?) AS paid_enrollments_count
        "
        , [
                $teacher_id,
                $teacher_id,
                $teacher_id,
                $teacher_id,
                $teacher_id,
                $teacher_id,
                $teacher_id,
                $teacher_id,
                $teacher_id,
                $teacher_id,
        ]);
        $temp = $temp[0];
        $data = [
            'total_courses_count' => $temp->total_courses
            , 'total_completed_courses_count' => 0
            , 'total_online_courses_count' => $temp->online_courses
            , 'total_online_paid_courses_count' => $temp->paid_online_courses
            , 'total_online_free_courses_count' => $temp->free_online_courses
            , 'total_video_courses_count' => $temp->video_courses
            , 'total_video_paid_courses_count' => $temp->paid_video_courses
            , 'total_video_free_courses_count' => $temp->free_video_courses

            , 'total_students_count' => $temp->total_enrollments
            , 'total_paid_students_count' => $temp->paid_enrollments_count
            , 'total_free_students_count' => $temp->free_enrollments_count
        ];
        $data = (object)$data;
        return getInternalSuccessResponse($data);

        //   +"total_courses": 2
        // +"video_courses": 1
        // +"free_video_courses": 0
        // +"paid_video_courses": 1
        // +"online_courses": 1
        // +"free_online_courses": 1
        // +"paid_online_courses": 0
        // +"total_enrollments": 2
        // +"free_enrollments_count": 1
        // +"paid_enrollments_count": 1
        dd($temp);

        // $data = [
        //         'total_courses_count' => $model->total_courses_count
        //         , 'total_completed_courses_count' => $model->total_completed_courses_count
        //         , 'total_online_courses_count' => $model->total_online_courses_count
        //         , 'total_online_paid_courses_count' => $model->total_online_paid_courses_count
        //         , 'total_online_free_courses_count' => $model->total_online_free_courses_count
        //         , 'total_video_courses_count' => $model->total_video_courses_count
        //         , 'total_video_paid_courses_count' => $model->total_video_paid_courses_count
        //         , 'total_video_free_courses_count' => $model->total_video_free_courses_count

        //         , 'total_students_count' => $model->total_students_count
        //         , 'total_paid_students_count' => $model->total_paid_students_count
        //         , 'total_free_students_count' => $model->total_free_students_count
        //     ];
        //     $data = (object)$data;
        //     return getInternalSuccessResponse($data);
    }

    /**
     * Get Stats for All Courses
     *
     * @param Request $request
     * @return void
     */
    public function getAllCoursesStats(Request $request)
    {
        $model = Stats::orderBy('created_at', 'desc')->first();
        if (null == $model) {
            $model = new Stats();
            $model->created_at = date('Y-m-d H:i:s');
        }
        $model->updated_at = date('Y-m-d H:i:s');

        // save stats
        try {
            $model->save();
            $data = [
                'total_courses_count' => $model->total_courses_count
                , 'total_completed_courses_count' => $model->total_completed_courses_count
                , 'total_online_courses_count' => $model->total_online_courses_count
                , 'total_online_paid_courses_count' => $model->total_online_paid_courses_count
                , 'total_online_free_courses_count' => $model->total_online_free_courses_count
                , 'total_video_courses_count' => $model->total_video_courses_count
                , 'total_video_paid_courses_count' => $model->total_video_paid_courses_count
                , 'total_video_free_courses_count' => $model->total_video_free_courses_count

                , 'total_students_count' => $model->total_students_count
                , 'total_paid_students_count' => $model->total_paid_students_count
                , 'total_free_students_count' => $model->total_free_students_count
            ];
            $data = (object)$data;
            return getInternalSuccessResponse($data);
        } catch (\Exception $ex) {
            // dd($ex);
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }

    // /**
    //  * Add Appointment Stats
    //  *
    //  * @param Request $request
    //  * @return void
    //  */
    // public function addAppointmentStats(Request $request)
    // {
    //     // get|set model
    //     $model = Stats::whereNull('deleted_at')->orderBy('created_at', 'desc')->first();
    //     if (null == $model) {
    //         $model = new Stats();
    //         $model->created_at = date('Y-m-d H:i:s');
    //     }
    //     $model->updated_at = date('Y-m-d H:i:s');

    //     $model->total_appointments_count += 1;

    //     if (isset($request->consultancy_type) && ('video' == $request->consultancy_type)) {
    //         $model->total_call_appointments_count += 1;
    //     } else {
    //         $model->total_chat_appointments_count += 1;
    //     }
    //     $model->total_pending_appointments_count += 1;

    //     // save stats
    //     try {
    //         $model->save();
    //         // dd($model->getAttributes());
    //         return getInternalSuccessResponse($model);
    //     } catch (\Exception $ex) {
    //         // dd($ex);
    //         return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
    //     }
    // }

    // /**
    //  * Update Appointment Stats
    //  *
    //  * @param Request $request
    //  *
    //  * @return void
    //  */
    // public function updateAppointmentStats(Request $request)
    // {
    //     $model = Stats::whereNull('deleted_at')->orderBy('created_at', 'desc')->first();
    //     if (null == $model) {
    //         $model = new Stats();
    //         $model->created_at = date('Y-m-d H:i:s');
    //     }
    //     $model->updated_at = date('Y-m-d H:i:s');

    //     if(isset($request->status)){
    //         if ('completed' == $request->status) {
    //             $model->total_completed_appointments_count += 1;
    //         } else if ('cancelled' == $request->status) {
    //             $model->total_cancelled_appointments_count += 1;
    //         }
    //     }

    //     // save stats
    //     try {
    //         $model->save();
    //         // dd($model->getAttributes());
    //         return getInternalSuccessResponse($model);
    //     } catch (\Exception $ex) {
    //         // dd($ex);
    //         return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
    //     }
    // }

    /**
     *  Add Course Stats
     * @param Request $request
     *
     * @return void
     */
    public function addCourseStats($nature, $is_paid, $mode = "add")
    {
        $model = Stats::orderBy('created_at', 'DESC')->first();
        if (null == $model) { // create a new record
            $model = new Stats();
            $model->created_at = date('Y-m-d H:i:s');
            $model->total_courses_count = 1;
            if($nature = 'online'){ // online course
                if($is_paid){
                    $model->total_online_courses_count = 1;
                    $model->total_online_paid_courses_count = 1;
                }
                else{
                    $model->total_online_free_courses_count = 1;
                }
            }
            else{ // video course
                $model->total_video_courses_count = 1;
                if($is_paid){
                    $model->total_video_paid_courses_count = 1;
                }
                else{
                    $model->total_video_free_courses_count = 1;
                }
            }
        }
        else{ // update a record
            $model->total_courses_count  = ($mode == 'add')? + $model->total_courses_count + 1 : $model->total_courses_count -1;
            if($nature == 'online'){ // online course
                if($is_paid){
                    // $model->total_online_courses_count += 1;
                    // $model->total_online_paid_courses_count += 1;
                    $model->total_online_courses_count  = ($mode == 'add')? + $model->total_online_courses_count + 1 : $model->total_online_courses_count -1;
                    $model->total_online_paid_courses_count  = ($mode == 'add')? + $model->total_online_paid_courses_count + 1 : $model->total_online_paid_courses_count -1;
                }
                else{
                    $model->total_online_free_courses_count  = ($mode == 'add')? + $model->total_online_free_courses_count + 1 : $model->total_online_free_courses_count -1;
                    // $model->total_online_free_courses_count += 1;
                }
            }
            else{ // video course
                // $model->total_video_courses_count += 1;
                $model->total_video_courses_count  = ($mode == 'add')? + $model->total_video_courses_count + 1 : $model->total_video_courses_count -1;

                if($is_paid){
                    $model->total_video_paid_courses_count  = ($mode == 'add')? + $model->total_video_paid_courses_count + 1 : $model->total_video_paid_courses_count -1;
                    // $model->total_video_paid_courses_count += 1;
                }
                else{
                    $model->total_video_free_courses_count  = ($mode == 'add')? + $model->total_video_free_courses_count + 1 : $model->total_video_free_courses_count -1;
                    // $model->total_video_free_courses_count += 1;
                }
            }
        }
        $model->updated_at = date('Y-m-d H:i:s');

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
     *  Update Enrolled Student Stats
     * @param Request $request
     *
     * @return void
     */
    public function updateEnrolledStudentStats($is_free, $mode= "add")
    {
        $model = Stats::orderBy('created_at', 'DESC')->first();
        if (null == $model) { // create a new record
            $model = new Stats();
            $model->created_at = date('Y-m-d H:i:s');

            if($is_free) // check if course is free
            {
                $model->total_free_students_count = 1;
            }
            else {
                $model->total_paid_students_count = 1;
            }
        }
        else{
            if($is_free) // In Update check if course is free
            {
                $model->total_free_students_count  = ($mode == 'add')? + $model->total_free_students_count + 1 : $model->total_free_students_count -1;
            }
            else {
                $model->total_paid_students_count  = ($mode == 'add')? + $model->total_paid_students_count + 1 : $model->total_paid_students_count -1;

            }
        }
        $model->updated_at = date('Y-m-d H:i:s');

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
     *  Add User Stats
     * @param Request $request
     *
     * @return void
     */

    public function addUserStats($social_id, $social_type, $profile_type, $device_type, $mode = 'add')
    {
        $model = Stats::orderBy('created_at', 'DESC')->first();
        if (null == $model) { // create a new record
            $model = new Stats();
            $model->created_at = date('Y-m-d H:i:s');

            // case: user regiereted using social media
            if($social_id)
            {
                if($social_type == 'google')
                {
                    $model->google_users_count = 1;
                }
                else if($social_type == 'apple')
                {
                    $model->apple_users_count = 1;
                }
                else if($social_type == 'facebook')
                {
                    $model->facebook_users_count = 1;
                }
                else if($social_type == 'twitter'){
                    $model->twitter_users_count = 1;
                }
            }

            // determine if user is teacher, parent, student or admin
            if($profile_type == 'teacher')
            {
                $model->total_teachers_count = 1;
            }
            else if($profile_type == 'parent')
            {
                $model->total_parents_count = 1;
            }
            else if($profile_type == 'student')
            {
                $model->total_students_count = 1;
            }

            // determine user device type
            if($device_type == "android")
            {
                $model->andriod_users_count = 1;
            }
            else if($device_type == "ios"){
                $model->ios_users_count = 1;
            }
            else if($device_type == "web"){
                $model->web_users_count = 1;
            }

        }
        else{ // update case: user regiereted using social media
            if($social_id)
            {
                if($social_type == 'google')
                {
                    $model->google_users_count = ($mode == 'add')? + $model->google_users_count + 1 : $model->google_users_count -1;
                }
                else if($social_type == 'apple')
                {
                    $model->apple_users_count = ($mode == 'add')? + $model->apple_users_count + 1 : $model->apple_users_count -1;
                }
                else if($social_type == 'facebook')
                {
                    $model->facebook_users_count = ($mode == 'add')? + $model->facebook_users_count + 1 : $model->facebook_users_count -1;
                }
                else if($social_type == 'twitter'){
                    $model->twitter_users_count = ($mode == 'add')? + $model->twitter_users_count + 1 : $model->twitter_users_count -1;
                }
            }

            // determine if user is teacher, parent, student or admin
            if($profile_type == 'teacher')
            {
                $model->total_teachers_count = ($mode == 'add')? + $model->total_teachers_count + 1 : $model->total_teachers_count -1;

            }
            else if($profile_type == 'parent')
            {
                $model->total_parents_count = ($mode == 'add')? + $model->total_parents_count + 1 : $model->total_parents_count -1;

            }
            else if($profile_type == 'student')
            {
                $model->total_students_count = ($mode == 'add')? + $model->total_students_count + 1 : $model->total_students_count -1;
            }

            // determine user device type
            if($device_type == "android")
            {
                $model->andriod_users_count = ($mode == 'add')? + $model->andriod_users_count + 1 : $model->andriod_users_count -1;

            }
            else if($device_type == "ios"){
                $model->ios_users_count = ($mode == 'add')? + $model->ios_users_count + 1 : $model->ios_users_count -1;
            }
            else if($device_type == "web"){
                $model->web_users_count = ($mode == 'add')? + $model->web_users_count + 1 : $model->web_users_count -1;
            }

        }
        $model->updated_at = date('Y-m-d H:i:s');
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
}
