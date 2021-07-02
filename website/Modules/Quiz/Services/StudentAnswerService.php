<?php

namespace Modules\Quiz\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Quiz\Entities\StudentQuizAnswer;

class StudentAnswerService
{
    private $relations = [
        'question'
        ,'quiz'
        , 'student'
        , 'course'
    ];

    /**
     * Check if an Student Quiz Exists given ID
     *
     * @param Integer $id
     * @return void
     */
    public function getStudentQuizAnswerById($id)
    {
        $model =  StudentQuizAnswer::where('id', $id)->first();
        if(null == $model){
            return getInternalErrorResponse('No Student Answer Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check and fetch and Student Quiz Answer against given ID
     *
     * @param Integer $id
     * @return void
     */
    public function checkStudentQuizAnswerById($id)
    {
        $model =  StudentQuizAnswer::where('id', $id)->first();
        if(null == $model){
            return getInternalErrorResponse('No Student Quiz Answer Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    // /**
    //  * Check if an Student Quiz Answer Exists against given $request->question_choice_uuid
    //  *
    //  * @param Request $request
    //  * @return void
    //  */
    // public function checkStudentQuizAnswer(Request $request)
    // {
    //     $model = StudentQuizAnswer::where('uuid', $request->question_choice_uuid)->first();
    //     if (null == $model) {
    //         return getInternalErrorResponse('No Student Quiz Answer Found', [], 404, 404);
    //     }
    //     return getInternalSuccessResponse($model);
    // }

    // /**
    //  * Get an Student Quiz Answer against given UUID
    //  *
    //  * @param Request $request
    //  * @return void
    //  */
    // public function getStudentQuizAnswer(Request $request)
    // {
    //     $model = StudentQuizAnswer::where('uuid', $request->question_choice_uuid)->with($this->relations)->first();
    //     return getInternalSuccessResponse($model);
    // }

    // /**
    //  * Delete an Student Quiz Answer by given UUID
    //  *
    //  * @param Request $request
    //  * @return void
    //  */
    // public function deleteStudentQuizAnswer(Request $request)
    // {
    //     $model = StudentQuizAnswer::where('uuid', $request->question_choice_uuid)->first();
    //     if (null == $model) {
    //         return getInternalErrorResponse('No Student Quiz Answer Found', [], 404, 404);
    //     }

    //     try{
    //         $model->delete();
    //     }
    //     catch(\Exception $ex)
    //     {
    //         return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode(), 500);
    //     }
    //     return getInternalSuccessResponse($model);
    // }


    /**
     * Get Student Quiz Answer based on filters
     *
     * @param Request $request
     * @return void
     */
    public function getStudentQuizAnswers(Request $request)
    {
        $models = StudentQuizAnswer::orderBy('created_at', 'DESC');

        //question_id
        if(isset($request->question_id) && ('' != $request->question_id)){
            $models->where('question_id', $request->question_id);
        }

        if (isset($request->quiz_id) && ('' != $request->quiz_id)) {
            $models->where('quiz_id', $request->quiz_id);
        }

        if (isset($request->student_id) && ('' != $request->student_id)) {
            $models->where('student_id', $request->student_id);
        }

        if (isset($request->course_id) && ('' != $request->course_id)) {
            $models->where('course_id', $request->course_id);
        }

        // answer_body
        if (isset($request->answer_body) && ('' != $request->answer_body)) {
            $models->where('answer_body', 'LIKE', "%{$request->answer_body}%");
        }

        $cloned_models = clone $models;
        if(isset($request->offset) && isset($request->limit)){
            $models->offset($request->offset)->limit($request->limit);
        }
        $data['answers'] = $models->with($this->relations)->get();
        $data['total_count'] = $cloned_models->count();

        return getInternalSuccessResponse($data);
    }

    // public function addUpdateQuestion(Request $request, $question_choice_id = null)
    // {
    //     // dd($request->all());
    //     if (null == $question_choice_id) {
    //         $model = new StudentQuizAnswer();
    //         $model->uuid = \Str::uuid();
    //         $model->created_at = date('Y-m-d H:i:s');
    //     } else {
    //         $model = StudentQuizAnswer::where('id', $question_choice_id)->first();
    //     }
    //     $model->updated_at = date('Y-m-d H:i:s');
    //     $model->question_id = $request->question_id;
    //     $model->body = $request->body;
    //     try {
    //         $model->save();
    //         $model = StudentQuizAnswer::where('id', $model->id)->with($this->relations)->first();
    //         return getInternalSuccessResponse($model);
    //     } catch (\Exception $ex) {
    //         return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
    //     }
    // }

    public function addUpdateBulkChoices(Request $request)
    {
        $data['correct_choice'] = null;
        foreach ($request->answers as $index => $item) {
            $item = json_decode($item);
            if(null != $item->answer_uuid && '' != $item->answer_uuid){
                $model = StudentQuizAnswer::where('uuid', $item->answer_uuid)->first();
            }
            else{
                $model = new StudentQuizAnswer();
                $model->uuid = \Str::uuid();
                $model->question_id = $request->question_id;
                $model->created_at = date('Y-m-d H:i:s');
            }
            $model->updated_at = date('Y-m-d H:i:s');
            $model->body = $item->body;

            try {
                $model->save();
                $model = StudentQuizAnswer::where('id', $model->id)->with($this->relations)->first();
                if($item->is_correct){
                    $data['correct_choice'] = $model;
                }
            } catch (\Exception $ex) {
                return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
            }
        }

        return getInternalSuccessResponse($data['correct_choice']);
    }
}
