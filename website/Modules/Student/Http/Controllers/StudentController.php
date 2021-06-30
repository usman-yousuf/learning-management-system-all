<?php

namespace Modules\Student\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Common\Services\CommonService;
use Modules\Course\Http\Controllers\API\CourseDetailController;
use Modules\Course\Http\Controllers\API\StudentQueryController;
use Modules\Quiz\Http\Controllers\API\QuestionController;
use Modules\Quiz\Http\Controllers\API\QuizController;
use Modules\Student\Http\Controllers\API\StudentCourseEnrollmentController;
use Modules\User\Services\ProfileService;

class StudentController extends Controller
{

    private $commonService;
    private $reportCtrlObj;
    private $profileService;
    private $studentEnrollementService;
    private $quizControllerService;
    private $quizCtrlObj;
    private $courseDetail;
    private $studentQueryController;
    private $questionsDetail;




    public function __construct(
        CommonService $commonService,
        StudentCourseEnrollmentController $studentCtrlObj,
        ProfileService $profileService,
        StudentCourseEnrollmentController $studentEnrollementService,
        QuizController $quizCtrlObj,
        QuestionController $questionsDetail,
        StudentQueryController $studentQueryController

    ) {
        $this->commonService = $commonService;
        $this->studentCntrlObj = $studentCtrlObj;
        $this->profileService = $profileService;
        $this->studentEnrollementService = $studentEnrollementService;
        $this->quizCtrlObj = $quizCtrlObj;
        // $this->courseDetail = $courseDetail;
        $this->questionsDetail = $questionsDetail;
        $this->studentQueryController = $studentQueryController;

    }

    public function studentList(Request $request)
    {
        $request->merge([
            'is_date_range' => true,
            'nature' => $request->course_type
        ]);
        $stdCntrlObj = $this->studentCntrlObj;

        $apiResponse = $stdCntrlObj->getStudentCourses($request)->getData();
        $data = $apiResponse->data;
        if($request->getMethod()== 'GET')
        {
            $data->requestFilters = [];
            if(!$apiResponse->status){
                return abort(500, 'Smething went wrong');
            }
        }
        else {
            $data->requestFilters = $request->all();
        }
        // dd($data, $data->requestFilters );

        return view('student::student/listing', ['data' => $data]);
    }


    public function slotExist(Request $request)
    {
        $request->merge([
            'course_uuid'=> '46e3f741-69cf-4fa1-a7e3-b34d80b6f87b',
            'student_uuid' => '38f04384-dd00-4cb5-806f-c81e731036fd',
            'slot_uuid' => '996d9d73-52f1-4aa8-85ab-8f99579e4f18',
            'joining_date' => '2021-06-11 18:26:59',
            'status' => 'active'
        ]);
        $stdCntrlObj = $this->studentCntrlObj;
        $apiResponse = $stdCntrlObj->addUpdateStudentCourseEnroll($request)->getData();

        if ($apiResponse->status) {
            $data = $apiResponse->data;
            return $this->commonService->getSuccessResponse('Data Fetched Successfully', $data);
        }
        return json_encode($apiResponse);
    }

    /**
     * Enroll a Student
     *
     * @param Request $request
     *
     * @return void
     */
    public function enrollStudent(Request $request)
    {
        if ($request->user()->profile->profile_type == 'student') {
            $request->merge(['student_uuid' => $request->user()->profile->uuid]);
        } else {
        }

        // 'stripe_trans_id' => 'abc123xyz',
        // 'stripe_trans_status' => 'successfull',
        // 'card_uuid' => 'card_uuid',
        // 'status' => 'successfull',

        $request->merge([
            'course_uuid' => 'e60ee954-1385-470b-9524-adedcd8ef7cd',

            // 'slot_uuid' => '8f26986f-1565-402a-8ba1-f7f88691396f',

            'stripe_trans_id' => 'abc123xyz',
            'stripe_trans_status' => 'successfull',
            'card_uuid' => 'card_uuid',
            'payment_method' => 'stripe',
            'status' => 'successfull',
        ]);

        dd($request->all());


        unset($request['easypaisa_trans_id'], $request['easypaisa_trans_status']);
        $ctrlObj = $this->studentCntrlObj;
        $apiResponse = $ctrlObj->addUpdateStudentCourseEnroll($request)->getData();

        if ($apiResponse->status) {
            $data = $apiResponse->data;
            return $this->commonService->getSuccessResponse('Student Enrolled Successfully', $data);
        }
        return json_encode($apiResponse);
    }



    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function dashboard(Request $request)
    {
        // dd("student dashboard");
      // validate if request user is actually a teacher
        $request->merge([
            'profile_id' => $request->user()->profile->id,
            'profile_uuid' => $request->user()->profile->uuid
            , 'profile_interests' => explode(',', $request->user()->profile->interests)
        ]);

        $result = $this->profileService->checkStudent($request);
        if (!$result['status']) {
            return view('common::errors.403');
        }
        $currentProfile = $result['data'];

        // enrolled and suggested courses for dashboard
        $result = $this->studentEnrollementService->getStudentEnrolledCourses($request)->getData();
        $suggestionResult = $this->studentEnrollementService->getSuggestedCourses($request)->getData();

        if (!$result->status && !$suggestionResult->status) {
            return view('common::errors.500');
        }
        $enrolled_courses = $result->data;
        $suggestion_courses = $suggestionResult->data;
        return view('student::dashboard', [
            'enrolled_courses' => $enrolled_courses
            , 'suggested_courses' => $suggestion_courses
        ]);
    }


    /**
     * Course
     *
     *
     */
    public function courseDetail(Request $request)
    {
        // dd($request->user()->profile->uuid);
        //get course uuid against the student enroll
        $request->merge(['student_uuid' => $request->user()->profile->uuid]);
        $result = $this->studentEnrollementService->getStudentCourses($request)->getData();
        // dd($result->data->enrollment[0]->course->uuid);
        if(!$result->status)
        {
            return view('common::errors.403');
        }
        // $course_uuid = $result->data->enrollment[0]->course->uuid;
        $request->merge(['course_uuid' =>$result->data->enrollment[0]->course->uuid]);
        $course_detail = $result->data->enrollment[0]->course;

        $result = $this->quizCtrlObj->getQuizzes($request)->getData();
        // dd($result->data);
        if (!$result->status) {
            return view('common::errors.403');
        }

        $data = $result->data;
        // dd($data);
        return view('student::courseDetail',['course_detail' => $course_detail ,'data' => $data]);

    }

    public function getQuiz($uuid, Request $request)
    {
        // dd(123);
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
        // dd($quiz);

        // detremine the view to show
        $viewName = 'test_question';
        if($quiz->type == 'test'){
            // dd("123");
            $viewName = "student::studentQuiz.test_question";
        }
        else if($quiz->type == 'mcqs'){
            $viewName = "student::studentQuiz.mcqs";
        }
        else if($quiz->type == 'boolean'){
            $viewName = "student::studentQuiz.mcqs";
        }

        return view($viewName, ['data' => $quiz, 'data_questions' => $quiz->questions]);
    }


     /**
     * Add Boolean (True , false) Question
     * @return Renderable
     */
    public function addStudentQuizAnswer($uuid, Request $request)
    {
        $request->merge([
            'question_uuid' => $request->question_uuid,
            'quiz_uuid'=> $uuid,
            'student_uuid' =>$request->user()->profile->uuid, // student_uuid
        ]);

        $questCntrlObj = $this->questionsDetail;

        $apiResponse = $questCntrlObj->addStudentQuizAnswerBulkChoice($request)->getData();

        // dd($apiResponse->data);
        if($apiResponse->status){
            $data = $apiResponse->data;
            return $this->commonService->getSuccessResponse('Student submitted Quiz Successfully', $data);
        }
        return json_encode($apiResponse);
        // return redirect()->back();
    }

    /**
     * Add  Question against the course
     * @return Renderable
     */
    public function addQuestion($uuid, Request $request)
    {
        // dd($request->all(),$uuid);
        $request->merge([
            'course_uuid' => $uuid,
            'student_uuid' => $request->user()->profile->uuid,
            'body' =>  $request->body
        ]);

        $apiResponse = $this->studentQueryController->updateStudentQuery($request)->getData();
        // dd($apiResponse->data);
        if($apiResponse->data)
        {
            return $this->commonService->getSuccessResponse('Query sent successfully', $apiResponse);
        }
        return json_encode($apiResponse);

    }
    /**
     * Undocumented function
     *
     * @param [type] $id
     *
     * @return Renderable
     */
    public function show($id)
    {
        return view('student::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('student::edit');
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
