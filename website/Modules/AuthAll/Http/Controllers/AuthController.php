<?php

namespace Modules\AuthAll\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use Modules\AuthAll\Services\AuthService;
use Modules\Common\Services\CommonService;

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

            // Auth::loginUsingId($user->id);
            // if (Auth::check()) {
            // } else {
            //     return $this->commonService->getGeneralErrorResponse('Internal Server Error');
            // }
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

    // public function resendVerificationToken(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required_without:phone_number|email',
    //         'phone_number' => 'required_without:email',
    //         'phone_code' => 'required_without:email', // basically country code
    //     ]);

    //     if ($validator->fails()) {
    //         $data['validation_error'] = $validator->getMessageBag();
    //         return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
    //     }


    //     // get user based on email|phone
    //     $result = $this->authService->checkUser($request);
    //     if (!$result['status']) {
    //         if ($result['exceptionCode'] == 404) {
    //             return $this->commonService->getNoRecordFoundResponse('User Not Found');
    //         } else {
    //             return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
    //         }
    //     }
    //     $user = $result['data'];

    //     // get verification code based on email|phone
    //     if (isset($request->email) && $request->email != '') {
    //         $veridicationModel = AuthVerification::where('email', $request->email)->first();
    //     } else {
    //         $veridicationModel = AuthVerification::where('phone', $request->phone_code . $request->phone_number)->first();
    //     }

    //     // create existing verification code and delete old one
    //     if (null != $veridicationModel) {
    //         $veridicationModel->delete();
    //     }
    //     $code = mt_rand(1000, 9999);

    //     $result = $this->authService->sendVerificationToken($user, $code, $request);
    //     if (!$result) {
    //         return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
    //     }

    //     $data['code'] = $code;
    //     $data['user'] = $user;
    //     // return $data;
    //     return $this->commonService->getSuccessResponse('Verification Token Resent Successfully.', $data);
    // }

    public function forgotPassword()
    {
        return view('authall::forgot_password');
    }

    // public function verifyUser(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'activation_code' => 'required',

    //         'email' => 'required_without:phone_number|email',
    //         'phone_number' => 'required_without:email',
    //         'phone_code' => 'required_without:email', // basically country code
    //     ]);

    //     if ($validator->fails()) {
    //         $data['validation_error'] = $validator->getMessageBag();
    //         return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
    //     }

    //     // validate user and validation token against given info
    //     $result = $this->authService->verifyUser($request);
    //     if (!$result['status']) {
    //         if ($result['exceptionCode'] == 404) {
    //             return $this->commonService->getNoRecordFoundResponse('Invalid or Expired Information Provided');
    //         } else {
    //             return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
    //         }
    //     }
    //     $user = $result['data'];

    //     Auth::loginUsingId($user->id);

    //     if (Auth::check()) {
    //         $result = $this->authService->createAuthorizationToken($request, $user);
    //         if (!$result['status']) {
    //             return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
    //         }
    //         $data = [];
    //         $data = array_merge($data, $result['data']);
    //         $data['user'] = User::where('id', $request->user()->id)->with('profile')->first();

    //         return $this->commonService->getSuccessResponse('Verified Successfully', $data);
    //     } else {
    //         return $this->commonService->getGeneralErrorResponse('Internal Server Error');
    //     }
    // }
    public function validatePasswordCode(Request $request)
    {
        if ($request->getMethod() == 'GET') {
            return view('authall::validate_code');
        } else { // its a post callback
            $validator = Validator::make($request->all(), [
                'activation_code' => 'required',
                'email' => 'required|email',
            ]);

            if ($validator->fails()) {
                $data['validation_error'] = $validator->getMessageBag();
                return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
            }
            dd($request->all());
        }
    }
    public function setPassword()
    {
        return view('authall::set_password');
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
