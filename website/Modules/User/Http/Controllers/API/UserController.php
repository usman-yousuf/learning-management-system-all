<?php

namespace Modules\User\Http\Controllers\API;

use App\Http\Controllers\Controller;
// use App\Services\AddressService;
// use App\Services\CategoryService;
// use App\Services\CommonService;
// use App\Services\HealthMatrixService;
// use App\Services\InsuranceService;
// use App\Services\LifeStyleService;
// use App\Services\ProfileService;
// use App\Services\UploadedMediaService;
// use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Modules\Common\Services\CommonService;
use Modules\User\Services\ProfileService;
use Modules\User\Services\UserService;

class UserController extends Controller
{
    public $commonService;
    public $userService;
    // public $categoryService;
    public $profileService;
    // public $addressService;
    // public $healthService;
    // public $lifeStyleService;
    // public $insuranceService;
    // public $uploadMediasService;

    public function __construct(CommonService $commonService, UserService $userService, ProfileService $profileService)
    {
        $this->commonService = $commonService;
        $this->userService = $userService;
        $this->profileService = $profileService;
        // $this->categoryService = new CategoryService();
        // $this->addressService = new AddressService();
        // $this->healthService = new HealthMatrixService();
        // $this->lifeStyleService = new LifeStyleService();
        // $this->insuranceService = new InsuranceService();
        // $this->uploadMediasService = new UploadedMediaService();
    }

    /**
     * get User Details
     *
     * @param Request $request
     * @return void
     */
    public function getUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_uuid' => 'string|min:8|exists:users,uuid',
        ]);

        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        $result = $this->userService->getUser($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }

        $data['user'] = $result['data'];
        return $this->commonService->getSuccessResponse('Success', $data);
    }

        /**
     * Delete User by uuid
     *
     * @param Request $request
     *
     * @return void
     */

    public function deleteUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_uuid' => 'required|exists:users,uuid',
            'profile_uuid'=> 'string|min:8|exists:profiles,uuid'
        ]);

        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        $result = $this->profileService->getProfile($request);
        if(!$result['status'])
        {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);

        }
        $profile = $result['data'];
        $request->merge(['profile_id' => $profile->id]);

        $result = $this->userService->deleteUser($request);
        if(!$result['status'])
        {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }

        $delete_User = $result['data'];
        return $this->commonService->getSuccessResponse('Record Deleted Successfully', []);


    }

    /**
     * Get Profile Details
     *
     * @param Request $request
     *
     * @return void
     */
    public function getProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_uuid' => 'required|exists:profiles,uuid',
        ]);

        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        $result = $this->profileService->getProfile($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }

        $data['profile'] = $result['data'];
        return $this->commonService->getSuccessResponse('Success', $data);
    }


    /**
     * List Profiles based on filters
     *
     * @param Request $request
     *
     * @return void
     */
    public function listProfiles(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'profile_type' => 'string|in:doctor,patient',
        //     'status' => 'string|in:active,inactive',
        //     'profile_uuid' => 'string|exists:profile,uuid',

        // ]);

        // if ($validator->fails()) {
        //     $data['validation_error'] = $validator->getMessageBag();
        //     return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        // }

        if(isset($request->user_uuid) && ('' != $request->user_uuid))
        {
            $result = $this->userService->getUser($request);
            if(!$result['status'])
            {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $user= $result['data'];
            $request->merge(['user_id' => $user->id]);      
        }
        
        $profile = $this->profileService->listProfiles($request);
        if (!$profile['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }

        $data = $profile['data'];
        return $this->commonService->getSuccessResponse('Success', $data);
    }

    public function deleteProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_uuid' => 'required|exists:profiles,uuid',
        ]);

        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }
        $result = $this->profileService->deleteProfile($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }

        $data['profile'] = $result['data'];
        return $this->commonService->getSuccessResponse('Record Deleted Successfully', []);

    }
}
