<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Common\Services\CommonService;
use Modules\User\Services\AddressService;
use Modules\User\Services\EducationService;
use Modules\User\Services\ExperienceService;
use Modules\User\Services\ProfileService;
use Modules\User\Services\UserBankService;
use Modules\User\Services\UserService;

class UserController extends Controller
{
    private $commonService;
    private $userService;
    private $profileService;
    private $addressService;
    private $educationService;
    private $userbankService;
    private $userexperience;

    public function __construct(CommonService $commonService
        , UserService $userService
        , ProfileService $profileService
        , AddressService $addressService
        , EducationService $educationService
        , UserBankService $userbankService
        , ExperienceService $userexperience
    )
    {
        $this->commonService = $commonService;
        $this->userService = $userService;
        $this->profileService = $profileService;
        $this->addressService = $addressService;
        $this->educationService = $educationService;
        $this->userbankService = $userbankService;
        $this->userexperience = $userexperience;
    }

    public function updateprofileSetting(Request $request)
    {
        if ($request->getMethod() == 'GET') {
            $user = $request->user();
            $profile = $request->user()->profile;
            $address = $profile->address;
            $education = $profile->education;
            $userBank = $profile->userBank;
            $experience = $profile->experience;
            //  dd($userBank);

            return view('user::profile_setting', [
                'user'=> $user
                , 'profile'=>$profile
                , 'address' => $address
                , 'education' => $education
                , 'userBank' => $userBank
                , 'experience' => $experience
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
            $result_profile = $this->profileService->addUpdateProfile($request, $request->user()->profile_id);
            if (!$result_profile['status']) {
                DB::rollback();
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $profile = $result_profile['data'];
            $request->merge(['profile_id' => $profile->id]);

            //validate address
            $address_id =null;
            if(isset($request->address_uuid) && ('' != $request->address_uuid))
            {
                $result = $this->addressService->checkAddress($request);
                if(!$result['status'])
                {
                    DB::rollback();
                    return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
                }
                $address = $result['data'];
                $address_id = $address->id;
            }

            //update address
            $result_address = $this->addressService->addUpdateAddress($request, $address_id);
            if(!$result_address['status'])
            {
                DB::rollback();
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $address = $result_address['data'];
            $request->merge([
                'address_id' => $address->id,
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
            $request->merge([
                'completed_at' => $request->completion_year,
                'image' => $request->certification_image,
                'title' => $request->degree_title,
            ]);
            $result_education = $this->educationService->addUpdateEducation($request, $education_id);
            if (!$result_education['status']) {
                DB::rollback();
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $education = $result_education['data'];
            $request->merge(['education_id' => $education->id]);







             //validation experience
            $experience_id = null;
            if(isset($request->experience_uuid)&& ('' != $request->experience_uuid ))
            {
                $result = $this->userexperience->checkExperience($request);
                if(!$result['status'])
                {
                    DB::rollback();
                    return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
                }
                $experience = $result['data'];
                $experience_id = $experience->id;
            }
            $request->merge([
                'job_exp' => $request->job_experience,
                'teaching_exp' => $request->teaching_experience
            ]);
            // update my experience
            $result_experience = $this->userexperience->addUpdateExperience($request, $experience_id);
            if(!$result_experience['status'])
            {
                DB::rollBack();
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $experinece = $result_experience['data'];
            $request->merge(['experience_id' => $experinece->id]);




            // validation bank details
            $user_bank_id = null;
            if(isset($request->user_bank_uuid)&& ('' != $request->user_bank_uuid ))
            {
                $result = $this->userbankService->checkUserBank($request);
                if(!$result['status'])
                {
                    DB::rollback();
                    return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
                }
                $user_bank = $result['data'];
                $user_bank_id = $user_bank->id;
            }

            // update my bank details
            $result_bank = $this->userbankService->addUpdateUserBank($request, $user_bank_id);
            if(!$result_bank['status'])
            {
                DB::rollBack();
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $user_bank = $result_bank['data'];
            $request->merge(['bank_id' => $user_bank->id]);


            $result = $this->profileService->getProfile($request);
            if (!$result_bank['status']) {
                DB::rollBack();
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $data = $result['data'];
            DB::commit();


            return $this->commonService->getSuccessResponse('profile Updated Successfully', $data);
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
