<?php

namespace Modules\User\Http\Controllers\API;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Common\Services\CommonService;
use Modules\User\Services\AddressService;
use Modules\User\Services\EducationService;
use Modules\User\Services\ProfileService;

class EducationController extends Controller
{
    private $commonService;
    private $addressService;
    private $profileService;
    private $educationService;

    public function __construct(CommonService $commonService, AddressService $addressService, ProfileService $profileService ,EducationService $educationService)
    {
        $this->commonService = $commonService;
        $this->addressService = $addressService;
        $this->profileService = $profileService;
        $this->educationService = $educationService;
    }

    /**
     * Get a Single Address based on uuid
     *
     * @param Request $request
     * @return void
     */
    public function getEducation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'education_uuid' => 'required',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }
        //  dd($request->all());
        // validate and fetch an Education
        $result = $this->educationService->checkEducation($request);
        if(!$result['status']){
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $education = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $education);
    }

    /**
     * Delete an Education by UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteEducation(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'education_uuid' => 'required|exists:educations,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and delete an Education
        $result = $this->educationService->deleteEducation($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $education = $result['data'];

        return $this->commonService->getSuccessResponse('Record Deleted Successfully', []);
    }

    /**
     * Get Education based on given filters
     *
     * @param Request $request
     * @return void
     */
    public function getEducations(Request $request)
    {
        if(isset($request->profile_uuid) && ('' != $request->profile_uuid)){
            $result = $this->profileService->getProfile($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $profile = $result['data'];
            $request->merge(['profile_id' => $profile->id]);
        }

        $result = $this->educationService->getEducations($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $addresses = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $addresses);
    }

    /**
     * Add|Update an Address
     *
     * @param Request $request
     * @return void
     */
    public function updateEducation(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'education_uuid' => 'exists:educations,uuid',
            'profile_uuid' => 'exists:profiles,uuid',
            'title' => 'required|string',
            'completed_at' => 'required',
            'university' => 'required|string'
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // get/set profile uuid
        $uuid = $request->user()->profile->uuid;
        if(isset($request->profile_uuid) && ('' != $request->profile_uuid)){
            $uuid = $request->profile_uuid;
        }
        $request->merge(['profile_uuid' => $uuid]);

        // valiadate and fetch profile
        $result = $this->profileService->getProfile($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $profile = $result['data'];
        $request->merge(['profile_id' => $profile->id]);

        // find education by uuid if given
        $education_id = null;
        if(isset($request->education_uuid) && ('' != $request->education_uuid)){
            $result = $this->educationService->checkEducation($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $education = $result['data'];
            $education_id = $education->id;
        }

        $result = $this->educationService->addUpdateEducation($request, $education_id);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $address = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $address);
    }
}
