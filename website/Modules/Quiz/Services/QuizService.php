<?php

namespace Modules\Quiz\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Quiz\Entities\Quiz;

class QuizService
{

    /**
     * Check if an Quiz Exists given ID
     *
     * @param Integer $id
     * @return void
     */
    public function getQuizById($id)
    {
        $model =  Quiz::where('id', $id)->first();
        if(null == $model){
            return getInternalErrorResponse('No Quiz Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check and fetch and Quiz against given ID
     *
     * @param Integer $id
     * @return void
     */
    public function checkQuizById($id)
    {
        $model =  Quiz::where('id', $id)->first();
        if(null == $model){
            return getInternalErrorResponse('No Quiz Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check if an Quiz Exists against given $request->course_handout_uuid
     *
     * @param Request $request
     * @return void
     */
    public function checkQuiz(Request $request)
    {
        $model = Quiz::where('uuid', $request->quiz_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No Quiz Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Get an Quiz against given UUID
     *
     * @param Request $request
     * @return void
     */
    public function getQuiz(Request $request)
    {

        $model = Quiz::where('uuid', $request->quiz_uuid)->with(['course', 'assignee', 'questions'])->first();
        return getInternalSuccessResponse($model);
    }

    /**
     * Delete an Quiz by given UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteQuiz(Request $request)
    {
        $model = Quiz::where('uuid', $request->quiz_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No Quiz Found', [], 404, 404);
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
     * Get Quiz based on filters
     *
     * @param Request $request
     * @return void
     */
    public function getQuizzes(Request $request)
    {
        $models = Quiz::orderBy('created_at', 'DESC');

        //course_id
        if(isset($request->course_id) && ('' != $request->course_id)){
            $models->where('course_id', $request->course_id);
        }

        //assignee_id
        if(isset($request->assignee_id) && ('' != $request->assignee_id)){
        $models->where('assignee_id', $request->assignee_id);
        }

        // quiz_uuid
        if(isset($request->quiz_uuid) && ('' != $request->quiz_uuid)){
            $models->where('uuid', $request->quiz_uuid);
        }

        // title
        if (isset($request->title) && ('' != $request->title)) {
              $models->where('title', 'LIKE', "%{$request->title}%");
        }

        // description
        if (isset($request->description) && ('' != $request->description)) {
             $models->where('description', 'LIKE', "%{$request->description}%");
        }

        // type
        if (isset($request->type) && ('' != $request->type)) {
              $models->where('type', 'LIKE', "%{$request->type}%");
        }

        // duration_mins
        if (isset($request->duration_mins) && ('' != $request->duration_mins)) {
            $models->where('duration_mins', $request->duration_mins);
        }

        // students_count
        if (isset($request->students_count) && ('' != $request->students_count)) {
            $models->where('students_count', $request->students_count);
        }

        $cloned_models = clone $models;
        if(isset($request->offset) && isset($request->limit)){
            $models->offset($request->offset)->limit($request->limit);
        }

        $data['quizzes'] = $models->with(['course', 'assignee', 'questions'])->get();
        $data['total_count'] = $cloned_models->count();

        return getInternalSuccessResponse($data);
    }

    /**
     * Add|Update Quizs
     *
     * @param Request $request
     * @param Integer $query_response_id
     * @return void
     */
    public function addUpdateQuiz(Request $request, $quiz_id = null)
    {
        if (null == $quiz_id) {
            $model = new Quiz();
            $model->uuid = \Str::uuid();
            $model->created_at = date('Y-m-d H:i:s');
        } else {
            $model = Quiz::where('id', $quiz_id)->first();
        }
        $model->updated_at = date('Y-m-d H:i:s');
        $model->course_id = $request->course_id;
        $model->assignee_id = $request->assignee_id;
        $model->title = $request->title;
        $model->type = $request->type;
        if (isset($request->description) && ('' != $request->description)) {
            $model->description = $request->description;
        }
        if (isset($request->duration_mins) && ('' != $request->duration_mins)) {
            $model->duration_mins = $request->duration_mins;
        }
        if (isset($request->students_count) && ('' != $request->students_count)) {
            $model->students_count = $request->students_count;
        }

        try {
            $model->save();
            $model = Quiz::where('id', $model->id)->with(['course', 'assignee', 'questions'])->first();
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }
}