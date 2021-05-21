<?php

namespace Modules\Quiz\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Quiz\Entities\QuizChoice;

class QuizChoiceService
{

    /**
     * Check if an Quiz Choice Exists given ID
     *
     * @param Integer $id
     * @return void
     */
    public function getQuizChoiceById($id)
    {
        $model =  QuizChoice::where('id', $id)->first();
        if(null == $model){
            return getInternalErrorResponse('No Quiz Choice Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check and fetch and Quiz Choice against given ID
     *
     * @param Integer $id
     * @return void
     */
    public function checkQuizChoiceById($id)
    {
        $model =  QuizChoice::where('id', $id)->first();
        if(null == $model){
            return getInternalErrorResponse('No Quiz Choice Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check if an Quiz Choice Exists against given $request->course_handout_uuid
     *
     * @param Request $request
     * @return void
     */
    public function checkQuizChoice(Request $request)
    {
        $model = QuizChoice::where('uuid', $request->quiz_choice_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No Quiz Choice Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Get an Quiz Choice against given UUID
     *
     * @param Request $request
     * @return void
     */
    public function getQuizChoice(Request $request)
    {
        
        $model = QuizChoice::where('uuid', $request->quiz_choice_uuid)->with(['quiz'])->first();
        return getInternalSuccessResponse($model);
    }

    /**
     * Delete an Quiz Choice by given UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteQuizChoice(Request $request)
    {
        $model = QuizChoice::where('uuid', $request->quiz_choice_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No Quiz Choice Found', [], 404, 404);
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
     * Get Quiz Choice based on filters
     *
     * @param Request $request
     * @return void
     */
    public function getQuizChoices(Request $request)
    {
        $models = QuizChoice::orderBy('created_at');

        //quiz_id
        if(isset($request->quiz_id) && ('' != $request->quiz_id)){
            $models->where('quiz_id', $request->quiz_id);
        }

        // quiz_choice_uuid
        if(isset($request->quiz_choice_uuid) && ('' != $request->quiz_choice_uuid)){
            $models->where('uuid', $request->quiz_choice_uuid);
        }

        // body
        if (isset($request->body) && ('' != $request->body)) {
        $models->where('body', 'LIKE', "%{$request->body}%");
        }

        $cloned_models = clone $models;
        if(isset($request->offset) && isset($request->limit)){
            $models->offset($request->offset)->limit($request->limit);
        }

        $data['quiz_choices'] = $models->with(['quiz'])->get();
        $data['total_count'] = $cloned_models->count();

        return getInternalSuccessResponse($data);
    }

    /**
     * Add|Update Quiz Choice
     *
     * @param Request $request
     * @param Integer $quiz_choice_id
     * @return void
     */
    public function addUpdateQuiz(Request $request, $quiz_choice_id = null)
    {
        if (null == $quiz_choice_id) {
            $model = new QuizChoice();
            $model->uuid = \Str::uuid();
            $model->created_at = date('Y-m-d H:i:s');
        } else {
            $model = QuizChoice::where('id', $quiz_choice_id)->first();
        }
        $model->updated_at = date('Y-m-d H:i:s');
        $model->quiz_id = $request->quiz_id;
        $model->body = $request->body;
        try {
            $model->save();
            $model = QuizChoice::where('id', $model->id)->with(['quiz'])->first();
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }
}
