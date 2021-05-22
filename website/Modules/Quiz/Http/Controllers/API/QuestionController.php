<?php

namespace Modules\Quiz\Http\Controllers\API;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Common\Services\CommonService;
use Modules\Course\Services\CourseDetailService;
use Modules\Quiz\Services\QuestionChoiceService;
use Modules\Quiz\Services\QuestionService;
use Modules\Quiz\Services\QuizService;
use Modules\User\Services\ProfileService;

class QuestionController extends Controller
{
    private $commonService;
    private $courseDetailService;
    private $profileService;
    private $questionService;
    private $quizService;
    private $questionChoiceService;

    public function __construct(CommonService $commonService, QuizService $quizService, QuestionService $questionService, CourseDetailService $courseDetailService, ProfileService $profileService, QuestionChoiceService $questionChoiceService)
    {
        $this->commonService = $commonService;
        $this->courseDetailService = $courseDetailService;
        $this->profileService = $profileService;
        $this->questionService = $questionService;
        $this->quizService = $quizService;
        $this->questionChoiceService = $questionChoiceService;
    }

    /**
     * Get a Single Question  based on uuid
     *
     * @param Request $request
     * @return void
     */
    public function getQuestion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question_uuid' => 'required|exists:questions,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and fetch  Question
        $result = $this->questionService->getQuestion($request);
        if(!$result['status']){
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $question = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $question);
    }

    /**
     * Delete Question by UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteQuestion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question_uuid' => 'required|exists:questions,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and delete Question
        $result = $this->questionService->deleteQuestion($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $question = $result['data'];

        return $this->commonService->getSuccessResponse('Record Deleted Successfully', []);
    }

    /**
     * Get Handout Student query on given filters
     *
     * @param Request $request
     * @return void
     */
    public function getQuestions(Request $request)
    {
        // dd($request->all());
        //quiz_id
        if(isset($request->quiz_uuid) && ('' != $request->quiz_uuid)){
            $result = $this->quizService->checkQuiz($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $quiz = $result['data'];
            $request->merge(['quiz_id' => $quiz->id]);
        }

        //creator_id
        if(isset($request->creator_id) && ('' != $request->creator_id)){
            $request->merge(['profile_uuid' => $request->creator_id]);
            $result = $this->profileService->checkTeacher($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $creator = $result['data'];
            $request->merge(['creator_id' => $creator->id]);
        }

        $result = $this->questionService->getQuestions($request);
        // dd($result['data']);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $question = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $question);
    }

    /**
     * Add|Update Question
     *
     * @param Request $request
     * @return void
     */
    public function addUpdateQuestion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question_uuid' => 'exists:questions,uuid',
            'quiz_uuid' => 'required|exists:quizzes,uuid',
            'creator_uuid' => 'exists:profiles,uuid',
            'body' => 'string',
            'correct_answer_id' => 'string',
            'correct_answer' => 'string',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        //quiz_id
         if(isset($request->quiz_uuid) && ('' != $request->quiz_uuid)){
            $result = $this->quizService->checkQuiz($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $quiz = $result['data'];
            $request->merge(['quiz_id' => $quiz->id]);
        }

        //creator_id
        if(isset($request->creator_id) && ('' != $request->creator_id)){
            $request->merge(['profile_uuid' => $request->creator_id]);
            $result = $this->profileService->checkTeacher($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $creator = $result['data'];
            $request->merge(['creator_id' => $creator->id]);
        }

        // find  Question by uuid if given
        $question_id = null;
        if(isset($request->question_uuid) && ('' != $request->question_uuid)){
            $result = $this->questionService->checkQuestion($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $question = $result['data'];
            $question_id = $question->id;
        }

        //correct_question_id

        $result = $this->questionService->addUpdateQuestion($request, $question_id);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $question = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $question);
    }

    public function updateQuestionsPlusChoices(Request $request)
    {
    //    dd($request->all());
            // var_dump($request->all());
        $validator = Validator::make($request->all(), [
            'question_uuid' => 'exists:questions,uuid',
            'quiz_uuid' => 'required|exists:quizzes,uuid',
            'creator_uuid' => 'required|exists:profiles,uuid',
            // 'question_choice_uuid' => 'exists:question_choices,uuid',
            'question_body' => 'string',
            'correct_answer_id' => 'string',
            'correct_answer' => 'string',
            'ans_body' => 'string',
            'answers.*' => 'required|json'
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        //merge question_body to body
          $request->merge(['body' => $request->question_body]);

        // dd($request->answers);
                // //quiz_id
        if(isset($request->quiz_uuid) && ('' != $request->quiz_uuid)){
            $result = $this->quizService->checkQuiz($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $quiz = $result['data'];
            $request->merge(['quiz_id' => $quiz->id]);
        }

        //creator_id
        if(isset($request->creator_id) && ('' != $request->creator_id)){
            $request->merge(['profile_uuid' => $request->creator_id]);
            $result = $this->profileService->checkTeacher($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $creator = $result['data'];
            $request->merge(['creator_id' => $creator->id]);
        }

        //  //question_choice_uuid
        //  if(isset($request->question_choice_uuid) && ('' != $request->question_choice_uuid)){
        //     $result = $this->questionChoiceService->checkQuestionChoice($request);
        //     if (!$result['status']) {
        //         return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        //     }
        //     $question_choice = $result['data'];
        //     $request->merge(['question_choice_id' => $question_choice->id]);
        // }

        // find  Question by uuid if given
        $question_id = null;
        if(isset($request->question_uuid) && ('' != $request->question_uuid)){
            $result = $this->questionService->checkQuestion($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $question = $result['data'];
            $question_id = $question->id;
        }

        //correct_question_id

        $result = $this->questionService->addUpdateQuestion($request, $question_id);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $question = $result['data'];

          return $this->commonService->getSuccessResponse('Success', $question);

        // create question
        
        $correct_ans_id = null;

        // loop through answers and save them in db
        foreach ($request->answers as $index => $item) {
            $result = json_decode($item);
            var_dump($result);
            // save answer in db
            // store correct answer id in above var
            # code...
        }

        // update question with correct answer only if 
        exit;

      
        // //quiz_id
        // if(isset($request->quiz_uuid) && ('' != $request->quiz_uuid)){
        //     $result = $this->quizService->checkQuiz($request);
        //     if (!$result['status']) {
        //         return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        //     }
        //     $quiz = $result['data'];
        //     $request->merge(['quiz_id' => $quiz->id]);
        // }

        // //creator_id
        // if(isset($request->creator_id) && ('' != $request->creator_id)){
        //     $request->merge(['profile_uuid' => $request->creator_id]);
        //     $result = $this->profileService->checkTeacher($request);
        //     if (!$result['status']) {
        //         return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        //     }
        //     $creator = $result['data'];
        //     $request->merge(['creator_id' => $creator->id]);
        // }

        //  //question_choice_uuid
        //  if(isset($request->question_choice_uuid) && ('' != $request->question_choice_uuid)){
        //     $result = $this->questionChoiceService->checkQuestionChoice($request);
        //     if (!$result['status']) {
        //         return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        //     }
        //     $question_choice = $result['data'];
        //     $request->merge(['question_choice_id' => $question_choice->id]);
        // }

        // // find  Question by uuid if given
        // $question_id = null;
        // if(isset($request->question_uuid) && ('' != $request->question_uuid)){
        //     $result = $this->questionService->checkQuestion($request);
        //     if (!$result['status']) {
        //         return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        //     }
        //     $question = $result['data'];
        //     $question_id = $question->id;
        // }

        // //correct_question_id

        // $result = $this->questionService->addUpdateQuestion($request, $question_id);
        // if (!$result['status']) {
        //     return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        // }
        // $question = $result['data'];

        //   return $this->commonService->getSuccessResponse('Success', $question);
    
    }
}
