<?php

namespace Modules\Common\Http\Controllers\API;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\AuthAll\Http\Controllers\API\AuthApiController;
use Modules\Common\Services\CommonService;
use Modules\Course\Services\CourseCategoryService;
use Modules\Course\Services\CourseDetailService;
use Modules\Payment\Services\PaymentHistoryService;

class ReportController extends Controller
{
    private $commonService;
    private $courseCategoryService;
    private $courseDetailService;
    private $paymentHistoryService;

    public function __construct(CommonService $commonService, CourseDetailService $courseDetailService, CourseCategoryService $courseCategoryService, PaymentHistoryService $paymentHistoryService)
    {
        // $this->statsService = new StatsService();
        $this->commonService = $commonService;
        $this->courseDetailService = $courseDetailService;
        $this->courseCategoryService = $courseCategoryService;
        $this->paymentHistoryService = $paymentHistoryService;
    }

    /**
     * Student Course Report
     *
     * @param Request $request
     * @return void
     */
    public function getStudentCourseReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_category_uuid' => 'exists:course_categories,uuid',
            // 'course_title' => 'string',
            // 'students_count' => 'string',
            // 'no_of_students_enrolled' => 'integer',
            // 'no_of_paid_students_count' => 'integer',
            // 'is_course_free' => 'string'
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // course category id
        $course_category_id = null;
        if(isset($request->course_category_uuid) && ('' != $request->course_category_uuid))
        {
            $result = $this->courseCategoryService->checkCourseCateogry($request);
            if(!$result['status'])
            {
              return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
            }
            $course_category = $result['data'];
            $course_category_id = $course_category->id;
        }

        // get specific columns
        $specific_columns = array('title', 'students_count' , 'uuid AS course_uuid' , 'free_students_count', 'paid_students_count','is_course_free' );
        // merge attributes
        $request->merge([
            'free_students_count' => $request->no_of_students_enrolled,
            'paid_students_count' => $request->no_of_paid_students_count,
            'specific_columns' => $specific_columns,
        ]);

        // get listing through course detail
        $result = $this->courseDetailService->getCourses($request);
        if(!$result['status']){
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $courseDetail = $result['data'];
        // dd($courseDetail);

        // check if the current loged In user is admin or teacher;
        if(($request->user()->profile->profile_type == 'teacher') || $request->user()->profile->profile_type == 'admin')
        {
            return $this->commonService->getSuccessResponse('Success', $courseDetail);
        }
        return $this->commonService->getNotAuthorizedResponse();



    }

    /**
     *  Sales Report
     * @param Request $request
     * @return void
     */
    public function getSalesReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_uuid' => 'exists:courses,uuid',
            // 'course_title' => 'string',
            'student_name' => 'string',
            'amount_paid' => 'numeric',
            'transaction_id' => 'string',
            // 'IS_date_range' => 'string'
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // get payments

        $request->merge([
            'get_all' => false,
        ]);
            // dd($request->all());
        if ($request->user()->profile_type == 'admin') {
            $request->get_all = true;
        }
        else
        {
            if($request->user()->profile_type != 'teacher'){
                return $this->commonService->getNotAuthorizedResponse();
            }
        }

        $result = $this->paymentHistoryService->getPaymentHistories($request);
        if(!$result['status'])
        {
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $payments = $result['data'];


        // check if the current loged In user is admin or teacher;
        // if(($request->user()->profile->profile_type == 'teacher') || $request->user()->profile->profile_type == 'admin')
        // {
        //     return $this->commonService->getSuccessResponse('Success', $payments);
        // }
        //     return $this->commonService->getNotAuthorizedResponse();

            return $this->commonService->getSuccessResponse('Success', $payments);

    }



}
