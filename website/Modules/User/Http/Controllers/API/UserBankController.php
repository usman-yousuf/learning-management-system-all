<?php

namespace Modules\User\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Common\Services\CommonService;
use Modules\User\Services\ProfileService;
use Modules\User\Services\UserBankService;


class UserBankController extends Controller
{
    private $commonService;
    private $profileService;
    private $userbankService;

    public function __construct(CommonService $commonService, ProfileService $profileService , UserBankService $userbankService )
    {
        $this->commonService = $commonService;
        $this->profileService = $profileService;
        $this->userbankService = $userbankService;
    }

    /**
     * Get a Single User bank based on uuid
     *
     * @param Request $request
     * @return void
     */
    public function getUserBank(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_bank_uuid' => 'required',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }
        //  dd($request->all());
        // validate and fetch an Education
        $result = $this->userbankService->checkUserBank($request);
        if(!$result['status']){
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $userbank = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $userbank);
    }

    /**
     * Delete an User Bank by UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteUserBank(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_bank_uuid' => 'required|exists:user_banks,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and delete an User Bank
        $result = $this->userbankService->deleteUserBank($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $userbank = $result['data'];

        return $this->commonService->getSuccessResponse('Record Deleted Successfully', []);
    }

    /**
     * Get User Bank based on given filters
     *
     * @param Request $request
     * @return void
     */
    public function getUserBanks(Request $request)
    {
        if(isset($request->profile_uuid) && ('' != $request->profile_uuid)){
            $result = $this->profileService->checkProfile($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $profile = $result['data'];
            $request->merge(['profile_id' => $profile->id]);
        }

        $result = $this->userbankService->getUserBanks($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $userbank = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $userbank);
    }

    /**
     * Add|Update an User Bank
     *
     * @param Request $request
     * @return void
     */
    public function updateUserBank(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'user_bank_uuid' => 'exists:user_banks,uuid',
            'profile_uuid' => 'exists:profiles,uuid',
            'account_title' => 'required|string',
            'iban' => 'required|string|min:10|unique:user_banks,uuid,'.$request->user_bank_uuid,
            'branch_name' => 'required|string',
            'bank_name' => 'required|string',
            'branch_code' => 'required|min:4|max:6',
            'swift_code' => 'required|string|min:5',
            'account_number' => 'required|min:10|unique:user_banks,uuid,'.$request->user_bank_uuid
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

        // find user bank by uuid if given
        $user_bank_id = null;
        if(isset($request->user_bank_uuid) && ('' != $request->user_bank_uuid)){
            $result = $this->userbankService->checkUserBank($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $userbank = $result['data'];
            $user_bank_id = $userbank->id;
        }

        $result = $this->userbankService->addUpdateUserBank($request, $user_bank_id);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $userbank = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $userbank);
    }
}
