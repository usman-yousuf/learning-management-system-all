<?php

namespace Modules\Quiz\Http\Controllers\API;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Common\Services\CommonService;
use Modules\Course\Services\CourseDetailService;
use Modules\Quiz\Services\QuizService;
use Modules\User\Services\ProfileService;

class QuizController extends Controller
{
    private $commonService;
    private $courseDetailService;
    private $profileService;
    private $quizService;

    public function __construct(CommonService $commonService, QuizService $quizService, CourseDetailService $courseDetailService, ProfileService $profileService)
    {
        $this->commonService = $commonService;
        $this->courseDetailService = $courseDetailService;
        $this->profileService = $profileService;
        $this->quizService = $quizService;
    }

    /**
     * Get a Single Quiz  based on uuid
     *
     * @param Request $request
     * @return void
     */
    public function getQuiz(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quiz_uuid' => 'required|exists:quizzes,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and fetch  Quiz
        $result = $this->quizService->getQuiz($request);
        if(!$result['status']){
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $quiz = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $quiz);
    }

    /**
     * Delete Quiz by UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteQuiz(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quiz_uuid' => 'required|exists:quizzes,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and delete Quiz
        $result = $this->quizService->deleteQuiz($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $quiz = $result['data'];

        return $this->commonService->getSuccessResponse('Record Deleted Successfully', []);
    }

    /**
     * Get Handout Student query on given filters
     *
     * @param Request $request
     * @return void
     */
    public function getQuizzes(Request $request)
    {
        //course_id
        if(isset($request->course_uuid) && ('' != $request->course_uuid)){
            $result = $this->courseDetailService->checkCourseDetail($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $course = $result['data'];
            $request->merge(['course_id' => $course->id]);
        }

        //assignee_id
        if(isset($request->assignee_uuid) && ('' != $request->assignee_uuid)){
            $request->merge(['profile_uuid' => $request->assignee_uuid]);
            $result = $this->profileService->checkTeacher($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $assignee = $result['data'];
            $request->merge(['assignee_id' => $assignee->id]);
        }

        $result = $this->quizService->getQuizzes($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $quiz = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $quiz);
    }

    /**
     * Add|Update Quiz
     *
     * @param Request $request
     * @return void
     */
    public function addUpdateQuiz(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quiz_uuid' => 'exists:quizzes,uuid',
            'course_uuid' => 'required|exists:courses,uuid',
            'assignee_uuid' => 'required|exists:profiles,uuid',
            'correct_quiz_choice_id' => 'string',
            'correct_answer' => 'required|string',
            'title' => 'required|string',
            'description' => 'required|string',
            'type' => 'required|string',
            'duration_mins' => 'required|numeric',
            'students_count' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        //course_id
        if(isset($request->course_uuid) && ('' != $request->course_uuid)){
            $result = $this->courseDetailService->checkCourseDetail($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $course = $result['data'];
            $request->merge(['course_id' => $course->id]);
        }

        //assignee_id
        if(isset($request->assignee_uuid) && ('' != $request->assignee_uuid)){
            $request->merge(['profile_uuid' => $request->assignee_uuid]);
            $result = $this->profileService->checkTeacher($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $assignee = $result['data'];
            $request->merge(['assignee_id' => $assignee->id]);
        }
        // find  Quiz by uuid if given
        $quiz_id = null;
        if(isset($request->quiz_uuid) && ('' != $request->quiz_uuid)){
            $result = $this->quizService->checkQuiz($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $quiz = $result['data'];
            $quiz_id = $quiz->id;
        }

        //correct_quiz_id

        $result = $this->quizService->addUpdateQuiz($request, $quiz_id);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $quiz = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $quiz);
    }
}
