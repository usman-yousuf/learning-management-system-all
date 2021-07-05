<?php

namespace Modules\Student\Services;

use Illuminate\Http\Request;
use Modules\Student\Entities\StudentAssignment;

class StudentAssignmentService
{


     /**
     * Check if Student Student Assignment Exists given ID
     *
     * @param Integer $id
     * @return void
     */
    public function getStudentAssignmentById($id)
    {
        $model =  StudentAssignment::where('id', $id)->first();
        if(null == $model){
            return \getInternalErrorResponse('No student assignment Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check and fetch and Student Student Assignment against given ID
     *
     * @param Integer $id
     * @return void
     */
    public function checkStudentAssignmentById($id)
    {
        $model =  StudentAssignment::where('id', $id)->first();
        if(null == $model){
            return getInternalErrorResponse('No student assignment Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check if an Student Student Assignment Exists against given $request->student_assignment_uuid
     *
     * @param Request $request
     * @return void
     */
    public function checkStudentAssignment(Request $request)
    {
        $model = StudentAssignment::where('uuid', $request->student_assignment_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No student assignment Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Get Student Student Assignment against given UUID
     *
     * @param Request $request
     * @return void
     */
    public function getStudentAssignment(Request $request)
    {
        $model = StudentAssignment::where('uuid', $request->student_assignment_uuid)->first();
        return getInternalSuccessResponse($model);
    }

    /**
     * Delete Student Student Assignment by given UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteStudentAssignment(Request $request)
    {
        $model = StudentAssignment::where('uuid', $request->review_uuid)->first();

        if (null == $model) {
            return getInternalErrorResponse('No student assignment Found', [], 404, 404);
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
     * Add|Update Student Assignment
     *
     * @param Request $request
     * @param Integer $student_assignment_id
     * @return void
     */
    public function addUpdateStudentAssignment(Request $request, $student_assignment_id = null)
    {
        if (null == $student_assignment_id) {
            $model = new StudentAssignment();
            $model->uuid = \Str::uuid();
            $model->created_at = date('Y-m-d H:i:s');
        } else {
            $model = StudentAssignment::where('uuid', $student_assignment_id)->first();
            // $model_stats = Stats::orderBy('DESC');

        }
        $model->updated_at = date('Y-m-d H:i:s');
        $model->course_id = $request->course_id;
        $model->student_id = $request->profile_id;
        $model->assignment_id = $request->assignment_id;
        $model->media = $request->media;
        if(isset($request->status) && ('' != $request->status))
        {
            $model->status = $request->status;
        }

        try {
            $model->save();
            // update course stats
            // $model = Review::where('id', $model->id)->with(['student', 'course'])->first();
            // $courseDetailService = new CourseDetailService();
            // $result = $courseDetailService->updateCourseReviewStats($model->course_id, 'add');
            // if(!$result['status']){
            //     return $result;
            // }
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }
}
