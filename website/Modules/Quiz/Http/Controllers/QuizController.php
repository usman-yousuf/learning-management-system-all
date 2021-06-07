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

        if($data-> type == "test")
        {
            $questCntrlObj = $this->questionsDetail;
        $getAllQuestObj  = $questCntrlObj->getQuestions($request)->getData();
        $data_questions =$getAllQuestObj->data;
        $data_questions =$data_questions->questions;
        // dd($data_questions);
          return view('quiz::quizez.test_question', ['data' => $data, 'data_questions' =>$data_questions]);
        }
         
        if($data->type == "mcqs")
        {
            return view('quiz::quizez.mcqs');
        }

        if($data->type == "boolean") {
            return view('quiz::quizez.boolean');
        }
        // $request->merge([
        //     'quiz_uuid'=> $uuid,
        //     'body' => $request->add_question_textarea,
        //     'correct_answer' => $request->add_answer_textarea
        // ]);
        // $testCntrlObj = $this->quizCtrlObj;
        // $apiResponse = $testCntrlObj->getQuiz($request)->getData();
        // $data = $apiResponse->data;

        // $questCntrlObj = $this->questionsDetail;
        // $getAllQuestObj  = $questCntrlObj->getQuestions($request)->getData();
        // $data_questions =$getAllQuestObj->data;
        // // $data_questions =$data_questions->questions;


        // if($request->getMethod() =='GET'){
        //     $data->requestFilters = [];
        //     if(!$apiResponse->status){
        //         return abort(500, 'Smething went wrong');
        //     }
        // }
        // else{
        //     $request->merge([
        //         'quiz_uuid'=> $uuid,
        //         'body' => $request->add_question_textarea,
        //         'correct_answer' => $request->add_answer_textarea
        //     ]);
        //     $questCntrlObj = $this->questionsDetail;
        //     $apiResponse = $questCntrlObj->addUpdateQuestion($request)->getData();
            
        //     $data->requestFilters = $request->all();
        // }
        
        // return view('quiz::quizez.test_question', ['data' => $data, 'data_questions' => $data_questions]);
    }


     /**
     * Add Test Add Question 
     * @return Renderable
     */
    public function addTestQuestion($uuid, Request $request)
    {
        // dd(123);
        
        $request->merge([
            'quiz_uuid'=> $uuid,
            'body' => $request->add_question_textarea,
            'correct_answer' => $request->add_answer_textarea
        ]);
            
            $questCntrlObj = $this->questionsDetail;
            $apiResponse = $questCntrlObj->addUpdateQuestion($request)->getData();
            
            // dd($apiResponse->data);
        return redirect()->back();
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
