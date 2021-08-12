<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\Common\Services\CommonService;
use Modules\User\Http\Controllers\API\UserController;

class TeacherController extends Controller
{
    private $commonService;
    private $userController;

    public function __construct(CommonService $commonService, UserController $userController)
    {
        $this->commonService = $commonService;
        $this->userController = $userController;
    }

    /**
     * Approve a teacher
     *
     * @param String $uuid
     * @param Request $request
     *
     * @return void
     */
    public function approveTeacher($uuid, Request $request)
    {
        $request->merge(['teacher_uuid' => $uuid]);
        $apiResponse = $this->userController->approveTeacher($request)->getData();
        if ($apiResponse->status) {
            $data = $apiResponse->data;
            // dd($data);
            return $this->commonService->getSuccessResponse('Admin approved the Profile Successfully', $data);
        }
        // return json_encode($apiResponse);
        return $this->commonService->getProcessingErrorResponse($apiResponse->message, $apiResponse->data, $apiResponse->responseCode, $apiResponse->exceptionCode);
    }

    // admin reject teacher profile , does not approve teacher profile
    public function rejectTeacherProfile(Request $request)
    {
        $request->merge(['teacher_uuid' => $request->profile_uuid, 'reason' => $request->rejection_description]);
        $apiResponse = $this->userController->rejectTeacher($request)->getData();
        if ($apiResponse->status) {
            $data = $apiResponse->data;
            // dd($data);
            return $this->commonService->getSuccessResponse('Admin Rejected said Profile Successfully', $data);
        }
        // return json_encode($apiResponse);
        return $this->commonService->getProcessingErrorResponse($apiResponse->message, $apiResponse->data, $apiResponse->responseCode, $apiResponse->exceptionCode);

        // // dd($request->all());
        // // Validate User
        // $result = $this->authService->checkUser($request);
        // if (!$result['status']) {
        //     if ($result['exceptionCode'] == 404) {
        //         return $this->commonService->getNoRecordFoundResponse('User Not Found');
        //     } else {
        //         return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        //     }
        // }
        // $user = $result['data'];
        // $email = $user->email;

        // $reason = $request->reason;

        // // send email
        // $result = $this->commonService->sendRejectionTeacherApprovedEmail($user->email, 'Rejection Email', 'authall::email_template.admin_reject_teacher_approval', ['email' => $email, 'description' => $reason]);
        // if (!$result['status']) {
        //     return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        // }

        // // return response
        // $data['user'] = $user;
        // $data['description'] = $request->rejection_description;
        // return $this->commonService->getSuccessResponse('Admin Reject your Profile', $data);
    }


    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('user::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('user::create');
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
        return view('user::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('user::edit');
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
