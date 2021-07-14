<?php

namespace Modules\Student\Services;

use Illuminate\Http\Request;
use Modules\Common\Services\NotificationService;
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

             
            if(null == $student_assignment_id){
                //send notification
                $notiService = new NotificationService();
                $teacher_id  = $model->course->teacher_id;
                $receiverIds[] = $teacher_id;

                // dd($receiverIds);
                $request->merge([
                    'notification_type' => listNotficationTypes()['upload_assignment']
                    , 'notification_text' => getNotificationText($request->user()->profile->first_name, 'upload_assignment')
                    , 'notification_model_id' => $model->id
                    , 'notification_model_uuid' => $model->uuid
                    , 'notification_model' => 'student_assignments'

                    , 'additional_ref_id' => $model->course->id
                    , 'additional_ref_uuid' => $model->course->uuid
                    , 'additional_ref_model_name' => 'courses'

                    , 'is_activity' => true
                    // , 'start_date' => $model->start_date
                    // , 'end_date' => (null != $model->extended_date)? $model->extended_date : $model->due_date
                ]);
                $result =  $notiService->sendNotifications($receiverIds, $request, true);
                // dd($result['status']);
                if(!$result['status'])
                {
                    return $result;
                }
            }
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
