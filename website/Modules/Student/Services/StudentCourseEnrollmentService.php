<?php

namespace Modules\Student\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Student\Entities\Review;
use Modules\User\Entities\StudentCourse;

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
        $model = StudentCourse::
                where('student_id', $request->student_id)
                ->where('course_id', $request->course_id)
                ->first();
            dd($model);
        if (null == $model) {
            return getInternalErrorResponse('No Student Course Found', [], 404, 404);
        }

        try{
            $model->delete();
        }
        catch(\Exception $ex)
        {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode(), 500);
        }
        return getInternalSuccessResponse($model);
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

        $data['enrollment'] = $models->with('student', 'course')->get();
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

        try {
            $model->save();
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }
}
