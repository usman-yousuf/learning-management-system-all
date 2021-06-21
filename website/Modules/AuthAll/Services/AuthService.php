<?php

namespace Modules\AuthAll\Services;

// use Modules\AuthAll\Http\Controllers\TwilioController;
use Modules\AuthAll\Entities\PasswordReset;
use Modules\User\Entities\Profile;
use Modules\User\Entities\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\AuthAll\Entities\AuthVerification;
use Modules\User\Services\UserService;
use Modules\User\Services\ProfileService;
use Modules\Common\Services\StatsService;
use Modules\Common\Services\CommonService;

class AuthService
{
    protected $userService;
    protected $profileService;
    protected $statsService;
    protected $commonService;

    /**
     * setup services for global usage
     */
    public function __construct(UserService $userService, ProfileService $profileService, StatsService $statsService, CommonService $commonService)
    {
        $this->userService = $userService;
        $this->profileService = $profileService;
        $this->statsService = $statsService;
        $this->commonService = $commonService;
    }

    /**
     * Register User in application
     *
     * @param Request $request
     * @return void
     */
    public function registerUser(Request $request)
    {
        if (!isset($request->profile_type) && ('' != $request->profile_type)) {
            $request->merge(['profile_type' => 'student']);
        }
        // add|update user
        $userResponse = $this->userService->addUpdateUser($request, null);
        if(!$userResponse['status']){
            return $userResponse;
        }
        $user = $userResponse['data'];
        $request->merge(['user_id' => $user->id]);

        // add|update profile
        $profileResponse = $this->profileService->addUpdateProfile($request);
        if (!$profileResponse['status']) {
            return $profileResponse;
        }
        $profile = $profileResponse['data'];

        // update current profile for user
        $switchResponse = $this->userService->switchUserProfile($user->id, $profile->id, $profile->profile_type);
        if (!$switchResponse['status']) {
            return $switchResponse;
        }

        // update stats
        $statsResponse = $this->statsService->updateNewUserStats($request);
        if (!$statsResponse['status']) {
            return $statsResponse;
        }

        // $user = $switchResponse['data'];

        $user = User::where('id', $user->id)->with('profile') ->first();
        return getInternalSuccessResponse($user);
    }

    /**
     * Send AuthenticationVerification Code to user
     *
     * @param User $user
     * @param Integer $code
     * @param Request $request
     * @return void
     */
    public function sendVerificationToken($user, $code, $request)
    {
        $verificationModel = new AuthVerification();
        if (isset($request->phone_number) && isset($request->phone_code)) {

            $result = $this->commonService->sendAccountVerificationSMS($request->phone_code . $request->phone_number, $code);
            if (!$result['status']) {
                return $result;
            }
            $verificationModel->type = 'phone';
            $verificationModel->phone = (strpos($request->phone_number, '+') > -1) ? $request->phone_number : $request->phone_code . $request->phone_number;
            $verificationModel->email = null;
        }

        if(isset($request->email) && ('' != $request->email)){
            $result = $this->commonService->sendVerificationEmail($user->email, 'Verification', 'authall::email_template.verification_code', ['code' => $code]);
            if(!$result['status']){
                return $result;
            }
            $verificationModel->type = 'email';
            $verificationModel->email = $request->email;
        }
        $verificationModel->token = $code;
        $verificationModel->created_at = date('Y-m-d H:i:s');

        if (isset($request->phone_number) && isset($request->phone_code)) {
            $user->phone_verified_at = null;
        } else {
            $user->email_verified_at = null;
        }

        // save model
        try {
            $verificationModel->save();
            // dd($model->getAttributes());
            return getInternalSuccessResponse($verificationModel);
        } catch (\Exception $ex) {
            // dd($ex);
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }

    /**
     * Verfy a User
     *
     * @param Request $request
     * @return void
     */
    public function verifyUser($request)
    {
        // look for User
        $result = $this->checkUser($request);
        if (!$result['status']) {
            return $result;
        }
        $user = $result['data'];


        // look for Auth Verification
        $result = $this->deleteAuthVerificationToken($request);
        if (!$result['status']) {
            return $result;
        }

        if (isset($request->email) && $request->email != '') {
            $user->email_verified_at = date('Y-m-d H:i:s');
        } else {
            $result = $user->profile->update(['phone_verified_at' => date('Y-m-d H:i:s')]);
        }

        // update user info
        if (isset($request->user_uuid) && ($request->user_uuid != null)) { // case: new email|phone is to be set
            if (isset($request->email) && $request->email != '') {
                $user->email = $request->email;
            } else {
                $user->profile->update([
                    'phone_code' => $request->phone_code,
                    'phone_number' => $request->phone_number
                ]);
            }
        }

        try {
            $user->save();
            return getInternalSuccessResponse($user);
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }

    /**
     * Create Authorization Token
     *
     * @param Request $request
     * @return void
     */
    public function createAuthorizationToken($request, $foundUser)
    {
        $tokenResult = $foundUser->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }

        try {
            $token->save();
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }

        $data['access_token'] = $tokenResult->accessToken;
        $data['token_type'] = 'Bearer';
        $data['expires_at'] = Carbon::parse($tokenResult->token->expires_at)->toDateTimeString();

        return getInternalSuccessResponse($data, 'token created successfully');
    }

    public function validatePhoneNumber($phone_code, $phone_number)
    {
        $result = $this->commonService->validatePhoneNumber($phone_code.$phone_number, $phone_code);
        if(!$result['status']){
            return $result;
        }
        return getInternalSuccessResponse([], 'Phone Number is Valid');

        // // validate phone number
        // if (isset($phone_number) && isset($phone_code)) {

        //     $twilio = new TwilioController;
        //     if (!$twilio->validNumber($phone_code . $phone_number, $phone_code)) {
        //         return $this->commonService->getNoRecordFoundResponse('Phone is invalid');
        //     }
        // }
    }

    /**
     * Generate Password Reset Token for given email|phone
     *
     * @param Request $request
     * @param Integer $code
     *
     * @return void
     */
    public function generatePasswordResetToken($request, $code = null)
    {
        // delete existing reset token if any
        $result = $this->deleteExistingPasswordResetToken($request);
        if (!$result['status']) {
            return $result;
        }

        // create new model
        $model = new PasswordReset();
        $code = (null != $code) ? $code : mt_rand(1000, 9999);
        if (isset($request->email) && $request->email != '') {
            $model->email = $request->email;
            $model->type = 'email';
        } else {
            $model->email = $request->email;
            $model->type = 'phone';
            $model->phone = $request->phone_code . $request->phone_number;
        }
        $model->token = $code;
        $model->created_at = date('Y-m-d H:i:s');

        try {
            $model->save();

            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }

    /**
     * Update Password
     *
     * @param Request $request
     * @return void
     */
    public function resetPassword($request)
    {
        $result = $this->checkUser($request);
        if(!$result['status']){
            return $result;
        }
        $user = $result['data'];
        $result = $this->matchExistingPasswordResetToken($request);
        if(!$result['status']){
            return $result;
        }
        $tokenModel = $result['data'];
        $tokenModel->delete(); // delete that token code

        // update user password
        $result = $this->userService->updatePassword($user->id, $request->new_password);
        // dd($result);
        if(!$result['status']){
            return $result;
        }
        $user = $result['data'];

        return getInternalSuccessResponse($user);
    }

    /**
     * Update Password of current
     *
     * @param Request $request
     * @return void
     */
    public function updatePassword($request)
    {
        $result = $this->userService->updatePassword($request->user_id, $request->new_password, $request->old_password);
        if(!$result['status']){
            return $result;
        }

        return getInternalSuccessResponse($result['data']);
    }

    /**
     * Check Auth user
     *
     * @param Request $request
     * @return void
     */
    public function checkAuthUser(Request $request)
    {
        if (!isset($request->user_uuid) || null == $request->user_uuid) { // get user based on email|phone

            if (isset($request->email) && ($request->email != '')) {
                $foundUser = User::where('email', $request->email)->orWhere('username', $request->email);
            } else {
                $foundUser = Profile::where('phone_code', $request->phone_code)->where('phone_number', $request->phone_number)->first();
                if(null != $foundUser){
                    $foundUser = User::where('id', $foundUser->user_id)->first();
                }
                // Profile::where('phone_code', $request->phone_code)->where('phone_number', $request->phone_number);
            }
            // request role
            if(isset($request->role) && ('' != $request->role)){
                $foundUser->where('profile_type', $request->role);
            }

            $foundUser = $foundUser->first();

            if(null == $foundUser){
                return getInternalErrorResponse('Invalid Username or Password', [], 404, 404);
            }

            if(!Hash::check($request->password, $foundUser->password)){
                // dd($foundUser->password, $request->password);
                return getInternalErrorResponse('Invalid Username or Password', [], 404, 404);
            }
        } else {
            $foundUser = User::where('uuid', $request->user_uuid)->width('profile')->first();
        }

        if (null == $foundUser) {
            return getInternalErrorResponse('No Record Found', [], 404, 404);
        }

        return getInternalSuccessResponse($foundUser);
    }

    /**
     * Validate a User Existence
     *
     * @param Request $request
     * @return void
     */
    public function checkUser(Request $request)
    {
        if (!isset($request->user_uuid) || '' == $request->user_uuid) { // get user based on email|phone
            if (isset($request->email) && ($request->email != '')) {
                $foundUser = User::where('email', $request->email)->orWhere('username', $request->email)->first();
            } else {
                $foundUser = Profile::where('phone_code', $request->phone_code)->where('phone_number', $request->phone_number)->first();
                if(null != $foundUser){
                    $foundUser = $foundUser->user;
                }
            }
        } else {
            $foundUser = User::where('uuid', $request->user_uuid)->first();
        }

        if (null == $foundUser) {
            return getInternalErrorResponse('No Record Found', [], 404, 404);
        }

        return getInternalSuccessResponse($foundUser);
    }

    /**
     * Validate given AuthVerification Code details
     *
     * @param [type] $request
     * @return void
     */
    public function checkAuthVerificationToken($request)
    {
        if (isset($request->email) && $request->email != '') {
            $verificationModel = AuthVerification::where('email', $request->email)->where('token', $request->activation_code)->first();
        } else {
            $verificationModel = AuthVerification::where('phone', $request->phone_code . $request->phone_number)->where('token', $request->activation_code)->first();
        }

        if (null == $verificationModel) {
            return getInternalErrorResponse('No Record Found', [], 404, 404);
        }

        return getInternalSuccessResponse($verificationModel);
    }

    /**
     * Delete a Auth Verification Code
     *
     * @param Request $request
     * @return void
     */
    public function deleteAuthVerificationToken($request)
    {
        $result = $this->checkAuthVerificationToken($request);
        if(!$result['status']){
            // if($result['exceptionCode'] != 404){
                return $result;
            // }
        }
        $model = $result['data'];
        if($result['exceptionCode'] != 404){
            $model->delete();
        }

        return getInternalSuccessResponse();
    }

    /**
     * Check Existing Password Reset Token
     *
     * @param Request $request
     * @return void
     */
    public function checkExistingPasswordResetToken(Request $request)
    {
        if (isset($request->email) && $request->email != '') {
            $model = PasswordReset::where('email', $request->email)->where('type', 'email')->first();
        } else {
            $model = PasswordReset::where('phone', $request->phone_code . $request->phone_number)->where('type', 'phone')->first();
        }

        if (null == $model) {
            return getInternalErrorResponse('No Record Found', [], 404, 404);
        }

        return getInternalSuccessResponse($model);
    }

    /**
     * Match Existing Password Reset Token
     *
     * @param Request $request
     * @return void
     */
    public function matchExistingPasswordResetToken(Request $request)
    {
        if (isset($request->email) && $request->email != '') {
            $model = PasswordReset::where('email', $request->email)->where('type', 'email');
        } else {
            $model = PasswordReset::where('phone', $request->phone_code . $request->phone_number)->where('type', 'phone');
        }
        $model = $model->where('token', $request->code)->first();

        if (null == $model) {
            return getInternalErrorResponse('No Record Found', [], 404, 404);
        }

        return getInternalSuccessResponse($model);
    }

    /**
     * Delete an Existing Password Reset Token
     *
     * @param Request $request
     * @return void
     */
    public function deleteExistingPasswordResetToken($request)
    {
        $result = $this->checkExistingPasswordResetToken($request);
        if (!$result['status']) {
            if ($result['exceptionCode'] != 404) {
                return $result;
            }
        }
        $model = $result['data'];
        if ($result['exceptionCode'] != 404) {
            $model->delete();
        }

        return getInternalSuccessResponse();
    }

    public function socialLogin(Request $request)
    {
        // get|create a user via social info
        $result = $this->userService->checkSocialUser($request);
        if(!$result['status']){
            if(404 == $result['exceptionCode']){ // Record not found
                // create user

                if (!isset($request->first_name) || ('' == $request->first_name)) {
                    $data['validation_error'] = ['first_name' => 'First Name is Required'];
                    return getInternalErrorResponse('First Name is Required', $data, 422, 422);
                }
                // dd($request->all());

                if(!isset($request->profile_type) && ('' != $request->profile_type)){
                    $request->merge(['profile_type' => 'student']);
                }
                $result = $this->userService->addUpdateUser($request);
                if (!$result['status']) {
                    // dd($result);
                    return $result;
                }
                $user = $result['data'];

                // create profile

                $request->merge(['user_id' => $user->id]);
                $result = $this->profileService->addUpdateProfile($request);
                if (!$result['status']) {
                    // dd($result);
                    return $result;
                }
                $profile = $result['data'];
                // dd($result);


                // update profile for user
                $switchResponse = $this->userService->switchUserProfile($user->id, $profile->id, $request->profile_type);
                if (!$switchResponse['status']) {
                    return $switchResponse;
                }

                // update stats
                $statsResponse = $this->statsService->updateNewUserStats($request);
                if (!$statsResponse['status']) {
                    return $statsResponse;
                }
            }
            else{
                return $result;
            }
        }
        else{
            $user = $result['data'];
        }
        // dd($user);

        // login that found user
        \Auth::login($user);
        // Auth::loginUsingId($user->id);
        // dd(auth::user(), \Auth::user(), $request->user());
        if ($request->user() != null) {
            $result = $this->createAuthorizationToken($request, $user);
            if (!$result['status']) {
                return $result;
            }
            $token = $result['data'];
            $data = $token;

            $data['user'] = $this->userService->getUserById($user->id)['data'];
            return getInternalSuccessResponse($data, 'Logged in Successfully');
        } else {
            return getInternalErrorResponse('Something went wrong while loggin with social media');
        }

    }

    // public function socialLogin(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required_unless:social_type,apple', //|string|email
    //         //'email' => 'required|string|email',
    //         'social_id' => 'required',
    //         'social_type' => 'required'
    //     ]);

    //     if ($validator->fails()) {
    //         $data['validation_error'] = $validator->getMessageBag();
    //         return sendError($validator->errors()->all()[0], $data);
    //     }

    //     $user = null;

    //     if ($request->social_type == 'apple') {
    //         $user = User::where('social_id', $request->social_id)->first();
    //     } else {
    //         $user = User::where('email',  $request->email)->where('social_id', $request->social_id)->first();
    //     }

    //     $check1 = User::where('email',  $request->email)->first();
    //     if (!$user && $check1) {
    //         return sendError('Email has been registered already with another account.', null);
    //     }

    //     $check2 = User::where('social_id', $request->social_id)->first();
    //     if (!$user && $check2) {
    //         return sendError('Account has been registered with another email.', null);
    //     }

    //     if (!$user) {
    //         return sendError('not_registered.', null);
    //     }

    //     Auth::login($user);
    //     $tokenResult = $user->createToken('Personal Access Token');
    //     $token = $tokenResult->token;
    //     if ($request->remember_me)
    //         $token->expires_at = Carbon::now()->addWeeks(1);
    //     $token->save();
    //     Profile::where('id', $user->profile_id)->update(['is_online' => true]);

    //     $data['access_token'] = $tokenResult->accessToken;
    //     $data['token_type'] = 'Bearer';
    //     $data['expires_at'] = Carbon::parse($tokenResult->token->expires_at)->toDateTimeString();
    //     $data['user'] = getUser()->where('id', $request->user()->id)->first();
    //     return sendSuccess('Login successfully.', $data);
    // }

}
