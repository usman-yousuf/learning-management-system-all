<?php

namespace Modules\Assignment\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Assignment\Entities\Assignment;

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
        $model->assignee_id = $request->assignee_id;
        $model->title = $request->title;
        $model->description = $request->description;
        $model->total_marks = $request->total_marks;
        $model->due_date = $request->due_date;
        $model->extended_date = $request->extended_date;
        $model->media_1 = $request->media_1;

        if (isset($request->media_2) && ('' != $request->media_2)) {
            $model->media_2 = $request->media_2;
        }
        if (isset($request->media_3) && ('' != $request->media_3)) {
            $model->media3 = $request->media_3;
        }

        try {
            $model->save();
            $model = Assignment::where('id', $model->id)->with($this->relation)->first();
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }
}
