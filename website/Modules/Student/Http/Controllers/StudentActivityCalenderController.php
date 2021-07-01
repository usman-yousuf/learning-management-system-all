<?php

namespace Modules\Student\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Common\Services\CommonService;
use Modules\Quiz\Entities\QuizAttemptStats;
use Modules\Quiz\Http\Controllers\API\QuizController;
use Modules\Student\Http\Controllers\API\StudentCourseEnrollmentController;
use Modules\User\Services\ProfileService;


class StudentActivityCalenderController extends Controller
{

    private $commonService;
    private $profileService;
    private $studentEnrollementService;
    private $quizCtrlObj;


    public function __construct(
        CommonService $commonService,
        ProfileService $profileService,
        StudentCourseEnrollmentController $studentEnrollementService,
        QuizController $quizCtrlObj

    )
    {
        $this->commonService = $commonService;
        $this->profileService = $profileService;
        $this->studentEnrollementService = $studentEnrollementService;
        $this->quizCtrlObj = $quizCtrlObj;
    }

    // public function index(Request $request)
    // {
    //     $request->merge(['profile_uuid'=> $request->user()->profile->uuid ]);

    //     // dd($request->all());
    //     $apiResponse = $this->profileService->checkStudent($request);
    //     if(!$apiResponse['status'])
    //     {
    //         return view('common::errors.403');
    //     }
    //     $student = $apiResponse['data'];
    //     $student_id = $student->id;

    //     // attempted quiz details
    //     $std_quiz_attempt = QuizAttemptStats::where('student_id', $student_id)->with('course','quiz')->first();


    //     // attempt new quiz
    //     $request->merge(['student_uuid' => $request->user()->profile->uuid]);
    //     $result = $this->studentEnrollementService->getStudentCourses($request)->getData();
    //     // dd($result->data->enrollment[0]->course->uuid);
    //     if(!$result->status)
    //     {
    //         return view('common::errors.403');
    //     }
    //     // $course_uuid = $result->data->enrollment[0]->course->uuid;
    //     $request->merge(['course_uuid' =>$result->data->enrollment[0]->course->uuid]);
    //     $course_detail = $result->data->enrollment[0]->course;

    //     $result = $this->quizCtrlObj->getQuizzes($request)->getData();
    //     // dd($result->data);
    //     if (!$result->status) {
    //         return view('common::errors.403');
    //     }

    //     $new_quiz_attempt = $result->data;



    //     return view('student::calender.index' ,
    //     [
    //         'attempted_quiz' => $std_quiz_attempt,
    //         'new_quiz_attempt' => $new_quiz_attempt,
    //         'course_detail' => $course_detail

    //     ]);
    // }

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
