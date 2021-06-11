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

    public function approveTeacher(Request $request)
    {
        $request->merge([
            // 'profile_uuid' => 'ae0e275e-525c-4fe7-838a-7c4aa72ec577',
            'teacher_uuid' => '21df577e-7c5a-42c5-9cc1-286ee8e5f2ba'
        ]);
        $apiResponse = $this->userController->approveTeacher($request)->getData();
        if ($apiResponse->status) {
            $data = $apiResponse->data;
            return $this->commonService->getSuccessResponse('Admin approved you Successfully', $data);
        }
        return json_encode($apiResponse);

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
