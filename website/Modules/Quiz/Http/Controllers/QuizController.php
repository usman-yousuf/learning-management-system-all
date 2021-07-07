<?php

namespace Modules\Quiz\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\Common\Services\CommonService;
use Modules\Course\Http\Controllers\API\CourseDetailController;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Http\Controllers\API\QuestionController;
use Modules\Quiz\Http\Controllers\API\QuizController as APIQuizController;

class QuizController extends Controller
{
    private $commonService;
    private $quizCtrlObj;
    private $courseDetail;
    private $questionsDetail;

    public function __construct(
        CommonService $commonService,
        APIQuizController $quizCtrlObj,
        CourseDetailController $courseDetail,
        QuestionController $questionsDetail
    ) {
        $this->commonService = $commonService;
        $this->quizCtrlObj = $quizCtrlObj;
        $this->courseDetail = $courseDetail;
        $this->questionsDetail = $questionsDetail;
    }

    /**
     * Load All Answers given by a Student
     *
     * @param Request $request
     *
     * @return void
     */
    public function loadStudentAnswers(Request $request)
    {
        $ctrlObj = $this->questionsDetail;
        $apiResponse = $ctrlObj->loadStudentAnswers($request)->getData();

        if ($apiResponse->status) {
            $data = $apiResponse->data;
            return $this->commonService->getSuccessResponse('Question Answers loaded Successfully', $data);
        }
        return json_encode($apiResponse);
    }

    public function markStudentAnswers(Request $request)
    {
        $ctrlObj = $this->questionsDetail;
        $apiResponse = $ctrlObj->markStudentAnswers($request)->getData();

        dd($apiResponse);
        if ($apiResponse->status) {
            $data = $apiResponse->data;
            return $this->commonService->getSuccessResponse('Question Answer Saved Successfully', $data);
        }
        return json_encode($apiResponse);
    }

    public function getStudentQuizResult(Request $request)
    {
        $ctrlObj = $this->questionsDetail;
        $apiResponse = $ctrlObj->getStudentQuizResult($request)->getData();

        if ($apiResponse->status) {
            $data = $apiResponse->data;
            return $this->commonService->getSuccessResponse('Student Quiz Result Fetched Successfully', $data);
        }
        return json_encode($apiResponse);
    }


    /**
     * Display a listing of Quiz.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $user_id = $request->user()->profile->id;
        $request->merge([
            'assignee_id' =>$user_id,
            'teacher_id' => $user_id
        ]);

        $ctrlObj = $this->quizCtrlObj;
        $apiResponse = $ctrlObj->getQuizzes($request)->getData();
        $data = $apiResponse->data;

        if($request->getMethod() =='GET'){
            $data->requestFilters = [];
            if(!$apiResponse->status){
                return abort(500, 'Smething went wrong');
            }
        }
        else{

            $data->requestFilters = $request->all();
        }
        // dd($data->quizzes[0]->question->body);
        return view('quiz::quizez.index', ['data' => $data]);
    }


    /**
     * Add update quizzes through web.
     * @return Renderable
     */
    public function updateQuizzes(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'quiz_uuid' => 'exists:quizzes,uuid',
            'course_uuid' => 'required|exists:courses,uuid',
            'slot_uuid' => 'required|exists:course_slots,uuid',
            'quiz_title' => 'required|string',
            'description' => 'required|string',
            'quiz_type' => 'required|string',
            'quiz_duration' => 'required|numeric',
            'due_date' => 'required|date'
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }
        $profile_id = $request->user()->profile->id;
        if(!isset($request->quiz_uuid) || (null == $request->quiz_uuid)){
            unset($request['quiz_uuid']);
        }
        $request->merge([
            'assignee_id' => $profile_id,
            'duration_mins' => $request->quiz_duration,
            'title' => $request->quiz_title,
            'type' => $request->quiz_type,
        ]);
        $ctrlObj = $this->quizCtrlObj;
        $apiResponse = $ctrlObj->addUpdateQuiz($request)->getData();
        // $data = $apiResponse->data;
        // dd($request->all());
        if($apiResponse->status){
            $data = $apiResponse->data;
            return $this->commonService->getSuccessResponse('Quiz Saved Successfully', $data);
        }
        return json_encode($apiResponse);
    }



    public function viewQuiz($uuid, Request $request)
    {
        $request->merge([
            'quiz_uuid' => $uuid,
        ]);
        $ctrlObj = $this->quizCtrlObj;

        // validate and get Quiz
        $response = $ctrlObj->getQuiz($request)->getData();
        if(!$response->status){
            if($response->exceptionCode == 404){
                return view('common::errors.404', ['message' => 'no Quiz Found']);
            }
            return view('common::errors.500', ['message' => 'Intenal Server Error']);
        }
        $quiz = $response->data;

        if(\Auth::user()->profile_type == 'teacher')
        {
            // detremine the view to show
            $viewName = 'test_question';
            if($quiz->type == 'test'){
                $viewName = "quiz::quizez.test_question";
            }
            else if($quiz->type == 'mcqs'){
                $viewName = "quiz::quizez.mcqs";
            }
            else if($quiz->type == 'boolean'){
                $viewName = "quiz::quizez.boolean";
            }
        }
        else if(\Auth::user()->profile_type == 'student')
        {
            $viewName = 'test_question';
            if($quiz->type == 'test'){
                // dd("123");
                $viewName = "quiz::quizez.test_question";
            }
            else if($quiz->type == 'mcqs'){
                $viewName = "quiz::quizez.mcqs";
            }
            else if($quiz->type == 'boolean'){
                $viewName = "quiz::quizez.mcqs";
            }

        }


        return view($viewName, ['data' => $quiz, 'data_questions' => $quiz->questions]);
    }

    /**
     * WASTED . . ..
     */
    // public function testQuestion($uuid, Request $request)
    // {
    //     $request->merge([
    //         'quiz_uuid'=> $uuid,
    //     ]);
    //     $testCntrlObj = $this->quizCtrlObj;
    //     $apiResponse = $testCntrlObj->getQuiz($request)->getData();
    //     $data = $apiResponse->data;
    //     // dd($data);
    //     $questCntrlObj = $this->questionsDetail;
    //     $getAllQuestObj  = $questCntrlObj->getQuestions($request)->getData();
    //     $data_questions =$getAllQuestObj->data;
    //     $data_questions =$data_questions->questions;

    //     if($data-> type == "test")
    //     {
    //         // dd($data);
    //         return view('quiz::quizez.test_question', ['data' => $data, 'data_questions' =>$data_questions]);
    //     }

    //     if($data->type == "mcqs")
    //     {
    //         return view('quiz::quizez.mcqs', ['data' => $data, 'data_questions' =>$data_questions]);
    //     }

    //     if($data->type == "boolean") {

    //         // dd($data, $data_questions);
    //         return view('quiz::quizez.boolean',  ['data' => $data, 'data_questions' =>$data_questions]);
    //     }
    // }


    /**
     * Add Test Add Question
     * @return Renderable
     */
    public function addTestQuestion($uuid, Request $request)
    {
        if(!isset($request->question_uuid) || (null == $request->question_uuid)){
            unset($request['question_uuid']);
        }
        $request->merge([
            'body' => $request->test_question,
            'correct_answer' => $request->test_answer,
            'creater_uuid' => $request->assignee_id,
        ]);

        $ctrlObj = $this->questionsDetail;
        $apiResponse = $ctrlObj->addUpdateQuestion($request)->getData();
        if($apiResponse->status){
            $data = $apiResponse->data;
            return $this->commonService->getSuccessResponse('Quiz Question Saved Successfully', $data);
        }
        return json_encode($apiResponse);

        // dd(123);
        // dd($request->all());
        // if($request->question_uuid)
        // {
        //     $request->merge([
        //         'question_uuid' => $request->question_uuid,
        //         'body' =>$request->add_question_textarea,
        //         'correct_answer' => $request->add_answer_textarea,
        //         'quiz_uuid' => $request->quiz_test_uuid,
        //         'creater_uuid' => $request->assignee_id,

        //     ]);
        //     // dd($request->all());
            $questCntrlObj = $this->questionsDetail;
            $apiResponse = $questCntrlObj->addUpdateQuestion($request)->getData();
        //     if($apiResponse->status){
        //         $data = $apiResponse->data;
        //         return $this->commonService->getSuccessResponse('Course Saved Successfully', $data);
        //     }
        //     return json_encode($apiResponse);
        //     // return redirect()->back();
        // }
        // $request->merge([
        //     'quiz_uuid'=> $uuid,
        //     'body' => $request->add_question_textarea,
        //     'correct_answer' => $request->add_answer_textarea
        // ]);

        //     $questCntrlObj = $this->questionsDetail;
        //     $apiResponse = $questCntrlObj->addUpdateQuestion($request)->getData();

        //     // dd($apiResponse->data);
        //     if($apiResponse->status){
        //         $data = $apiResponse->data;
        //         return $this->commonService->getSuccessResponse('Course Saved Successfully', $data);
        //     }
        //     return json_encode($apiResponse);
        // return redirect()->back();
    }

    /**
     * Delete Quiz Question .
     * @return Renderable
     */
    public function deleteQuizQuestion( Request $request)
    {
        $ctrlObj = $this->questionsDetail;
        $apiResponse = $ctrlObj->deleteQuestion($request)->getData();
        // dd($apiResponse->status);
        if ($apiResponse->status) {
            $data = $apiResponse->data;
            return $this->commonService->getSuccessResponse('Test Question Deleted Successfully', $data);
        }
        return json_encode($apiResponse);
    }


    /**
     * Add Boolean (True , false) Question
     * @return Renderable
     */
    public function addBooleanQuestion($uuid, Request $request)
    {
        $request->merge([
            'question_uuid' => $request->question_uuid,
            'quiz_uuid'=> $uuid,
            'creator_uuid' =>$request->user()->profile->uuid, // teacher uuid that is ologged in
        ]);

        $questCntrlObj = $this->questionsDetail;

        $apiResponse = $questCntrlObj->updateQuestionsPlusChoices($request)->getData();

        // dd($apiResponse->data);
        if($apiResponse->status){
            $data = $apiResponse->data;
            return $this->commonService->getSuccessResponse('Question and choices Saved Successfully', $data);
        }
        return json_encode($apiResponse);
        // return redirect()->back();
    }


    /**
     * Add Mutliple Choice Question
     * @return Renderable
     */
    public function addMutlipleChoiceQuestion($uuid, Request $request)
    {
        $request->merge([
            'question_uuid' => $request->answer_boolean_question,
            'quiz_uuid'=> $uuid,
            'creator_uuid' =>$request->assignee_id,
            'question_body' => $request->add_boolean_question_textarea,
        ]);

            $questCntrlObj = $this->questionsDetail;

            $apiResponse = $questCntrlObj->updateQuestionsPlusChoices($request)->getData();

            // dd($apiResponse->data);
            if($apiResponse->status){
                $data = $apiResponse->data;
                return $this->commonService->getSuccessResponse('Question and choices Saved Successfully', $data);
            }
            return json_encode($apiResponse);
        // return redirect()->back();
    }



    /**
     * Delete quiz Question .
     * @return Renderable
     */
    // public function deleteTestQuiz( Request $request)
    // {
    //     $request->merge(['question_uuid' => $request->quiz_question_uuid]);
    //     $testQuestobj = $this->questionsDetail;
    //     $apiResponse = $testQuestobj->deleteQuestion($request)->getData();
    //     // dd($apiResponse->status);
    //   if ($apiResponse->status) {
    //       $data = $apiResponse->data;
    //       return $this->commonService->getSuccessResponse('Quiz Question Deleted Successfully', $data);
    //   }
    //   return json_encode($apiResponse);
    // }

    /**
     * Delete a Question
     *
     * @param Request $request
     * @return void
     */
    public function deleteQuestion(Request $request)
    {
        $ctrlObj = $this->questionsDetail;
        $apiResponse = $ctrlObj->deleteQuestion($request)->getData();
        if ($apiResponse->status) {
            $data = $apiResponse->data;
            return $this->commonService->getSuccessResponse('Question Deleted Successfully', $data);
        }
        return json_encode($apiResponse);
    }




    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('quiz::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('quiz::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('quiz::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
