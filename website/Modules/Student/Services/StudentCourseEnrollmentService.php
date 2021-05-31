<?php

namespace Modules\Student\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Common\Entities\Stats;
use Modules\Common\Services\StatsService;
use Modules\Course\Services\CourseDetailService;
use Modules\Student\Entities\Review;
use Modules\User\Entities\StudentCourse;
use Modules\User\Services\ProfileService;

class StudentCourseEnrollmentService
{

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
            return \getInternalErrorResponse('No Student Course Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

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

        //student_id
        if(isset($request->student_id) && ('' != $request->student_id)){
            $models->where('student_id', $request->student_id);
        }

       // status
       if (isset($request->status) && ('' != $request->status)) {
        $models->where('status', $request->status);
        }
        // joining_date
        if(isset($request->joining_date) && ('' != $request->joining_date)){
            $models->where('joining_date', '=', "{$request->joining_date}");
        }

        $cloned_models = clone $models;
        if(isset($request->offset) && isset($request->limit)){
            $models->offset($request->offset)->limit($request->limit);
        }

        $data['enrollment'] = $models->with('student', 'course', 'slot')->get();
        $data['total_count'] = $cloned_models->count();

        return getInternalSuccessResponse($data);
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

        try {
            $model->save();
            $model = StudentCourse::where('id',$model->id)->with(['student', 'course', 'slot'])->first();
            if($student_course_id ==  null)
            {
                //update Stats
               $result =  $this->updateEnrollmentStats($request->course_id, $request->student_id, $model->course->is_course_free, $model->course->nature);
               if(!$result['status'])
               {
                   return $result;
               }
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
    public function updateEnrollmentStats($course_id, $student_id, $is_free, $nature, $mode="free")
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
}
