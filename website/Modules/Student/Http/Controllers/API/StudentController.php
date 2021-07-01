<?php

namespace Modules\Student\Http\Controllers\API;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Common\Services\CommonService;
use Modules\User\Services\ProfileService;
use Modules\User\Services\UserService;

class StudentController extends Controller
{
    private $commonService;
    private $userService;
    private $profileService;

    public function __construct(CommonService $commonService, UserService $userService, ProfileService $profileService)
    {
        $this->commonService = $commonService;
        $this->userService = $userService;
        $this->profileService = $profileService;
    }

    /**
     * Get a Single Student based on uuid
     *
     * @param Request $request
     * @return void
     */
    public function getStudent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_uuid' => 'required',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and fetch Student
        $request->merge(['profile_uuid' => $request->student_uuid]);
        $result = $this->profileService->checkStudent($request);
        if(!$result['status']){
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $courseReview = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $courseReview);
    }

    /**
     * Delete Student  by UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteStudent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_uuid' => 'required|exists:profiles,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and fetch Student
        if(isset($request->student_uuid) && ('' !=$request->student_uuid))
        {
            $request->merge(['profile_uuid' => $request->student_uuid]);
            $result = $this->profileService->checkStudent($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
        }

        // validate and delete Student
        $result = $this->profileService->deleteProfile($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $student = $result['data'];
        return $this->commonService->getSuccessResponse('Record Deleted Successfully', []);
    }

    /**
     * Get Students based on given filters
     *
     * @param Request $request
     * @return void
     */
    public function getStudents(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_uuid' => 'exists:users,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        //user_uuid
        if(isset($request->user_uuid) && ('' != $request->user_uuid)){
        $result = $this->userService->getUser($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $user = $result['data'];
        $request->merge(['user_id' => $user->id]);
        }

        $result = $this->profileService->listProfiles($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $student = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $student);
    }

    /**
     * Add|Update Student
     *
     * @param Request $request
     *
     * @return void
     */
    public function updateStudent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_uuid' => 'exists:profiles,uuid',
            'user_uuid' => 'exists:users,uuid',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'gender' => 'required|string',
            'profile_type' => 'required|string',
            'dob' => 'required|date',
            'interests' => 'required|string',
            'phone_code' => 'required|numeric',
            'phone_code' => 'required|numeric',
            'status' => 'required|string',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        //user_uuid
        if(isset($request->user_uuid) && ('' != $request->user_uuid)){
            $result = $this->userService->getUser($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $user = $result['data'];
            $request->merge(['user_id' => $user->id]);
        }

        // find Student by uuid if given
        $profile_id = null;
        if(isset($request->student_uuid) && ('' != $request->student_uuid)){
            $request->merge(['profile_uuid' => $request->student_uuid]);
            $result = $this->profileService->getProfile($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $profile = $result['data'];
            $profile_id = $profile->id;
        }

        $result = $this->profileService->addUpdateProfile($request, $profile_id);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $review = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $review);
    }
}
