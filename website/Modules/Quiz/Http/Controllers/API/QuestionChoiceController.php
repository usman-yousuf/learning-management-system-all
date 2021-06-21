<?php

namespace Modules\Quiz\Http\Controllers\API;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Common\Services\CommonService;
use Modules\Quiz\Services\QuestionChoiceService;
use Modules\Quiz\Services\QuestionService;

class QuestionChoiceController extends Controller
{
    private $commonService;
    private $questionChoiceService;
    private $questionService;
    public function __construct(CommonService $commonService, QuestionService $questionService, QuestionChoiceService $questionChoiceService)
    {
        $this->commonService = $commonService;
        $this->questionChoiceService = $questionChoiceService;
        $this->questionService = $questionService;
    }

    /**
     * Get a Single Question Choice  based on uuid
     *
     * @param Request $request
     * @return void
     */
    public function getQuestionChoice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question_choice_uuid' => 'required|exists:question_choices,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and fetch  Question Choice
        $result = $this->questionChoiceService->getQuestionChoice($request);
        if(!$result['status']){
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $qestion_choice = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $qestion_choice);
    }

    /**
     * Delete Question Choice by UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteQuestionChoice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question_choice_uuid' => 'required|exists:question_choices,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and delete Question Choice
        $result = $this->questionChoiceService->deleteQuestionChoice($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $question_choice = $result['data'];

        return $this->commonService->getSuccessResponse('Record Deleted Successfully', []);
    }

    /**
     * Get Question Choice on given filters
     *
     * @param Request $request
     * @return void
     */
    public function getQuestionChoices(Request $request)
    {
        //question_uuid
        if(isset($request->question_uuid) && ('' != $request->question_uuid)){
            $result = $this->questionService->checkQuestion($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $question = $result['data'];
            $request->merge(['question_id' => $question->id]);
        }

        $result = $this->questionChoiceService->getQuestionChoicess($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $question_choice = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $question_choice);
    }

    /**
     * Add|Update Question Choice
     *
     * @param Request $request
     * @return void
     */
    public function addUpdateQuestionChoice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question_choice_uuid' => 'exists:question_choices,uuid',
            'question_uuid' => 'required|exists:questions,uuid',
            'body' => 'required|string',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        //question_uuid
        if(isset($request->question_uuid) && ('' != $request->question_uuid)){
            $result = $this->questionService->checkQuestion($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $question = $result['data'];
            $request->merge(['question_id' => $question->id]);
        }

         // find  Question Choice by uuid if given
        $question_choice_id = null;
        if(isset($request->question_choice_uuid) && ('' != $request->question_choice_uuid)){
            $result = $this->questionChoiceService->checkQuestionChoice($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $question_choice = $result['data'];
            $question_choice_id = $question_choice->id;
        }

        $result = $this->questionChoiceService->addUpdateQuestion($request, $question_choice_id);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $question_choice = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $question_choice);
    }
}
