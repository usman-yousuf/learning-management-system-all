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

    public function signup()
    {
        return view('authall::registration');
    }
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
                // $data['validation_error'] = $validator->getMessageBag();
                return redirect()->route('login')->withErrors($validator)->withInput();
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
    public function forgotPassword()
    {
        return view('authall::forgot_password');
    }
    public function validatePasswordCode()
    {
        return view('authall::validate_code');
    }
    public function setPassword()
    {
        return view('authall::set_password');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('authall::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('authall::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('authall::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
