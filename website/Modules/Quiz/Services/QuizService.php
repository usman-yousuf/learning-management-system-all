<?php

namespace Modules\Quiz\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Common\Services\NotificationService;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizAttemptStats;

class QuizService
{
    /**
     * Increament 1 total marks count for each new question students
     *
     * @param Request $request
     * @return void
     */
    public function incrementTotalMarksByQuizId(Request $request)
    {
        $model = Quiz::where('id', $request->quiz_id)->first();
        if (null == $model) {
            return getInternalErrorResponse('No Quiz Found', [], 404, 404);
        }

        try {
            $model->total_marks += 1;
            $model->save();
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode(), 500);
        }

        return getInternalSuccessResponse($model);
    }

    /**
     * Increament 1 in student count for attemptin students
     *
     * @param Request $request
     * @return void
     */
    public function incrementStudentCountByQuizId(Request $request)
    {
        $model = Quiz::where('id', $request->quiz_id)->first();
        if (null == $model) {
            return getInternalErrorResponse('No Quiz Found', [], 404, 404);
        }

        try {
            $model->students_count +=1;
            $model->save();
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode(), 500);
        }

        return getInternalSuccessResponse($model);
    }


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
        $model = Quiz::where('uuid', $request->quiz_uuid)
            ->with(['course', 'assignee', 'questions', 'myAttempt', 'lastAnswer', 'slot'])
            ->first();
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

        $model = Quiz::where('uuid', $request->quiz_uuid)->with(['course', 'assignee', 'questions', 'slot'])->first();
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
        $models = Quiz::orderBy('created_at', 'ASC');

        //course_id
        if(isset($request->course_id) && ('' != $request->course_id)){
            $models->where('course_id', $request->course_id);
        }

        //slot_id
        if (isset($request->slot_id) && ('' != $request->slot_id)) {
            $models->where('slot_id', $request->slot_id);
        }

        //assignee_id
        if(isset($request->assignee_id) && ('' != $request->assignee_id)){
        $models->where('assignee_id', $request->assignee_id);
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

        $data['quizzes'] = $models->with(['course', 'assignee', 'questions', 'slot'])->get();
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
        $model->slot_id = $request->slot_id;
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

        // $model->start_date = $request->start_date;
        $model->due_date = $request->due_date;
        if (isset($request->due_date) && ('' != $request->due_date)) {
            $model->due_date = $request->due_date;
            // dd($model->due_date = $request->due_date);
        }
        else{
            $model->due_date = date('Y-m-d', strtotime('+1 day'));
        }
        if (isset($request->extended_date) && ('' != $request->extended_date)) {
            $model->extended_date = $request->extended_date;
        }
        else {
            $model->extended_date = date('Y-m-d', strtotime('+1 day'));
        }

        // dd($model->getAttributes());

        try {
            $model->save();
            $model = Quiz::where('id', $model->id)->with(['course', 'assignee' , 'questions', 'slot'])->first();

            if (null == $quiz_id) {

                // send notification
                $notiService = new NotificationService();
                $receiverIds = getCourseEnrolledStudentsIds($model->course);
                $request->merge([
                    'notification_type' => listNotficationTypes()['create_quiz']
                    , 'notification_text' => getNotificationText($request->user()->profile->first_name, 'create_quiz')
                    , 'notification_model_id' => $model->id
                    , 'notification_model_uuid' => $model->uuid
                    , 'notification_model' => 'quizzez'

                    , 'additional_ref_id' => $model->course->id
                    , 'additional_ref_uuid' => $model->course->uuid
                    , 'additional_ref_model_name' => 'courses'

                    , 'is_activity' => true
                    , 'start_date' => $model->created_at
                    , 'end_date' => (null != $model->extended_date)? $model->extended_date : $model->due_date
                ]);

                $result =  $notiService->sendNotifications($receiverIds, $request, true);
                if(!$result['status'])
                {
                    return $result;
                }
            }
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            // dd($ex);
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }

    /**
     * Update Quiz Attempt Stats based on marking of indiviual questions
     *
     * @param Request $request
     * @param boolean $isAdditionMode
     * @return void
     */
    public function incrementQuizAttempStats(Request $request, $isAdditionMode = true)
    {
        $model = QuizAttemptStats::
            where('student_id', $request->student_id)
            ->where('course_id', $request->course_id)
            ->where('quiz_id', $request->quiz_id)
        ->first();

        if(null == $model){
            return getInternalErrorResponse('No Quiz Attempt Found', [], 404, 404);
        }
        if($isAdditionMode){
            $model->total_correct_answers++;
        }
        else{
            $model->total_wrong_answers++;
        }

        if($model->total_questions == $model->total_correct_answers+ $model->total_wrong_answers){
            $model->status = 'marked';
        }

        try {
            $model->save();
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            // dd($ex);
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }

    /**
     * update Quiz Attempts Quiz
     *
     * @param Request $request
     * @param [type] $attempt_id
     * @return void
     */
    public function updateQuizAttempStats(Request $request, $attempt_id = null)
    {
        if (null == $attempt_id) {
            $model = new QuizAttemptStats();
            $model->uuid = \Str::uuid();
            $model->student_id = $request->student_id;
            $model->course_id = $request->course_id;
            $model->quiz_id = $request->quiz_id;
            $model->created_at = date('Y-m-d H:i:s');
        } else {
            $model = QuizAttemptStats::where('id', $attempt_id)->first();
        }
        $model->updated_at = date('Y-m-d H:i:s');

        $model->total_questions = $request->total_questions;

        if (isset($request->total_marks) && ('' != $request->total_marks)) {
            $model->total_marks = $request->total_marks;
            $model->marks_per_question = $request->total_marks / $request->total_questions;
        }
        else{
            $model->total_marks = $request->total_questions;
            $model->marks_per_question = 1;
        }

        $model->total_correct_answers = $request->total_correct_answers;
        $model->total_wrong_answers = $request->total_questions - $request->total_correct_answers;

        if(isset($request->quiz_status) && ('' != $request->quiz_status)){
            $model->status = $request->quiz_status;
        }

        try {
            $model->save();
            $model = QuizAttemptStats::where('id', $model->id)->with(['quiz'])->first();
            // dd($model);
            if (null == $attempt_id) {
                $notiService = new NotificationService();
                $receiverIds = [$model->quiz->assignee_id];
                $request->merge([
                    'notification_type' => listNotficationTypes()['submit_quiz']
                    , 'notification_text' => getNotificationText($request->user()->profile->first_name, 'submit_quiz')
                    , 'notification_model_id' => $model->id
                    , 'notification_model_uuid' => $model->uuid
                    , 'notification_model' => 'quiz_attempt_stats'
                    , 'additional_ref_id' => $model->quiz->id
                    , 'additional_ref_uuid' => $model->quiz->uuid
                    , 'additional_ref_model_name' => 'quizzes'
                    , 'is_activity' => true
                    , 'start_date' => null
                    , 'end_date' => null
                ]);
                $result =  $notiService->sendNotifications($receiverIds, $request, true);
                if (!$result['status']) {
                    return $result;
                }
            }
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            // dd($ex);
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }
}
