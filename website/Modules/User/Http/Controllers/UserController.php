<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Common\Services\CommonService;
use Modules\User\Services\AddressService;
use Modules\User\Services\EducationService;
use Modules\User\Services\ProfileService;
use Modules\User\Services\UserService;

class UserController extends Controller
{
    private $commonService;
    private $userService;
    private $profileService;
    private $addressService;
    private $educationService;

    public function __construct(CommonService $commonService
        , UserService $userService
        , ProfileService $profileService
        , AddressService $addressService
        , EducationService $educationService
    )
    {
        $this->commonService = $commonService;
        $this->userService = $userService;
        $this->profileService = $profileService;
        $this->addressService = $addressService;
        $this->educationService = $educationService;
    }

    public function updateprofileSetting(Request $request)
    {
        if ($request->getMethod() == 'GET') {
            $user = $request->user();
            $profile = $request->user()->profile;
            $address = $profile->address;
            $education = $profile->education;
            // dd($education);

            return view('user::profile_setting', [
                'user'=> $user
                , 'profile'=>$profile
                , 'address' => $address
                , 'education' => $education
            ]);
        } else { // its a post call
            DB::beginTransaction();
            if(isset($request->interests)){
                $request->merge(['interests' => implode(',', $request->interests)]);
            }
            // update user
            $result = $this->userService->addUpdateUser($request, $request->user()->id);
            if(!$result['status']){
                DB::rollback();
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $user = $result['data'];
            $request->merge(['user_id' => $user->id]);

            // update profile
            $result = $this->profileService->addUpdateProfile($request, $request->user()->profile_id);
            if (!$result['status']) {
                DB::rollback();
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $profile = $result['data'];
            $request->merge(['profile_id' => $profile->id]);

            // address
            $request->merge([
                'completed_at' => $request->completion_year,
                'image' => $request->certification_image,
                'title' => $request->degree_title
            ]);

            // validate education
            $education_id = null;
            if(isset($request->education_uuid) && ('' != $request->education_uuid)){
                $result = $this->educationService->checkEducation($request);
                if (!$result['status']) {
                    DB::rollback();
                    return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
                }
                $education = $result['data'];
                $education_id = $education->id;
            }

            // update education
            $result = $this->educationService->addUpdateEducation($request, $education_id);
            if (!$result['status']) {
                DB::rollback();
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $profile = $result['data'];
            $request->merge(['profile_id' => $profile->id]);
            // call api

            // DB::rollback();
            dd($request->all(), $result['data']);

            // update my profile
            // update my address
            // update my education
            // update my experience
            // update my bank details


            // DB::commit();
            // success response
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
