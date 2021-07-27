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
use Modules\Student\Http\Controllers\API\StudentController as APIStudentController;
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
    private $questionCtrlObj;
    private $reviewController;
    private $studentServiceController;

    public function __construct(
        CommonService $commonService,
        StudentCourseEnrollmentController $studentCtrlObj,
        ProfileService $profileService,
        CourseDetailController $courseDetail,
        QuizController $quizCtrlObj,
        QuestionController $questionCtrlObj,
        StudentQueryController $studentQueryController,
        ReviewController $reviewController,
        APIStudentController $studentServiceController

    ) {
        $this->commonService = $commonService;
        $this->studentCtrlObj = $studentCtrlObj;
        $this->profileService = $profileService;
        $this->quizCtrlObj = $quizCtrlObj;
        $this->courseDetail = $courseDetail;
        $this->questionCtrlObj = $questionCtrlObj;
        $this->studentQueryController = $studentQueryController;
        $this->reviewController = $reviewController;
        $this->studentServiceController = $studentServiceController;
    }

    public function studentList(Request $request)
    {
        $request->merge([
            'is_date_range' => true,
            'nature' => $request->course_type,
            'unique_only' => true
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
        // return json_encode($apiResponse);
        return $this->commonService->getProcessingErrorResponse($apiResponse->message, $apiResponse->data, $apiResponse->responseCode, $apiResponse->exceptionCode);
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
        // return json_encode($apiResponse);
        return $this->commonService->getProcessingErrorResponse($apiResponse->message, $apiResponse->data, $apiResponse->responseCode, $apiResponse->exceptionCode);
    }



    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function dashboard(Request $request)
    {
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

    /**
     * get Quiz by UUID
     *
     * @param String $uuid
     * @param Request $request
     *
     * @return void
     */
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
        // if($quiz->is_attempted){
        //     return view('common::errors.403', [
        //         'message' => 'You cannot attempt a Quiz again'
        //         , 'backUrl' => route('course.view',['uuid' => $quiz->course->uuid])
        //     ]);
        // }

        // detremine the view to show
        if ($quiz->is_attempted){
            $viewName = 'student::studentQuiz.pending_processing';
            // 'student::partials.quiz_result_content';
        }
        else{
            $viewName = ($quiz->type == 'test')? 'student::studentQuiz.test' : 'student::studentQuiz.mcqs';
        }

        return view($viewName, ['data' => $quiz, 'data_questions' => $quiz->questions]);
    }


    /**
     * Attempt a Quiz [Student ONLY]
     *
     * @param String $uuid
     * @param Request $request
     *
     * @return void
     */
    public function attemptQuiz($uuid, Request $request)
    {
        $request->merge([
            'quiz_uuid'=> $uuid,
            'student_uuid' =>$request->user()->profile->uuid, // student_uuid
        ]);

        $ctrlObj = $this->questionCtrlObj;

        $apiResponse = $ctrlObj->attemptQuiz($request)->getData();

        // dd($apiResponse->data);
        if($apiResponse->status){
            $data = $apiResponse->data;
            return $this->commonService->getSuccessResponse('Quiz Attempt Submitted Successfully', $data);
        }
        // return json_encode($apiResponse);
        return $this->commonService->getProcessingErrorResponse($apiResponse->message, $apiResponse->data, $apiResponse->responseCode, $apiResponse->exceptionCode);
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
        // return json_encode($apiResponse);
        return $this->commonService->getProcessingErrorResponse($apiResponse->message, $apiResponse->data, $apiResponse->responseCode, $apiResponse->exceptionCode);
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

        if(null == $request->review_uuid){
            unset($request['review_uuid']);
        }

        $add_reviews = $this->reviewController;
        $apiResponse = $add_reviews->updateReview($request)->getData();

        if ($apiResponse->status) {
            $data = $apiResponse->data;
            return $this->commonService->getSuccessResponse('Reviews Saved Successfully', $data);
        }
        // return json_encode($apiResponse);
        return $this->commonService->getProcessingErrorResponse($apiResponse->message, $apiResponse->data, $apiResponse->responseCode, $apiResponse->exceptionCode);
    }

    /**
     * delete a Review
     *
     * @param Request $request
     * @return void
     */
    public function deleteMyReview(Request $request)
    {
        $ctrlObj = $this->reviewController;
        $apiResponse = $ctrlObj->deleteReview($request)->getData();

        if ($apiResponse->status) {
            $data = $apiResponse->data;
            return $this->commonService->getSuccessResponse('Review Deleted Successfully', $data);
        }
        // return json_encode($apiResponse);
        return $this->commonService->getProcessingErrorResponse($apiResponse->message, $apiResponse->data, $apiResponse->responseCode, $apiResponse->exceptionCode);
    }


    /**
     * Search courses based on given keywords
     *
     * @param Request $request
     *
     * @return void
     */
    public function searchDashboard(Request $request)
    {
        $courses = $this->courseDetail;
        $apiResponse = $courses->getCourseDetails($request)->getData();
        if ($apiResponse->status) {
            // dd($apiResponse->data, $request->all());
            $data = $apiResponse->data;
            $request->merge(['keywords' => $request->keywords ?? '']);
            $data->requestForm = $request->all();
            if($request->ajax()){ // its an ajax callback
                return $this->commonService->getSuccessResponse('Courses Searched Successfully', $data);
            }
            else{
                return view('student::search', [
                    'courses' => $data,
                    'requestForm' => $data->requestForm,
                ]);
            }
        }
        if ($request->ajax()) { // its an ajax callback
            // return json_encode($apiResponse);
            return $this->commonService->getProcessingErrorResponse($apiResponse->message, $apiResponse->data, $apiResponse->responseCode, $apiResponse->exceptionCode);
        }
        else{
            return view('common::errors.500');
        }
    }


    public function uploadAssignment(Request $request)
    {
        $request->merge([
            'student_uuid' =>  $request->user()->profile->uuid,
            'media' => $request->upload_assignment_image
        ]);
        // dd($request->all());

        $apiResponse = $this->studentServiceController->submitStudentAssignment($request)->getData();
        if($apiResponse->status)
        {
            $data = $apiResponse->data;
            return $this->commonService->getSuccessResponse('Student uploaded Assignment Successfully', $data);
        }
        // return json_encode($apiResponse);
        return $this->commonService->getProcessingErrorResponse($apiResponse->message, $apiResponse->data, $apiResponse->responseCode, $apiResponse->exceptionCode);

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
