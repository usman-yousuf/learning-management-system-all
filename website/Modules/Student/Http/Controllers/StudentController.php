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
use Modules\Student\Http\Controllers\API\ReviewController;
use Modules\Student\Http\Controllers\API\StudentCourseEnrollmentController;
use Modules\User\Services\ProfileService;

class StudentController extends Controller
{

    private $commonService;
    private $reportCtrlObj;
    private $profileService;
    private $studentCtrlObj;
    private $quizControllerService;
    private $quizCtrlObj;
    private $courseDetail;
    private $studentQueryController;
    private $questionsDetail;
    private $reviewController;




    public function __construct(
        CommonService $commonService,
        StudentCourseEnrollmentController $studentCtrlObj,
        ProfileService $profileService,
        CourseDetailController $courseDetail,
        QuizController $quizCtrlObj,
        QuestionController $questionsDetail,
        StudentQueryController $studentQueryController,
        ReviewController $reviewController

    ) {
        $this->commonService = $commonService;
        $this->studentCtrlObj = $studentCtrlObj;
        $this->profileService = $profileService;
        $this->quizCtrlObj = $quizCtrlObj;
        $this->courseDetail = $courseDetail;
        $this->questionsDetail = $questionsDetail;
        $this->studentQueryController = $studentQueryController;
        $this->reviewController = $reviewController;
    }

    public function studentList(Request $request)
    {
        $request->merge([
            'is_date_range' => true,
            'nature' => $request->course_type
        ]);
        $studentCtrlObj = $this->studentCtrlObj;

        $apiResponse = $studentCtrlObj->getStudentCourses($request)->getData();
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
        $studentCtrlObj = $this->studentCtrlObj;
        $apiResponse = $studentCtrlObj->addUpdateStudentCourseEnroll($request)->getData();

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

        $request->merge([
            // 'course_uuid' => 'e60ee954-1385-470b-9524-adedcd8ef7cd',

            // 'slot_uuid' => '8f26986f-1565-402a-8ba1-f7f88691396f',
            'joining_date' => $request->joining_date . ' 00:00:00',

            'stripe_trans_id' => 'abc123xyz',
            'stripe_trans_status' => 'successfull',
            'card_uuid' => 'card_uuid',

            'easypaisa_trans_id' => 'abc123xyz',
            'easypaisa_trans_status' => 'successfull',
            'status' => 'successfull',
        ]);

        if($request->is_course_free){
            $request->merge([
                'payment_method' => 'free',
                'status' => 'successfull'
            ]);
            unset(
                $request['stripe_trans_id'],
                $request['stripe_trans_status'],
                $request['card_uuid'],
                $request['easypaisa_trans_id'],
                $request['easypaisa_trans_status'],
            );
        }
        else{
            if($request->payment_method == 'stripe'){

                // do stripe processing
                $request->merge([
                    'status' => 'successfull'
                ]);
                unset(
                    $request['easypaisa_trans_id'],
                    $request['easypaisa_trans_status'],
                );
            }
            else{
                // do easypaisa processing
                $request->merge([
                    'status' => 'successfull'
                ]);
                unset(
                    $request['stripe_trans_id'],
                    $request['stripe_trans_status'],
                    $request['card_uuid'],
                );
            }
        }
        // dd($request->all());
        $ctrlObj = $this->studentCtrlObj;
        $apiResponse = $ctrlObj->addUpdateStudentCourseEnroll($request)->getData();

        if ($apiResponse->status) {
            $data = $apiResponse->data;
            return $this->commonService->getSuccessResponse('Student Enrolled Successfully', $data);
        }
        return json_encode($apiResponse);
    }



    /**
     * Display a listing of the resource.
     *
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
        $result = $this->studentCtrlObj->getStudentEnrolledCourses($request)->getData();
        $suggestionResult = $this->studentCtrlObj->getSuggestedCourses($request)->getData();

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
     * Add Question against the course
     *
     * @param String $uuid UUID of course
     * @param Request $request
     *
     * @return Array[][] $jsonArray
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
     * Add Reviews against the course
     *
     * @param Request $request
     *
     * @return Array[][] $jsonArray
     */
    public function addComment(Request $request)
    {
        $request->merge([
            'student_uuid' =>  $request->user()->profile->uuid,
            'body' => $request->message_body,
        ]);

        $add_reviews = $this->reviewController;
        $apiResponse = $add_reviews->updateReview($request)->getData();

        if ($apiResponse->status) {
            $data = $apiResponse->data;
            return $this->commonService->getSuccessResponse('Reviews Added Successfully', $data);
        }
        return json_encode($apiResponse);
    }


    public function searchResult(Request $request)
    {
        // dd(133);
        $courses = $this->courseDetail;
        $apiResponse  = $courses->getCourseDetails($request)->getData();

        if ($apiResponse->status) {
            $data = $apiResponse->data;
            return $this->commonService->getSuccessResponse('Course Searched Successfully', $data);
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
