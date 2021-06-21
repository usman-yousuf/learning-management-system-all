<?php

namespace Modules\Course\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Course\Entities\StudentQuery;

class StudentQueryService
{

    /**
     * Check if an Student Query Exists given ID
     *
     * @param Integer $id
     * @return void
     */
    public function getStudentQueryById($id)
    {
        $model =  StudentQuery::where('id', $id)->first();
        if(null == $model){
            return getInternalErrorResponse('No Student Query Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check and fetch and Student Query against given ID
     *
     * @param Integer $id
     * @return void
     */
    public function checkStudentQueryById($id)
    {
        $model =  StudentQuery::where('id', $id)->first();
        if(null == $model){
            return getInternalErrorResponse('No Student Query Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check if an Student Query Exists against given $request->course_handout_uuid
     *
     * @param Request $request
     * @return void
     */
    public function checkStudentQuery(Request $request)
    {
        $model = StudentQuery::where('uuid', $request->student_query_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No Student Query Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Get an Student Query against given UUID
     *
     * @param Request $request
     * @return void
     */
    public function getStudentQuery(Request $request)
    {
        $model = StudentQuery::where('uuid', $request->student_query_uuid)->with(['course','student'])->first();
        return getInternalSuccessResponse($model);
    }

    /**
     * Delete an Student Query by given UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteStudentQuery(Request $request)
    {
        $model = StudentQuery::where('uuid', $request->student_query_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No Student Query Found', [], 404, 404);
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
     * Get Student Query based on filters
     *
     * @param Request $request
     * @return void
     */
    public function getStudentQuerys(Request $request)
    {
        $models = StudentQuery::orderBy('created_at');

        //course_id
        if(isset($request->course_id) && ('' != $request->course_id)){
            $models->where('course_id', $request->course_id);
        }

        // student_query_uuid
        if(isset($request->student_query_uuid) && ('' != $request->student_query_uuid)){
            $models->where('uuid', $request->student_query_uuid);
        }

        // body
        if (isset($request->body) && ('' != $request->body)) {
            $models->where('body', 'LIKE', "%{$request->body}%");
        }

        $cloned_models = clone $models;
        if(isset($request->offset) && isset($request->limit)){
            $models->offset($request->offset)->limit($request->limit);
        }

        $data['queries'] = $models->with(['course','student'])->get();
        $data['total_count'] = $cloned_models->count();

        return getInternalSuccessResponse($data);
    }

    /**
     * Add|Update Student Querys
     *
     * @param Request $request
     * @param Integer $course_handout_uuid
     * @return void
     */
    public function addUpdateStudentQuery(Request $request, $student_query_id = null)
    {
        if (null == $student_query_id) {
            $model = new StudentQuery();
            $model->uuid = \Str::uuid();
            $model->created_at = date('Y-m-d H:i:s');
        } else {
            $model = StudentQuery::where('id', $student_query_id)->first();
        }
        $model->updated_at = date('Y-m-d H:i:s');
        $model->course_id = $request->course_id;
        $model->student_id = $request->student_id;
        $model->body = $request->body;

        try {
            $model->save();
            $model = StudentQuery::where('id', $model->id)->with(['course','student'])->first();
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }
}
