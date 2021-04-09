<?php

namespace Modules\User\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\AddressService;
use App\Services\CategoryService;
use App\Services\CommonService;
use App\Services\HealthMatrixService;
use App\Services\InsuranceService;
use App\Services\LifeStyleService;
use App\Services\ProfileService;
use App\Services\UploadedMediaService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public $commonService;
    public $userService;
    public $categoryService;
    public $profileService;
    public $addressService;
    public $healthService;
    public $lifeStyleService;
    public $insuranceService;
    public $uploadMediasService;

    public function __construct()
    {
        $this->commonService = new CommonService();
        $this->userService = new UserService();
        $this->categoryService = new CategoryService();
        $this->profileService = new ProfileService();
        $this->addressService = new AddressService();
        $this->healthService = new HealthMatrixService();
        $this->lifeStyleService = new LifeStyleService();
        $this->insuranceService = new InsuranceService();
        $this->uploadMediasService = new UploadedMediaService();
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
     * Get Profile Details
     *
     * @param Request $request
     *
     * @return void
     */
    public function getProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_uuid' => 'string|min:8|exists:profiles,uuid',
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
     * Update Patient Profile
     *
     * @param Request $request
     * @return void
     */
    public function updatePatientProfile(Request $request)
    {
        $rules = [
            // profile rules - START
                'profile_uuid' => 'string|min:8|exists:profiles,uuid',
                'first_name' => 'required|min:3',
                'last_name' => 'required|min:1',
                'dob' => 'required|string',
                'gender' => 'required|string',
                'ethnicity' => 'required|string|min:3',

                'language' => 'required|string',
                'nok' => 'required|string',
                // 'phone_code' => 'string',
                // 'phone_number' => 'string',
                // 'is_policy_holder' => 'string',

                'profile_image' => 'file|mimes:jpg,jpeg,bmp,png,pdf',
                // 'emergency_contact' => 'string',
            // profile rules - END

            // address rules - START
                'address_uuid' => 'string|min:8|exists:addresses,uuid',
                'address1' => 'required|string',
                // 'address2' => 'string',
                'city' => 'required|string',
                'country' => 'required|string',
                'zip' => 'required|string',
            // address rules - END

            // health matrix rules- START
                'health_matrix_uuid' => 'string|min:8|exists:health_matrixes,uuid',
                // 'height' => 'string',
                // 'weight' => 'string',
                // 'pulse' => 'string',
                // 'oxygen_satration' => 'string',
                // 'blood_sugar' => 'string',
                // 'bmi' => 'string',
                // 'body_temprature' => 'string',
                // 'blood_presure_s' => 'string',
                // 'blood_presure_d' => 'string',

                // 'cholestrol_total' => 'string',
                // 'cholestrol_ldl' => 'string',
                // 'cholestrol_hdl' => 'string',
                // 'cholestrol_hdl_ratio' => 'string',
                // 'triglycerides' => 'string',
                // 'glucose' => 'string',

                // 'waist_circumference' => 'string',
                // 'conditions_past' => 'string',
                // 'conditions_current' => 'string',
                // 'medications_past' => 'string',
                // 'medications_current' => 'string',
                // 'allergies_past' => 'string',
                // 'allergies_current' => 'string',

                // 'treatment' => 'string',
                // 'vacations' => 'string',
            // health matrix rules - END

            // life style rules - START
                'life_style_uuid' => 'string|min:8|exists:life_styles,uuid',
                // 'diet_resriction_id' => 'string',
                // 'recreational_drug_id' => 'string',
                // 'alcohol_id' => 'string',
                // 'tobaco_id' => 'string',
                // 'sexually_Active_id' => 'string',
                // 'preferred_pharmacy' => 'string',
            // life style rules - END

            // Insurance rules - START
                'insurance_uuid' => 'string|min:8|exists:insurances,uuid',
                // 'insurance_first_name' => 'string',
                // 'insurance_last_name' => 'string',
                // 'provider' => 'string',
                // 'member_id' => 'string',
            // Insurance rules - END
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // logout user if is deleted
        if ($request->user()->profile == null) {
            $authCtrlObj = new AuthController();
            return $authCtrlObj->signout($request);
        }

        // validate that user messes his own profile or doctor does
        if(!isset($request->profile_uuid) || ('' == $request->profile_uuid) ){
            if($request->user()->profile->profile_type == 'patient')
            {
                $request->profile_uuid = $request->user()->profile->uuid;
            }
            else{
                return $this->commonService->getNoRecordFoundResponse('Invalid INformation Provided');
            }
        }

        // upload Media for profile typed
        // dd($request->all());
        if ($request->hasFile('profile_image')) {
            $result = $this->uploadMediasService->uploadMedias($request, 'profile_image', 'profile_image');
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $uploadedfiles = $result['data'];
            $request->merge(['profile_picture' => $uploadedfiles[0]['path']]);
        }

        // update Profile - START
            // get Profile based on email|phone
            $result = $this->profileService->checkPatient($request);
            if (!$result['status']) {
                if ($result['exceptionCode'] == 404) {
                    return $this->commonService->getNoRecordFoundResponse('Profile Not Found');
                } else {
                    return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
                }
            }
            $profile = $result['data'];
            $request->merge(['profile_type' => 'patient']);

            // update profile details
            $result = $this->profileService->addUpdateProfile($request, $profile->id);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
        // update Profile - END

        // add|update address - START
            // get Address based on address_uuid
            $address_id = null;
            $result = $this->addressService->checkAddress($request);
            if (!$result['status']) {
                if ($result['exceptionCode'] != 404) {
                    return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
                }
            }
            if($result['exceptionCode'] == null){
                $address = $result['data'];
                $address_id = $address->id;
            }

            // update Address details
            $request->merge(['profile_id' => $profile->id]);
            $result = $this->addressService->addUpdateAddress($request, $address_id);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
        // add|update address - END

        // update Health Matrix - START
            // get Health Matrix based on health_matrix_uuid
            $health_matrix_id = null;
            $result = $this->healthService->checkHealthMatrix($request);
            if (!$result['status']) {
                if ($result['exceptionCode'] != 404) {
                    return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
                }
            }
            if($result['exceptionCode'] == null){
                $health_matrix = $result['data'];
                $health_matrix_id = $health_matrix->id;
            }

            // update Health Matrix details
            $request->merge(['profile_id' => $profile->id]);
            $result = $this->healthService->addUpdateHealthMatrix($request, $health_matrix_id);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }

        // update Health Matrix - END

        // update Life Style - START
            // get Life Style based on life_style_uuid
            $life_style_id = null;
            $result = $this->lifeStyleService->checkLifeStyle($request);
            if (!$result['status']) {
                if ($result['exceptionCode'] != 404) {
                    return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
                }
            }
            if($result['exceptionCode'] == null){
                $life_style = $result['data'];
                $life_style_id = $life_style->id;
            }

            // Update Life Style details
            $request->merge(['profile_id' => $profile->id]);
            $result = $this->lifeStyleService->addUpdateLifeStyle($request, $life_style_id);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }

        // update Life Style - END

        // update Insurance - START
            // get Insurance based on insurance_uuid
            $insurance_id = null;
            $result = $this->insuranceService->checkInsurance($request);
            if (!$result['status']) {
                if ($result['exceptionCode'] != 404) {
                    return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
                }
            }
            if($result['exceptionCode'] == null){
                $insurance = $result['data'];
                $insurance_id = $insurance->id;
            }

            // Update Insurance details
            $request->merge(['profile_id' => $profile->id]);
            $result = $this->insuranceService->addUpdateInsurance($request, $insurance_id);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }

        // update Insurance - END

        $request->merge(['profile_uuid'=> $profile->uuid]);
        $data['profile'] = $this->profileService->getProfile($request)['data'];
        return $this->commonService->getSuccessResponse('Success', $data);
    }


    /**
     * Create  Doctor Profile [ADMIN ONLY]
     *
     * @param Request $request
     * @return void
     */
    public function createDoctorProfile(Request $request)
    {
        $rules = [
            // user rules - START
                'is_social' => 'required|in:0,1',
                'device_type' => 'required|in:andriod,ios,web',

                'first_name' => 'required|min:3',
                'last_name' => 'required|min:1',
                'dob' => 'required',
                'gender' => 'required',
                // 'ethnicity' => 'required|min:3',

                'email' => 'required_if:is_social,0|min:6|unique:users,email',
                'password' => 'required_if:is_social,0|min:8|confirmed',
                // 'password' => 'required_if:is_social,0|min:8|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',

                'social_type' => 'required_if:is_social,1|in:google,apple',
                'social_id' => 'required_if:is_social,1|min:6',
            // user rules - END

            // profile rules - START
                // 'language' => 'required|string',
                // 'nok' => 'required|string',
                // 'phone_code' => 'string',
                // 'phone_number' => 'string',
                // 'is_policy_holder' => 'string',

                'profile_image' => 'file|mimes:jpg,jpeg,bmp,png,pdf',
                // 'emergency_contact' => 'string',

                // doctor specific
                'category_uuid' => 'required|string|min:3',
                'license_number' => 'required|string|min:3',
                'license_authority' => 'required|string|min:3',
                // 'license_organization' => 'string|min:3',

                // 'social_security' => 'string|min:3',
                // 'position' => 'string',
                // 'specialization' => 'string',

                // 'organizations' => 'string',
                // 'start_time' => 'string',
                // 'end_time' => 'string',
                // 'is_convicted' => 'string',
            // profile rules - END

            // address rules - START
                'address_uuid' => 'string|min:8|exists:addresses,uuid',
                'address1' => 'required|string',
                // 'address2' => 'string',
                'city' => 'required|string',
                'country' => 'required|string',
                'zip' => 'required|string',
            // address rules - END

            // health matrix rules- START
                'health_matrix_uuid' => 'string|min:8|exists:health_matrixes,uuid',
                // 'height' => 'string',
                // 'weight' => 'string',
                // 'pulse' => 'string',
                // 'oxygen_satration' => 'string',
                // 'blood_sugar' => 'string',
                // 'bmi' => 'string',
                // 'body_temprature' => 'string',
                // 'blood_presure_s' => 'string',
                // 'blood_presure_d' => 'string',

                // 'cholestrol_total' => 'string',
                // 'cholestrol_ldl' => 'string',
                // 'cholestrol_hdl' => 'string',
                // 'cholestrol_hdl_ratio' => 'string',
                // 'triglycerides' => 'string',
                // 'glucose' => 'string',

                // 'waist_circumference' => 'string',
                // 'conditions_past' => 'string',
                // 'conditions_current' => 'string',
                // 'medications_past' => 'string',
                // 'medications_current' => 'string',
                // 'allergies_past' => 'string',
                // 'allergies_current' => 'string',

                // 'treatment' => 'string',
                // 'vacations' => 'string',
            // health matrix rules - END

            // life style rules - START
                'life_style_uuid' => 'string|min:8|exists:life_styles,uuid',
                // 'diet_resriction_id' => 'string',
                // 'recreational_drug_id' => 'string',
                // 'alcohol_id' => 'string',
                // 'tobaco_id' => 'string',
                // 'sexually_Active_id' => 'string',
                // 'preferred_pharmacy' => 'string',
            // life style rules - END

            // Insurance rules - START
                'insurance_uuid' => 'string|min:8|exists:insurances,uuid',
                // 'insurance_first_name' => 'string',
                // 'insurance_last_name' => 'string',
                // 'provider' => 'string',
                // 'member_id' => 'string',
            // Insurance rules - END
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        $request->merge([
            'profile_type' => 'doctor',
            'email_verified_at' => date('Y-m-d H:i:s'),
        ]);

        // create User - START
            // get Profile based on email|phone
            $result = $this->userService->addUpdateUser($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $user = $result['data'];
            $request->merge(['user_id' => $user->id]);
        // create User - END

        // create category - START
            $result = $this->categoryService->checkCategory($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $category = $result['data'];
            $request->merge(['category_id' => $category->id]);
        // create category - END

        // upload Media for profile type
        if($request->hasFile('profile_image')){
            $result = $this->uploadMediasService->uploadMedias($request, 'profile_image', 'profile_image');
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $uploadedfiles = $result['data'];
            $request->merge(['profile_picture' => $uploadedfiles[0]['path']]);
        }

        // create Profile - START
            $result = $this->profileService->addUpdateProfile($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $profile = $result['data'];
        // create Profile - END

        // add profile to user model - START
            $result = $this->userService->switchUserProfile($user->id, $profile->id, $profile->profile_type);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $user = $result['data'];
            $request->merge(['user_id' => $user->id]);

        // add profile to user model - END


        // add|update address - START
            // get Address based on address_uuid
            $address_id = null;
            $result = $this->addressService->checkAddress($request);
            if (!$result['status']) {
                if ($result['exceptionCode'] != 404) {
                    return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
                }
            }
            if($result['exceptionCode'] == null){
                $address = $result['data'];
                $address_id = $address->id;
            }

            // update Address details
            $request->merge(['profile_id' => $profile->id]);
            $result = $this->addressService->addUpdateAddress($request, $address_id);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
        // add|update address - END

        // update Health Matrix - START
            // get Health Matrix based on health_matrix_uuid
            $health_matrix_id = null;
            $result = $this->healthService->checkHealthMatrix($request);
            if (!$result['status']) {
                if ($result['exceptionCode'] != 404) {
                    return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
                }
            }
            if($result['exceptionCode'] == null){
                $health_matrix = $result['data'];
                $health_matrix_id = $health_matrix->id;
            }

            // update Health Matrix details
            $request->merge(['profile_id' => $profile->id]);
            $result = $this->healthService->addUpdateHealthMatrix($request, $health_matrix_id);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }

        // update Health Matrix - END

        // update Life Style - START
            // get Life Style based on life_style_uuid
            $life_style_id = null;
            $result = $this->lifeStyleService->checkLifeStyle($request);
            if (!$result['status']) {
                if ($result['exceptionCode'] != 404) {
                    return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
                }
            }
            if($result['exceptionCode'] == null){
                $life_style = $result['data'];
                $life_style_id = $life_style->id;
            }

            // Update Life Style details
            $request->merge(['profile_id' => $profile->id]);
            $result = $this->lifeStyleService->addUpdateLifeStyle($request, $life_style_id);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }

        // update Life Style - END

        // update Insurance - START
            // get Insurance based on insurance_uuid
            $insurance_id = null;
            $result = $this->insuranceService->checkInsurance($request);
            if (!$result['status']) {
                if ($result['exceptionCode'] != 404) {
                    return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
                }
            }
            if($result['exceptionCode'] == null){
                $insurance = $result['data'];
                $insurance_id = $insurance->id;
            }

            // Update Insurance details
            $request->merge(['profile_id' => $profile->id]);
            $result = $this->insuranceService->addUpdateInsurance($request, $insurance_id);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }

        // update Insurance - END

        $request->merge(['profile_uuid'=> $profile->uuid]);
        $data['profile'] = $this->profileService->getProfile($request)['data'];
        return $this->commonService->getSuccessResponse('Success', $data);
    }

    /**
     * Update Doctor Profile
     *
     * @param Request $request
     * @return void
     */
    public function updateDoctorProfile(Request $request)
    {
        $rules = [
            // profile rules - START
                'profile_uuid' => 'string|min:8|exists:profiles,uuid',
                'first_name' => 'required|min:3',
                'last_name' => 'required|min:3',
                'dob' => 'required|string',
                'gender' => 'required|string',
                // 'ethnicity' => 'required|string|min:3',

                // 'language' => 'required|string',
                // 'nok' => 'required|string',
                // 'phone_code' => 'string',
                // 'phone_number' => 'string',
                // 'is_policy_holder' => 'string',

                'profile_image' => 'file|mimes:jpg,jpeg,bmp,png,pdf',
                // 'emergency_contact' => 'string',

                // doctor specific
                'category_uuid' => 'required|string|min:3',
                'license_number' => 'required|string|min:3',
                'license_authority' => 'required|string|min:3',
                // 'license_organization' => 'string|min:3',

                // 'social_security' => 'string|min:3',
                // 'position' => 'string',
                // 'specialization' => 'string',

                // 'organizations' => 'string',
                // 'start_time' => 'string',
                // 'end_time' => 'string',
                // 'is_convicted' => 'string',
            // profile rules - END

            // address rules - START
                'address_uuid' => 'string|min:8|exists:addresses,uuid',
                'address1' => 'required|string',
                // 'address2' => 'string',
                'city' => 'required|string',
                'country' => 'required|string',
                'zip' => 'required|string',
            // address rules - END

            // health matrix rules- START
                'health_matrix_uuid' => 'string|min:8|exists:health_matrixes,uuid',
                // 'height' => 'string',
                // 'weight' => 'string',
                // 'pulse' => 'string',
                // 'oxygen_satration' => 'string',
                // 'blood_sugar' => 'string',
                // 'bmi' => 'string',
                // 'body_temprature' => 'string',
                // 'blood_presure_s' => 'string',
                // 'blood_presure_d' => 'string',

                // 'cholestrol_total' => 'string',
                // 'cholestrol_ldl' => 'string',
                // 'cholestrol_hdl' => 'string',
                // 'cholestrol_hdl_ratio' => 'string',
                // 'triglycerides' => 'string',
                // 'glucose' => 'string',

                // 'waist_circumference' => 'string',
                // 'conditions_past' => 'string',
                // 'conditions_current' => 'string',
                // 'medications_past' => 'string',
                // 'medications_current' => 'string',
                // 'allergies_past' => 'string',
                // 'allergies_current' => 'string',

                // 'treatment' => 'string',
                // 'vacations' => 'string',
            // health matrix rules - END

            // life style rules - START
                'life_style_uuid' => 'string|min:8|exists:life_styles,uuid',
                // 'diet_resriction_id' => 'string',
                // 'recreational_drug_id' => 'string',
                // 'alcohol_id' => 'string',
                // 'tobaco_id' => 'string',
                // 'sexually_Active_id' => 'string',
                // 'preferred_pharmacy' => 'string',
            // life style rules - END

            // Insurance rules - START
                'insurance_uuid' => 'string|min:8|exists:insurances,uuid',
                // 'insurance_first_name' => 'string',
                // 'insurance_last_name' => 'string',
                // 'provider' => 'string',
                // 'member_id' => 'string',
            // Insurance rules - END
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // logout user if is deleted
        if ($request->user()->profile == null) {
            $authCtrlObj = new AuthController();
            return $authCtrlObj->signout($request);
        }

        // validate that user messes his own profile or doctor does
        if(!isset($request->profile_uuid) || ('' == $request->profile_uuid) ){
            if($request->user()->profile->profile_type == 'doctor'){
                $request->profile_uuid = $request->user()->profile->uuid;
            }
            else{
                return $this->commonService->getNoRecordFoundResponse('Invalid INformation Provided');
            }
        }

        // upload Media for profile type
        if ($request->hasFile('profile_image')) {
            $result = $this->uploadMediasService->uploadMedias($request, 'profile_image', 'profile_image');
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $uploadedfiles = $result['data'];
            $request->merge(['profile_picture' => $uploadedfiles[0]['path']]);
        }

        // create category - START
            $result = $this->categoryService->checkCategory($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $category = $result['data'];
            $request->merge(['category_id' => $category->id]);
        // create category - END

        // update Profile - START
            // get Profile based on email|phone
            $result = $this->profileService->checkDoctor($request);
            if (!$result['status']) {
                if ($result['exceptionCode'] == 404) {
                    return $this->commonService->getNoRecordFoundResponse('Profile Not Found');
                } else {
                    return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
                }
            }
            $profile = $result['data'];
            $request->merge(['profile_type' => 'doctor']);

            // update profile details
            $result = $this->profileService->addUpdateProfile($request, $profile->id);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
        // update Profile - END

        // add|update address - START
            // get Address based on address_uuid
            $address_id = null;
            $result = $this->addressService->checkAddress($request);
            if (!$result['status']) {
                if ($result['exceptionCode'] != 404) {
                    return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
                }
            }
            if($result['exceptionCode'] == null){
                $address = $result['data'];
                $address_id = $address->id;
            }

            // update Address details
            $request->merge(['profile_id' => $profile->id]);
            $result = $this->addressService->addUpdateAddress($request, $address_id);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
        // add|update address - END

        // update Health Matrix - START
            // get Health Matrix based on health_matrix_uuid
            $health_matrix_id = null;
            $result = $this->healthService->checkHealthMatrix($request);
            if (!$result['status']) {
                if ($result['exceptionCode'] != 404) {
                    return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
                }
            }
            if($result['exceptionCode'] == null){
                $health_matrix = $result['data'];
                $health_matrix_id = $health_matrix->id;
            }

            // update Health Matrix details
            $request->merge(['profile_id' => $profile->id]);
            $result = $this->healthService->addUpdateHealthMatrix($request, $health_matrix_id);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }

        // update Health Matrix - END

        // update Life Style - START
            // get Life Style based on life_style_uuid
            $life_style_id = null;
            $result = $this->lifeStyleService->checkLifeStyle($request);
            if (!$result['status']) {
                if ($result['exceptionCode'] != 404) {
                    return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
                }
            }
            if($result['exceptionCode'] == null){
                $life_style = $result['data'];
                $life_style_id = $life_style->id;
            }

            // Update Life Style details
            $request->merge(['profile_id' => $profile->id]);
            $result = $this->lifeStyleService->addUpdateLifeStyle($request, $life_style_id);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }

        // update Life Style - END

        // update Insurance - START
            // get Insurance based on insurance_uuid
            $insurance_id = null;
            $result = $this->insuranceService->checkInsurance($request);
            if (!$result['status']) {
                if ($result['exceptionCode'] != 404) {
                    return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
                }
            }
            if($result['exceptionCode'] == null){
                $insurance = $result['data'];
                $insurance_id = $insurance->id;
            }

            // Update Insurance details
            $request->merge(['profile_id' => $profile->id]);
            $result = $this->insuranceService->addUpdateInsurance($request, $insurance_id);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }

        // update Insurance - END

        $request->merge(['profile_uuid'=> $profile->uuid]);
        $data['profile'] = $this->profileService->getProfile($request)['data'];
        return $this->commonService->getSuccessResponse('Success', $data);
    }

    /**
     * Add Certifications for Doctor
     *
     * @param Request $request
     * @return void
     */
    public function addDoctorCertifications(Request $request)
    {
        $rules = [
            'profile_uuid' => 'string|min:8|exists:profiles,uuid',
            'certifications.*' => 'required|file|mimes:jpg,jpeg,bmp,png,pdf',

        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate doctors
        $result = $this->profileService->checkDoctor($request);
        if (!$result['status']) {
            if ($result['exceptionCode'] == 404) {
                $result['message'] = 'Doctor is invalid';
            }
            return $result;
        }
        $doctor = $result['data'];


        $result = $this->uploadMediasService->uploadMedias($request, 'certifications', 'certificate', true);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        // dd($result['data']);
        $request->merge(['uploadedFiles' => $result['data']]);


        $request->merge([
            'profile_id' => $doctor->id,
        //     'medias' => $request->certifications
        ]);

        // Accept|Reject an Appointment of a Patient
        $result = $this->uploadMediasService->uploadDoctorCertificates($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $medias = $result['data'];

        // $request->merge(['appointment_uuid' => $appointment->uuid]);
        $result = $this->profileService->getProfile($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $data['profile'] = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $data);
    }

    public function addPatientLabTests(Request $request)
    {
        $rules = [
            'profile_uuid' => 'required|string|min:8|exists:profiles,uuid',
            'lab_tests.*' => 'required|file|mimes:jpg,jpeg,bmp,png,pdf',
        ];
        // dd($request->profile_uuid);

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate doctors
        $result = $this->profileService->checkPatient($request);
        if (!$result['status']) {
            if ($result['exceptionCode'] == 404) {
                $result['message'] = 'Patient is invalid';
            }
            return $result;
        }
        $patient = $result['data'];


        $result = $this->uploadMediasService->uploadMedias($request, 'lab_tests', 'lab_test', true);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        // dd($result['data']);
        $request->merge(['uploadedFiles' => $result['data']]);


        $request->merge([
            'profile_id' => $patient->id,
            //     'medias' => $request->certifications
        ]);

        // Accept|Reject an Appointment of a Patient
        $result = $this->uploadMediasService->uploadPatientLabTests($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $medias = $result['data'];

        // $request->merge(['appointment_uuid' => $appointment->uuid]);
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
        $validator = Validator::make($request->all(), [
            'profile_type' => 'string|in:doctor,patient',
            'status' => 'string|in:active,suspended,terminated',
            'category_uuid' => 'string|min:8|exists:categories,uuid',

        ]);

        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        $result = $this->profileService->listProfiles($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }

        $data = $result['data'];
        return $this->commonService->getSuccessResponse('Success', $data);
    }
}
