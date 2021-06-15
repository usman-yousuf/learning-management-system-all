<?php

namespace Modules\Student\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Common\Services\CommonService;
use Modules\Student\Http\Controllers\API\StudentCourseEnrollmentController;

class StudentController extends Controller
{

    private $commonService;
    private $reportCtrlObj;

    public function __construct(
        CommonService $commonService,
        StudentCourseEnrollmentController $studentCtrlObj
    ) {
        $this->commonService = $commonService;
        $this->studentCntrlObj = $studentCtrlObj;
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
        dd($request->all());
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
        $request->merge([
            'course_uuid' => 'e60ee954-1385-470b-9524-adedcd8ef7cd',
            // 'course_uuid' => 'b68894d4-0f53-47f5-8b5e-cf96a2417714', // online course
            // 'course_uuid' => '607f6879-4995-4d3b-be13-3684098cb2c8', // video course
            'student_uuid' => 'c17093f4-e172-4c88-bc86-ed45d7bb3609',
            'slot_uuid' => '8f26986f-1565-402a-8ba1-f7f88691396f',
            'joining_date' => '2021-06-11 18:26:59',

            'amount' => '1500',
            'stripe_trans_id' => 'abc123xyz',
            'stripe_trans_status' => 'successfull',
            'card_uuid' => 'card_uuid',
            'payment_method' => 'stripe',
            'status' => 'successfull',
        ]);

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
    public function index()
    {
        return view('student::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('student::create');
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
