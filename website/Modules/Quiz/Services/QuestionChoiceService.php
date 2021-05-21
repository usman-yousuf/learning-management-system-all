<?php

namespace Modules\Quiz\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Quiz\Entities\QuestionChoice;

class QuestionChoiceService
{
    private $relations = [
        'question'
    ];

    /**
     * Check if an Question Choice Exists given ID
     *
     * @param Integer $id
     * @return void
     */
    public function getQuestionChoiceById($id)
    {
        $model =  QuestionChoice::where('id', $id)->first();
        if(null == $model){
            return getInternalErrorResponse('No Question Choice Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check and fetch and Question Choice against given ID
     *
     * @param Integer $id
     * @return void
     */
    public function checkQuestionChoiceById($id)
    {
        $model =  QuestionChoice::where('id', $id)->first();
        if(null == $model){
            return getInternalErrorResponse('No Question Choice Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check if an Question Choice Exists against given $request->course_handout_uuid
     *
     * @param Request $request
     * @return void
     */
    public function checkQuestionChoice(Request $request)
    {
        $model = QuestionChoice::where('uuid', $request->question_choice_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No Question Choice Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Get an Question Choice against given UUID
     *
     * @param Request $request
     * @return void
     */
    public function getQuestionChoice(Request $request)
    {

        $model = QuestionChoice::where('uuid', $request->question_choice_uuid)->with($this->relations)->first();
        return getInternalSuccessResponse($model);
    }

    /**
     * Delete an Question Choice by given UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteQuestionChoice(Request $request)
    {
        $model = QuestionChoice::where('uuid', $request->question_choice_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No Question Choice Found', [], 404, 404);
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
     * Get Question Choice based on filters
     *
     * @param Request $request
     * @return void
     */
    public function getQuestionChoices(Request $request)
    {
        $models = QuestionChoice::orderBy('created_at', 'DESC');

        //question_id
        if(isset($request->question_id) && ('' != $request->question_id)){
            $models->where('question_id', $request->question_id);
        }

        // question_choice_uuid
        if(isset($request->question_choice_uuid) && ('' != $request->question_choice_uuid)){
            $models->where('uuid', $request->question_choice_uuid);
        }

        // body
        if (isset($request->body) && ('' != $request->body)) {
            $models->where('body', 'LIKE', "%{$request->body}%");
        }

        $cloned_models = clone $models;
        if(isset($request->offset) && isset($request->limit)){
            $models->offset($request->offset)->limit($request->limit);
        }

        $data['question_choices'] = $models->with($this->relations)->get();
        $data['total_count'] = $cloned_models->count();

        return getInternalSuccessResponse($data);
    }

    /**
     * Add|Update Question Choice
     *
     * @param Request $request
     * @param Integer $question_choice_id
     * @return void
     */
    public function addUpdateQuestion(Request $request, $question_choice_id = null)
    {
        if (null == $question_choice_id) {
            $model = new QuestionChoice();
            $model->uuid = \Str::uuid();
            $model->created_at = date('Y-m-d H:i:s');
        } else {
            $model = QuestionChoice::where('id', $question_choice_id)->first();
        }
        $model->updated_at = date('Y-m-d H:i:s');
        $model->question_id = $request->question_id;
        $model->body = $request->body;
        try {
            $model->save();
            $model = QuestionChoice::where('id', $model->id)->with($this->relations)->first();
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }
}
