<?php

namespace Modules\AuthAll\Http\Controllers\API;

use Illuminate\Routing\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\AuthAll\Entities\AuthVerification;
use Modules\AuthAll\Services\AuthService;
use Modules\Common\Services\CommonService;
use Modules\Common\Services\StatsService;
use Modules\User\Entities\User;

class AuthApiController extends Controller
{
    protected $statsService;
    protected $commonService;
    protected $authService;

    public function __construct(CommonService $commonService, StatsService $statsService, AuthService $authService)
    {
        $this->statsService = $statsService;
        $this->commonService = $commonService;
        $this->authService = $authService;
    }

    /**
     * Register New User
     *
     * @param Request $request
     * @return void
     */
    public function signup(Request $request)
    {
        $rules = [
            'is_social' => 'required|in:0,1',
            'device_type' => 'required|in:android,ios,web',

            'first_name' => 'required|min:3',
            'last_name' => 'required|min:1',
            'username' => 'required|min:3|unique:users,username',
            'dob' => 'required',
            'gender' => 'required',

            'email' => 'required_if:is_social,0|min:6|unique:users,email',
            'password' => 'required_if:is_social,0|min:8',
            // 'password' => 'required_if:is_social,0|min:8|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',

            'social_type' => 'required_if:is_social,1|in:google,apple',
            'social_id' => 'required_if:is_social,1|min:6',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        \DB::beginTransaction();
        $result = $this->authService->registerUser($request);
        if(!$result['status']){
            \DB::rollBack();
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $user = $result['data'];

        $code = mt_rand(1000, 9999);
        // \Log::info('Activation Code is: ' . $code);

        // if( isset($request->should_verifiy_phone) && $request->should_verifiy_phone){
            $result = $this->authService->sendVerificationToken($user, $code, $request);
            if (!$result['status']) {
                \DB::rollBack();
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
        // }
        \DB::commit();

        $data['user'] = $user;
        $data['code'] = $code;

        return $this->commonService->getSuccessResponse(null, $data, 201);
    }

    /**
     * Login User
     *
     * @param Request $request
     * @return void
     */
    public function login(Request $request)
    {
        // dd('welcome to login function');
        if($request->getMethod() == 'GET'){
            return $this->commonService->getGeneralErrorResponse('Please login Again');
        }
        // its a post call
        $rules = [
            'is_social' => 'required',
        ];
        if ($request->is_social) {
            $rules = array_merge($rules, [
                'social_email' => 'email',
                'social_id' => 'required',
                'social_type' => 'required|in:google,apple',
            ]);
        } else { // email or phone based verification
            if (isset($request->email) && ($request->email != '')) { // email
                $rules = array_merge($rules, [
                    'email' => 'required',
                    'password' => 'required|min:8',
                ]);
            } else { // phone
                $rules = array_merge($rules, [
                    'phone_code' => 'required|min:2',
                    'phone_number' => 'required|min:6',
                    'password' => 'required|min:8',
                ]);
            }
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        if($request->is_social){
            \DB::beginTransaction();
            $result = $this->authService->socialLogin($request);
            if(!$result['status']){
                if(422 == $result['exceptionCode']){
                    return $this->commonService->getValidationErrorResponse($result['message'], $result['data']);
                }
                \DB::rollBack();
                return $this->commonService->getGeneralErrorResponse('Something went wrong while logging with Social Media');
            }
            \DB::commit();
            $data = $result['data'];

            return $this->commonService->getSuccessResponse('Logged in Successfully', $data);
        }
        else{
            $result = $this->authService->checkAuthUser($request);
            if(!$result['status']){
                if($result['exceptionCode'] == 404){
                   return $this->commonService->getNoRecordFoundResponse('Invalid User or Password');
                }
                else{
                    return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
                }
            }
            $user = $result['data'];

            if (isset($request->email) && ($request->email != '')) {
                if ($user->email_verified_at == null) {
                    \DB::beginTransaction();
                    $response = $this->resendVerificationToken($request)->getData();
                    if (!$response->status) {
                        \DB::rollBack();
                        return $this->commonService->getGeneralErrorResponse('Something went wrong while sending verification token');
                    }
                    \DB::commit();
                    // $data = $response->data;
                    $data['code'] = $response->data->code;
                    return $this->commonService->getSuccessResponse('New Verification Code sent', $data);
                }
            }
            else{
                if( isset($request->should_verifiy_phone) && $request->should_verifiy_phone){
                    if ($user->phone_verified_at == null) {
                        \DB::beginTransaction();
                        $response = $this->resendVerificationToken($request)->getData();
                        if (!$response->status) {
                            \DB::rollBack();
                            return $this->commonService->getGeneralErrorResponse('Something went wrong while sending verification token SMS');
                        }
                        \DB::commit();
                        $data = $response->data;
                        return $this->commonService->getSuccessResponse('New Verification Code sent via SMS', $data);
                    }
                }
            }

            // dd($request->all(), $user->getAttributes());
            // dd($user->deleted_at);
            if($user->deleted_at != null){
                return $this->commonService->getGeneralErrorResponse('Information provided is invalid or user is deleted');
            }

            Auth::loginUsingId($user->id);
            if (Auth::check()) {
                $result = $this->authService->createAuthorizationToken($request, $user);
                if (!$result['status']) {
                    return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
                }
                $data = [];
                $data = array_merge($data, $result['data']);
                $data['user'] = User::where('id', $request->user()->id)->with('profile')->first();

                return $this->commonService->getSuccessResponse('Logged in Successfully', $data);
            } else {
                return $this->commonService->getGeneralErrorResponse('Internal Server Error');
            }
        }
    }

    /**
     * Verify a user and log him in
     *
     * @param Request $request
     * @return void
     */
    public function verifyUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'activation_code' => 'required',

            'email' => 'required_without:phone_number', // email or username
            'phone_number' => 'required_without:email',
            'phone_code' => 'required_without:email', // basically country code
        ]);

        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate user and validation token against given info
        $result = $this->authService->verifyUser($request);
        if (!$result['status']) {
            if ($result['exceptionCode'] == 404) {
                return $this->commonService->getNoRecordFoundResponse('Invalid or Expired Information Provided');
            } else {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
        }
        $user = $result['data'];

        if ($user->deleted_at != null) {
            return $this->commonService->getGeneralErrorResponse('Information provided is invalid or user is deleted');
        }
        Auth::loginUsingId($user->id);

        if (Auth::check()) {
            $result = $this->authService->createAuthorizationToken($request, $user);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $data = [];
            $data = array_merge($data, $result['data']);
            $data['user'] = User::where('id', $request->user()->id)->with('profile')->first();

            return $this->commonService->getSuccessResponse('Verified Successfully', $data);
        }
        else{
            return $this->commonService->getGeneralErrorResponse('Internal Server Error');
        }


    }

    /**
     * Resend Verification Token
     *
     * @param Request $request
     * @return void
     */
    public function resendVerificationToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required_without:phone_number|email',
            'phone_number' => 'required_without:email',
            'phone_code' => 'required_without:email', // basically country code
        ]);

        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }


        // get user based on email|phone
        $result = $this->authService->checkUser($request);
        if (!$result['status']) {
            if ($result['exceptionCode'] == 404) {
                return $this->commonService->getNoRecordFoundResponse('User Not Found');
            } else {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
        }
        $user = $result['data'];

        // get verification code based on email|phone
        if (isset($request->email) && $request->email != '') {
            $verificationModel = AuthVerification::where('email', $request->email)->first();
        } else {
            $verificationModel = AuthVerification::where('phone', $request->phone_code . $request->phone_number)->first();
        }

        // create existing verification code and delete old one
        if (null != $verificationModel) {
            $verificationModel->delete();
        }
        $code = mt_rand(1000, 9999);

        $result = $this->authService->sendVerificationToken($user, $code, $request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }

        $data['code'] = $code;
        // $data['user'] = $user;
        // return $data;
        return $this->commonService->getSuccessResponse('Verification Token Resent Successfully.', $data);
    }

    /**
     * Forgot Password
     *
     * @param Request $request
     * @return void
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required_without:phone_number|email',
            'phone_number' => 'required_without:email',
            'phone_code' => 'required_without:email', // basically country code
        ]);

        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // Validate User
        $result = $this->authService->checkUser($request);
        if (!$result['status']) {
            if ($result['exceptionCode'] == 404) {
                return $this->commonService->getNoRecordFoundResponse('User Not Found');
            } else {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
        }
        $user = $result['data'];

        // its a social media based user with no password
        if ($user->is_social && null == $user->password) {
            return $this->commonService->getGeneralErrorResponse('Please update your password at your Social media', null);
        }

        // generate reset password token
        $result = $this->authService->generatePasswordResetToken($request);
        if (!$result['status']) {
            if ($result['exceptionCode'] == 404) {
                return $this->commonService->getNoRecordFoundResponse('Invalid or Expired information provided');
            } else {

                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
        }
        $resetTokenModel = $result['data'];

        // send Email or message
        if (isset($request->email) && $request->email != '') {
            // send email
            $result = $this->commonService->sendResetPasswordEmail($user->email, 'Reset Password', 'authall::email_template.forgot_password', ['code' => $resetTokenModel->token]);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
        } else {
            // send Message
            $result = $this->commonService->sendResetPasswordMessage($request->phone_code . $request->phone_number, 'Reset Password', $resetTokenModel->token);
            if(!$result['status']){
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
        }

        $data['code'] = $resetTokenModel->token;
        $data['user'] = $user;
        return $this->commonService->getSuccessResponse('Reset Passwrd Code Sent Successfully', $data);
    }

    /**
     * Validate OTP token if it exists or not
     *
     * @param Request $request
     * @return void
     */
    public function validateAuthToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required_without:phone_number|email',
            'code' => 'required',
        ]);

        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        $result = $this->authService->matchExistingPasswordResetToken($request);
        if (!$result['status']) {
            if ($result['exceptionCode'] == 404) {
                return $this->commonService->getNoRecordFoundResponse('Invalid OTP Provided');
            } else {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
        }

        return $this->commonService->getSuccessResponse('Verified Successfully');
    }

    /**
     * Reset Password
     *
     * @param Request $request
     * @return void
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required_without:phone_number|email',
            'new_password' => 'required|string|min:8',
            'code' => 'required',
            'phone_number' => 'required_without:email',
            'phone_code' => 'required_without:email', // basically country code
        ]);

        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // Validate User
        $result = $this->authService->resetPassword($request);
        if (!$result['status']) {
            if ($result['exceptionCode'] == 404) {
                return $this->commonService->getNoRecordFoundResponse('User or Code Not Found');
            } else {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
        }
        $data['user'] = $result['data'];
        return $this->commonService->getSuccessResponse('Password updated Successfully', $data);
    }

    /**
     * Update Password
     *
     * @param Request $request
     * @return void
     */
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string|min:8',
            'new_password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // dd($request->user());
        if(null == $request->user()){
            return $this->commonService->getNoRecordFoundResponse('Please login or User is invalid');
        }
        $user = $request->user();

        $request->merge(['user_id' => $user->id]);
        $result = $this->authService->updatePassword($request);

        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $data['user'] = $user;
        return $this->commonService->getSuccessResponse('Password Updated Successfully', $data);

    }

    /**
     * Logout user from application
     *
     * @param Request $request
     * @return void
     */
    public function signout(Request $request)
    {
        if ($request->user() == null) {
            return $this->commonService->getNoRecordFoundResponse('Invalid User');
        } else {
            $token = $request->user()->token();
            // \Auth::logout();
            $token->revoke();
            return $this->commonService->getSuccessResponse('Logged out Successfully');
        }
    }
}
