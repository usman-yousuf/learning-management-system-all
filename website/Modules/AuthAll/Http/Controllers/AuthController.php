<?php

namespace Modules\AuthAll\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\AuthAll\Http\Controllers\API\AuthApiController;
use Modules\AuthAll\Services\AuthService;
use Modules\Common\Services\CommonService;
use Modules\Common\Services\StatsService;

class AuthController extends Controller
{
    public function __construct(CommonService $commonService, AuthService $authService)
    {
        // $this->statsService = new StatsService();
        $this->commonService = $commonService;
        $this->authService = $authService;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        return view('authall::index');
    }

    /**
     * Register a new User to application
     *
     * @param Request $request
     * @return void
     */
    public function signup(Request $request)
    {
        if ($request->getMethod() == 'GET') {
            return view('authall::registration');
        }
        else{ // its a post callback
            $rules = [
                'first_name' => 'required|min:3',
                'last_name' => 'required|min:1',
                'username' => 'required|string|min:3|unique:users,username',
                'email' => 'required|min:6|email',
                'password' => 'required|min:8|confirmed',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $data['validation_error'] = $validator->getMessageBag();
                return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
            }

            \DB::beginTransaction();
            $result = $this->authService->registerUser($request);
            if (!$result['status']) {
                \DB::rollBack();
                if ($result['exceptionCode'] == 404) {
                    return $this->commonService->getNoRecordFoundResponse('Invalid User or Password');
                } else {
                    return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
                }
            }
            \DB::commit();
            $data['user'] = $result['data'];
            return $this->commonService->getSuccessResponse('Account Created Successfully. <p>Please check your email for verification code</p>', $data);
        }
    }

    /**
     * Login a user
     *
     * @param Request $request
     * @return mixed view page for GET request and processing for post request
     */
    public function login(Request $request)
    {
        if ($request->getMethod() == 'GET') {
            return view('authall::login');
        } else { // its a post call
            $rules = [
                'email' => 'required|email',
                'password' => 'required|min:8',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $data['validation_error'] = $validator->getMessageBag();
                return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
            }

            $result = $this->authService->checkAuthUser($request);
            if (!$result['status']) {
                if ($result['exceptionCode'] == 404) {
                    return $this->commonService->getNoRecordFoundResponse('Invalid User or Password');
                } else {
                    return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
                }
            }
            $user = $result['data'];

            Auth::loginUsingId($user->id);
            if (Auth::check()) {
                return $this->commonService->getSuccessResponse('Logged in Successfully');
            } else {
                return $this->commonService->getGeneralErrorResponse('Internal Server Error');
            }
        }
    }

    /**
     * Forgot Password
     *
     * @param Request $request
     * @return void
     */
    public function forgotPassword(Request $request)
    {
        if ($request->getMethod() == 'GET') {
            return view('authall::forgot_password');
        }
        else {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                // 'email' => 'required|email|exists:users,email',
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

            // send email
            $result = $this->commonService->sendResetPasswordEmail($user->email, 'Reset Password', 'authall::email_template.forgot_password', ['code' => $resetTokenModel->token]);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }

            // return response
            $data['code'] = $resetTokenModel->token;
            $data['user'] = $user;
            $data['email'] = $request->email;
            return $this->commonService->getSuccessResponse('Reset Password Code Sent Successfully', $data);

        }
    }

    /**
     * Validate Password Code sent to Email
     *
     * @param Request $request
     * @return void
     */
    public function validatePasswordCode(Request $request)
    {
        if ($request->getMethod() == 'GET') {
            if(isset($request->email) && ('' != $request->email)){
                return view('authall::validate_code', ['email' => $request->email]);
            }
            $data['validation_error'] = ['email' => 'Email is Required'];
            return abort(422, 'Email is Required');

        } else { // its a post callback
            $validator = Validator::make($request->all(), [
                'activation_code' => 'required',
                'email' => 'required|email',
            ]);

            if ($validator->fails()) {
                $data['validation_error'] = $validator->getMessageBag();
                return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
            }
            // process sent code along with user
            $request->merge(['code' =>$request->activation_code]);
            $result = $this->authService->verifyUser($request);
            \DB::beginTransaction();
            if(!$result['status']){
                \DB::rollBack();
                if ($result['exceptionCode'] == 404) {
                    return $this->commonService->getNoRecordFoundResponse('Invalid User or Code Provided');
                } else {
                    return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
                }
            }
            $user = $result['data'];
            $data['activation_code'] = $request->activation_code;
            $data['email'] = $request->email;

            Auth::loginUsingId($user->id);
            if (Auth::check()) {
                return $this->commonService->getSuccessResponse('User Verified Successfully', $data);
            } else {
                return $this->commonService->getGeneralErrorResponse('Internal Server Error');
            }
        }
    }

    /**
     * Resend Verificaion Token to said email
     *
     * @param Request $request
     *
     * @return void
     */
    public function resendVerificationCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required_without:phone_number|email',
        ]);

        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        $statsService = new StatsService();
        $authCtrlObj = new AuthApiController($this->commonService, $statsService, $this->authService);
        $response = $authCtrlObj->resendVerificationToken($request)->getData();

        if (!$response->status) {
            return $this->commonService->getProcessingErrorResponse($response->message, $response->data, 500, $response->exceptionCode);
        }
        return $this->commonService->getSuccessResponse('Verification Token Resent Successfully.', []);
    }

    /**
     * Set Password against givn email and verification Code
     *
     * @param Request $request
     * @return void
     */
    public function resetPassword(Request $request)
    {
        if ($request->getMethod() == 'GET') {

            if (!isset($request->email) || ('' == $request->email)) {
                $data['validation_error'] = ['email' => 'Email is Required'];
                return abort(422, 'Email is Required');
            }

            if (!isset($request->vcode) || ('' == $request->vcode)) {
                $data['validation_error'] = ['vcode' => 'Verfication Code is Required'];
                return abort(422, 'Verification Code is Required');
            }
            return view('authall::set_password', ['vcode' => $request->vcode, 'email' => $request->email]);
        }
        else{
            $validator = Validator::make($request->all(), [
                'code' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:8|confirmed',
            ]);

            if ($validator->fails()) {
                $data['validation_error'] = $validator->getMessageBag();
                return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
            }
            $request->merge(['new_password' => $request->password]);

            $result = $this->authService->resetPassword($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            return $this->commonService->getSuccessResponse('Password Applied Successfully.', []);
        }
    }

    /**
     * Log off a signed in user and then redirect him to login page
     *
     * @param Request $request
     * @return void
     */
    public function signout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login');
    }
}