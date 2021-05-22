<?php

namespace Modules\Quiz\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Quiz\Entities\Question;

class QuestionService
{
    private $relations = [
        'quiz', 'creator', 'choices'
    ];
    /**
     * Check if an Question Exists given ID
     *
     * @param Integer $id
     * @return void
     */
    public function getQuestionById($id)
    {
        $model =  Question::where('id', $id)->first();
        if(null == $model){
            return getInternalErrorResponse('No Question Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check and fetch and Question against given ID
     *
     * @param Integer $id
     * @return void
     */
    public function checkQuestionById($id)
    {
        $model = Question::where('id', $id)->first();
        if(null == $model){
            return getInternalErrorResponse('No Question Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check if an Question Exists against given $request->quiz_uuid
     *
     * @param Request $request
     * @return void
     */
    public function checkQuestion(Request $request)
    {
        $model = Question::where('uuid', $request->question_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No Question Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Get an Question against given UUID
     *
     * @param Request $request
     * @return void
     */
    public function getQuestion(Request $request)
    {
        $model = Question::where('uuid', $request->question_uuid)->with($this->relations)->first();
        return getInternalSuccessResponse($model);
    }

    /**
     * Delete an Question by given UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteQuestion(Request $request)
    {
        $model = Question::where('uuid', $request->question_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No Question Found', [], 404, 404);
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
     * Get Question based on filters
     *
     * @param Request $request
     * @return void
     */
    public function getQuestions(Request $request)
    {
        $models = Question::orderBy('created_at');

        //quiz_id
        if(isset($request->quiz_id) && ('' != $request->quiz_id)){
            $models->where('quiz_id', $request->quiz_id);
        }
        //creator_id
        if(isset($request->creator_id) && ('' != $request->creator_id)){
            $models->where('creator_id', $request->creator_id);
        }
        // question_uuid
        if(isset($request->question_uuid) && ('' != $request->question_uuid)){
            $models->where('uuid', $request->question_uuid);
        }
        // correct_answer_id
        if(isset($request->correct_answer_id) && ('' != $request->correct_answer_id)){
            $models->where('uuid', $request->correct_answer_id);
        }
        // correct_answer
        if (isset($request->correct_answer) && ('' != $request->correct_answer)) {
            $models->where('correct_answer', 'LIKE', "%{$request->correct_answer}%");
        }

        // body
        if (isset($request->body) && ('' != $request->body)) {
             $models->where('body', 'LIKE', "%{$request->body}%");
        }

        $cloned_models = clone $models;
        if(isset($request->offset) && isset($request->limit)){
            $models->offset($request->offset)->limit($request->limit);
        }

        $data['questions'] = $models->with($this->relations)->get();
        $data['total_count'] = $cloned_models->count();

        return getInternalSuccessResponse($data);
    }

    /**
     * Add|Update Questions
     *
     * @param Request $request
     * @param Integer $question_id
     * @return void
     */
    public function addUpdateQuestion(Request $request, $question_id = null)
    {
        if (null == $question_id) {
            $model = new Question();
            $model->uuid = \Str::uuid();
            $model->created_at = date('Y-m-d H:i:s');
        } else {
            $model = Question::where('id', $question_id)->first();
        }
        $model->updated_at = date('Y-m-d H:i:s');
        $model->quiz_id = $request->quiz_id;

        if (isset($request->creator_id) && ('' != $request->creator_id)) {
            $model->creator_id = $request->creator_id;
        }
        if (isset($request->correct_answer_id) && ('' != $request->correct_answer_id)) {
            $model->correct_answer_id = $request->correct_answer_id;
        }
        if (isset($request->correct_answer) && ('' != $request->correct_answer)) {
            $model->correct_answer = $request->correct_answer;
        }
        if (isset($request->body) && ('' != $request->body)) {
            $model->body = $request->body;
        }

        try {
            $model->save();
            $model = Question::where('id', $model->id)->with($this->relations)->first();
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }

}
