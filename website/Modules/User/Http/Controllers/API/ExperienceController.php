<?php

namespace Modules\User\Http\Controllers\API;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Common\Services\CommonService;
use Modules\User\Services\ExperienceService;
use Modules\User\Services\ProfileService;

class ExperienceController extends Controller
{
    private $commonService;
    private $experienceService;
    private $profileService;

    public function __construct(CommonService $commonService, ExperienceService $experienceService, ProfileService $profileService)
    {
        $this->commonService = $commonService;
        $this->experienceService = $experienceService;
        $this->profileService = $profileService;
    }

    /**
     * Get a Single Experience based on uuid
     *
     * @param Request $request
     * @return void
     */
    public function getExperience(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'experience_uuid' => 'required',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and fetch an Experience
        $result = $this->experienceService->checkExperience($request);
        if(!$result['status']){
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $experience = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $experience);
    }

    /**
     * Delete an Experience by UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteExperience(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'experience_uuid' => 'required|exists:experiences,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and delete an Experience
        $result = $this->experienceService->deleteExperience($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $experience = $result['data'];

        return $this->commonService->getSuccessResponse('Record Deleted Successfully', []);
    }

    /**
     * Get Experiences based on given filters
     *
     * @param Request $request
     * @return void
     */
    public function getExperiences(Request $request)
    {
        if(isset($request->profile_uuid) && ('' != $request->profile_uuid)){
            $result = $this->profileService->getProfile($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $profile = $result['data'];
            $request->merge(['profile_id' => $profile->id]);
        }

        $result = $this->experienceService->getExperiences($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $experiences = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $experiences);
    }

    /**
     * Add|Update an Experience
     *
     * @param Request $request
     * @return void
     */
    public function updateExperience(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'experience_uuid' => 'exists:experiences,uuid',
            'profile_uuid' => 'exists:profiles,uuid',
            'job_exp' => 'string',
            'teaching_exp' => 'required|string',
            'image' => 'string',
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

        // find experience by uuid if given
        $experience_id = null;
        if(isset($request->experience_uuid) && ('' != $request->experience_uuid)){
            $result = $this->experienceService->checkExperience($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $experience = $result['data'];
            $experience_id = $experience->id;
        }

        $result = $this->experienceService->addUpdateExperience($request, $experience_id);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $experience = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $experience);
    }
}
