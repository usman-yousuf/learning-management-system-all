<?php

namespace Modules\Quiz\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
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


    /**
     * Display a listing of Quiz.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $user_id = $request->user()->profile->id;
        $request->merge(['assignee_id' =>$user_id,
                         'teacher_id' => $user_id]);

        $ctrlObj = $this->quizCtrlObj;
        $apiResponse = $ctrlObj->getQuizzes($request)->getData();
        $data = $apiResponse->data;

        $courses = $this->courseDetail->getCourseDetails($request)->getData();
        $courses_details = $courses->data;

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
        return view('quiz::quizez.index', ['data' => $data, 'courses_details' => $courses_details]);
    }


    /**
     * Add update quizzes through web.
     * @return Renderable
     */
    public function updateQuizzes(Request $request)
    {
        $user_id = $request->user()->profile->id;

        $request->merge([
            'assignee_id' =>$user_id,
            'course_uuid' => $request->course,
            'description' => $request->comment_text,
            'duration_mins' => $request->quiz_duration,
            'title' => $request->quiz_title,
            'type' => $request->test,
        ]);
        $ctrlObj = $this->quizCtrlObj;
        $apiResponse = $ctrlObj->addUpdateQuiz($request)->getData();
        // $data = $apiResponse->data;
        // dd($data);
        if($apiResponse->status){
            $data = $apiResponse->data;
            return $this->commonService->getSuccessResponse('Course Saved Successfully', $data);
        }
        return json_encode($apiResponse);
    }



    /**
     * Test Question .
     * @return Renderable
     */
    public function testQuestion($uuid, Request $request)
    {
        $request->merge([
            'quiz_uuid'=> $uuid,
        ]);
        $testCntrlObj = $this->quizCtrlObj;
        $apiResponse = $testCntrlObj->getQuiz($request)->getData();
        $data = $apiResponse->data;
        // dd($data);
        $questCntrlObj = $this->questionsDetail;
        $getAllQuestObj  = $questCntrlObj->getQuestions($request)->getData();
        $data_questions =$getAllQuestObj->data;
        $data_questions =$data_questions->questions;

        if($data-> type == "test")
        {
            // dd($data_questions);
            return view('quiz::quizez.test_question', ['data' => $data, 'data_questions' =>$data_questions]);
        }

        if($data->type == "mcqs")
        {
            return view('quiz::quizez.mcqs');
        }

        if($data->type == "boolean") {

            // dd($data, $data_questions);
            return view('quiz::quizez.boolean',  ['data' => $data, 'data_questions' =>$data_questions]);
        }
    }


    /**
     * Add Test Add Question
     * @return Renderable
     */
    public function addTestQuestion($uuid, Request $request)
    {
        // dd(123);
        // dd($request->all());
        if($request->answer_test_question)
        {
            $request->merge([
                'question_uuid' => $request->answer_test_question,
                'body' =>$request->add_question_textarea,
                'correct_answer' => $request->add_answer_textarea,
                'quiz_uuid' => $request->quiz_test_uuid,
                'creater_uuid' => $request->assignee_id,

            ]);
            // dd($request->all());
            $questCntrlObj = $this->questionsDetail;
            $apiResponse = $questCntrlObj->addUpdateQuestion($request)->getData();
            if($apiResponse->status){
                $data = $apiResponse->data;
                return $this->commonService->getSuccessResponse('Course Saved Successfully', $data);
            }
            return json_encode($apiResponse);
            // return redirect()->back();
        }
        $request->merge([
            'quiz_uuid'=> $uuid,
            'body' => $request->add_question_textarea,
            'correct_answer' => $request->add_answer_textarea
        ]);

            $questCntrlObj = $this->questionsDetail;
            $apiResponse = $questCntrlObj->addUpdateQuestion($request)->getData();

            // dd($apiResponse->data);
            if($apiResponse->status){
                $data = $apiResponse->data;
                return $this->commonService->getSuccessResponse('Course Saved Successfully', $data);
            }
            return json_encode($apiResponse);
        // return redirect()->back();
    }

    /**
     * Delete Test Question .
     * @return Renderable
     */
    public function deleteTestQuestion( Request $request)
    {
        $request->merge(['question_uuid' => $request->test_question_uuid]);
      $testQuestobj = $this->questionsDetail;
      $apiResponse = $testQuestobj->deleteQuestion($request)->getData();
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
        // dd(123);
        // dd($request->all());
        // if($request->answer_test_question)
        // {
        //     $request->merge([
        //         'question_uuid' => $request->answer_test_question,
        //         'body' =>$request->add_question_textarea,
        //         'correct_answer' => $request->add_answer_textarea,
        //         'quiz_uuid' => $request->quiz_test_uuid,
        //         'creater_uuid' => $request->assignee_id,

        //     ]);
        //     // dd($request->all());
        //     $questCntrlObj = $this->questionsDetail;
        //     $apiResponse = $questCntrlObj->addUpdateQuestion($request)->getData();
        //     if($apiResponse->status){
        //         $data = $apiResponse->data;
        //         return $this->commonService->getSuccessResponse('Course Saved Successfully', $data);
        //     }
        //     return json_encode($apiResponse);
        //     // return redirect()->back();
        // }
        // dd($request->all());
            // $answer[] = '"body":'.'"'.$request->boolean_option_1.'","is_correct": "'. $request->boolean_answer.'","answer_uuid":'. '""';
            // $answer[] = '"body":'.'"'.$request->boolean_option_2.'","is_correct": "'. $request->boolean_answer.'","answer_uuid":'. '""';
            // //  dd($answer);
            // $answers = json_encode($answer);
            // dd($answers);

        $request->merge([
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
