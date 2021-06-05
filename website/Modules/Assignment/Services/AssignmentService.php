<?php

namespace Modules\Assignment\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Assignment\Entities\Assignment;
use Modules\Common\Services\NotificationService;

class AssignmentService
{

    private $relation = [
        'course',
        'assignee'
    ];
    /**
     * Check if an Assignment Exists given ID
     *
     * @param Integer $id
     * @return void
     */
    public function getAssignmentById($id)
    {
        $model =  Assignment::where('id', $id)->first();
        if(null == $model){
            return getInternalErrorResponse('No Assignment Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check and fetch and Assignment against given ID
     *
     * @param Integer $id
     * @return void
     */
    public function checkAssignmentById($id)
    {
        $model =  Assignment::where('id', $id)->first();
        if(null == $model){
            return getInternalErrorResponse('No Assignment Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check if an Assignment Exists against given $request->course_handout_uuid
     *
     * @param Request $request
     * @return void
     */
    public function checkAssignment(Request $request)
    {
        $model = Assignment::where('uuid', $request->assignment_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No Assignment Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Get an Assignment against given UUID
     *
     * @param Request $request
     * @return void
     */
    public function getAssignment(Request $request)
    {

        $model = Assignment::where('uuid', $request->assignment_uuid)->with($this->relation)->first();
        return getInternalSuccessResponse($model);
    }

    /**
     * Delete an Assignment by given UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteAssignment(Request $request)
    {
        $model = Assignment::where('uuid', $request->assignment_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No Assignment Found', [], 404, 404);
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
     * Get Assignment based on filters
     *
     * @param Request $request
     * @return void
     */
    public function getAssignments(Request $request)
    {
        $models = Assignment::orderBy('created_at', 'desc');

        //course_id
        if(isset($request->course_id) && ('' != $request->course_id)){
            $models->where('course_id', $request->course_id);
        }

        //assignee_id
        if(isset($request->assignee_id) && ('' != $request->assignee_id)){
        $models->where('assignee_id', $request->assignee_id);
        }

        // assignment_uuid
        if(isset($request->assignment_uuid) && ('' != $request->assignment_uuid)){
            $models->where('uuid', $request->assignment_uuid);
        }

        // title
        if (isset($request->title) && ('' != $request->title)) {
              $models->where('title', 'LIKE', "%{$request->title}%");
        }

        // description
        if (isset($request->description) && ('' != $request->description)) {
             $models->where('description', 'LIKE', "%{$request->description}%");
        }

        // total_marks
        if (isset($request->total_marks) && ('' != $request->total_marks)) {
            $models->where('total_marks', $request->total_marks);
        }

        // due_date
        if (isset($request->due_date) && ('' != $request->due_date)) {
            $models->where('due_date', $request->due_date);
        }

        // extended_date
        if (isset($request->extended_date) && ('' != $request->extended_date)) {
            $models->where('extended_date', $request->extended_date);
        }

        $cloned_models = clone $models;
        if(isset($request->offset) && isset($request->limit)){
            $models->offset($request->offset)->limit($request->limit);
        }

        $data['assignments'] = $models->with($this->relation)->get();
        $data['total_count'] = $cloned_models->count();

        return getInternalSuccessResponse($data);
    }

    /**
     * Add|Update Assignments
     *
     * @param Request $request
     * @param Integer $query_response_id
     * @return void
     */
    public function addUpdateAssignment(Request $request, $Assignment_id = null)
    {
        if (null == $Assignment_id) {
            $model = new Assignment();
            $model->uuid = \Str::uuid();
            $model->created_at = date('Y-m-d H:i:s');
        } else {
            $model = Assignment::where('id', $Assignment_id)->first();
        }
        $model->updated_at = date('Y-m-d H:i:s');
        $model->course_id = $request->course_id;
        $model->slot_id = $request->slot_id;
        $model->assignee_id = $request->assignee_id;
        $model->title = $request->title;
        $model->description = $request->description;
        $model->total_marks = $request->total_marks;
        $model->start_date = $request->start_date;
        $model->due_date = $request->due_date;

        $model->media_1 = $request->media_1;

        if (isset($request->media_2) && ('' != $request->media_2)) {
            $model->media_2 = $request->media_2;
        }
        if (isset($request->media_3) && ('' != $request->media_3)) {
            $model->media3 = $request->media_3;
        }
        // extended_date
        if (isset($request->extended_date) && ('' != $request->extended_date)) {
            $model->extended_date = $request->extended_date;
        }

        try {
            $model->save();
            $model = Assignment::where('id', $model->id)->with($this->relation)->first();

            //send notification
            $notiService = new NotificationService();
            $receiverIds = getCourseEnrolledStudentsIds($model->course);
            $request->merge([
                'notification_type' => listNotficationTypes()['create_assignment']
                , 'notification_text' => getNotificationText($request->user()->profile->first_name, 'create_assignment')
                , 'notification_model_id' => $model->id
                , 'notification_model_uuid' => $model->uuid
                , 'notification_model' => 'assignments'

                , 'additional_ref_id' => $model->course->id
                , 'additional_ref_uuid' => $model->course->uuid
                , 'additional_ref_model_name' => 'courses'

                , 'is_activity' => true
                , 'start_date' => $model->start_date
                , 'end_date' => (null != $model->extended_date)? $model->extended_date : $model->due_date
            ]);
            $result =  $notiService->sendNotifications($receiverIds, $request, true);
            if(!$result['status'])
            {
                return $result;
            }
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }
}
