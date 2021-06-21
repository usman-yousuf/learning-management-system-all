<?php

namespace Modules\Quiz\Http\Controllers\API;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Common\Services\CommonService;
use Modules\Course\Services\CourseDetailService;
use Modules\Quiz\Services\QuizChoiceService;
use Modules\Quiz\Services\QuizService;
use Modules\User\Services\ProfileService;

class QuizChoiceController extends Controller
{
    private $commonService;
    private $quizChoiceService;
    private $quizService;
    public function __construct(CommonService $commonService, QuizService $quizService, QuizChoiceService $quizChoiceService)
    {
        $this->commonService = $commonService;
        $this->quizChoiceService = $quizChoiceService;
        $this->quizService = $quizService;
    }

    /**
     * Get a Single Quiz Choice  based on uuid
     *
     * @param Request $request
     * @return void
     */
    public function getQuizChoice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quiz_choice_uuid' => 'required|exists:quiz_choices,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and fetch  Quiz Choice
        $result = $this->quizChoiceService->getQuizChoice($request);
        if(!$result['status']){
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $quiz_choice = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $quiz_choice);
    }

    /**
     * Delete Quiz Choice by UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteQuizChoice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quiz_choice_uuid' => 'required|exists:quiz_choices,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and delete Quiz
        $result = $this->quizChoiceService->deleteQuizChoice($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $quiz_choice = $result['data'];

        return $this->commonService->getSuccessResponse('Record Deleted Successfully', []);
    }

    /**
     * Get Student Choice on given filters
     *
     * @param Request $request
     * @return void
     */
    public function getQuizChoices(Request $request)
    {
        //quiz_uuid
        if(isset($request->quiz_uuid) && ('' != $request->quiz_uuid)){
            $result = $this->quizService->checkQuiz($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $quiz = $result['data'];
            $request->merge(['quiz_id' => $quiz->id]);
        }

        $result = $this->quizChoiceService->getQuizChoices($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $quiz_choice = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $quiz_choice);
    }

    /**
     * Add|Update Quiz Choice
     *
     * @param Request $request
     * @return void
     */
    public function addUpdateQuizChoice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quiz_choice_uuid' => 'exists:quiz_choices,uuid',
            'quiz_uuid' => 'required|exists:quizzes,uuid',
            'body' => 'required|string',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        //quiz_uuid
        if(isset($request->quiz_uuid) && ('' != $request->quiz_uuid)){
            $result = $this->quizService->checkQuiz($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $quiz = $result['data'];
            $request->merge(['quiz_id' => $quiz->id]);
        }

         // find  Quiz Choice by uuid if given
        $quiz_choice_id = null;
        if(isset($request->quiz_choice_uuid) && ('' != $request->quiz_choice_uuid)){
            $result = $this->quizChoiceService->checkQuizChoice($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $quiz_choice = $result['data'];
            $quiz_choice_id = $quiz_choice->id;
        }

        $result = $this->quizChoiceService->addUpdateQuiz($request, $quiz_choice_id);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $quiz = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $quiz);
    }
}
