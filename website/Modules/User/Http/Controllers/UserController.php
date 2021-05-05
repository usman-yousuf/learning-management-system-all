<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Common\Services\CommonService;
use Modules\User\Services\ProfileService;
use Modules\User\Services\UserService;

class UserController extends Controller
{
    private $commonService;
    private $userService;
    private $profileService;

    public function __construct(CommonService $commonService, UserService $userService, ProfileService $profileService)
    {
        $this->commonService = $commonService;
        $this->userService = $userService;
        $this->profileService = $profileService;
    }

    public function updateprofileSetting(Request $request)
    {
        if ($request->getMethod() == 'GET') {
            return view('user::profile_setting', []);
        } else { // its a post call
            DB::beginTransaction();
            $result = $this->userService->addUpdateUser($request, $request->user()->id);
            if(!$result['status']){
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $user = $result['data'];
            $request->merge(['user_id' => $user->id]);
            // dd($request->user()->profile_id);
            $result = $this->profileService->addUpdateProfile($request, $request->user()->profile_id);
            DB::rollback();
            dd($request->all(), $result['data']);
            DB::commit();

            // update my profile
            // update my address
            // update my education
            // update my experience
            // update my interests
            // update my bank details
        }
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
