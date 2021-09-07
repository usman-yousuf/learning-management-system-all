<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Common\Services\CommonService;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;



    /**
     * return landing page of application
     *
     * @return void
     */
    public function welcome()
    {
        $courses = getAllApprovedCourses();
        $teachers = getAllApprovedTeachers();
        return view('welcome', ['courses' => $courses, 'teachers' => $teachers]);
    }

    /**
     * Contact us page
     *
     * @return void
     */
    public function contactUs(Request $request)
    {
        if ($request->getMethod() == 'GET') {
            return view('contact_us', []);
        }
        else{
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:3',
                'email' => 'required|email',
                'subject' => 'required|min:3',
                'message' => 'required|min:5',
            ]);

            $commonService = new CommonService();
            if ($validator->fails()) {
                $data['validation_error'] = $validator->getMessageBag();
                return $commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
            }

            // return $commonService->getSuccessResponse('Query Submiited Successfully and not atually sent');
            $result = $commonService->sendContactUsEmail($request->name, $request->email, $request->subject, $request->message);
            if (!$result['status']) {
                return $commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }

            return $commonService->getSuccessResponse('Query Sent Successfully');
        }
    }

    /**
     * our teachers page
     *
     * @return void
     */
    public function ourTeachers(Request $request)
    {
        return view('public_teachers', []);
    }

    /**
     * our courses page
     *
     * @return void
     */
    public function ourCourses(Request $request)
    {
        $courses = getAllApprovedCourses();
        return view('public_courses', ['courses' => $courses]);
    }
}

