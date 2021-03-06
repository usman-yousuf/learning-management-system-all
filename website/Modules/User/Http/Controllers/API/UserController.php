<?php

namespace Modules\User\Http\Controllers\API;

use App\Http\Controllers\Controller;
// use App\Services\AddressService;
// use App\Services\CategoryService;
// use App\Services\CommonService;
// use App\Services\HealthMatrixService;
// use App\Services\InsuranceService;
// use App\Services\LifeStyleService;
// use App\Services\ProfileService;
// use App\Services\UploadedMediaService;
// use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Modules\Common\Services\CommonService;
use Modules\Common\Services\StatsService;
use Modules\Course\Services\CourseDetailService;
use Modules\User\Services\ProfileService;
use Modules\User\Services\UserService;


class UserController extends Controller
{
    public $commonService;
    public $userService;
    public $statsService;
    public $profileService;

    public function __construct(CommonService $commonService, UserService $userService, ProfileService $profileService, StatsService $statsService)
    {
        $this->commonService = $commonService;
        $this->userService = $userService;
        $this->profileService = $profileService;
        $this->statsService = $statsService;
    }

    /**
     * Get Application Stats for Admin Dashboard
     *
     * @param Request $request
     *
     * @return void
     */
    public function getAdminDashboardData(Request $request)
    {
        $request->merge(['profile_uuid' =>  $request->user()->profile->uuid]);
        $result = $this->profileService->checkAdmin($request);
        if (!$result['status']) {
            return $this->commonService->getNotAuthorizedResponse('You are not Authorized to perform this action');
        }

        $result = $this->statsService->getAdminDashboardStats($request);
        if (!$result['status']) {
            return $this->commonService->getNotAuthorizedResponse('You are not Authorized to perform this action');
        }
        $stats = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $stats);
    }

    /**
     * get User Details
     *
     * @param Request $request
     * @return void
     */
    public function getUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_uuid' => 'string|min:8|exists:users,uuid',
        ]);

        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        $result = $this->userService->getUser($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }

        $data['user'] = $result['data'];
        return $this->commonService->getSuccessResponse('Success', $data);
    }

        /**
     * Delete User by uuid
     *
     * @param Request $request
     *
     * @return void
     */

    public function deleteUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_uuid' => 'required|exists:users,uuid',
        ]);

        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }
        $result = $this->userService->deleteUser($request);
        if(!$result['status'])
        {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }

        $delete_User = $result['data'];
        return $this->commonService->getSuccessResponse('Record Deleted Successfully', []);


    }

    /**
     * Get Profile Details
     *
     * @param Request $request
     *
     * @return void
     */
    public function getProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_uuid' => 'required|exists:profiles,uuid',
        ]);

        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        $result = $this->profileService->getProfile($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }

        $data['profile'] = $result['data'];
        return $this->commonService->getSuccessResponse('Success', $data);
    }

    /**
     * List Profiles based on filters
     *
     * @param Request $request
     *
     * @return void
     */
    public function listProfiles(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'profile_type' => 'string|in:doctor,patient',
        //     'status' => 'string|in:active,inactive',
        //     'profile_uuid' => 'string|exists:profile,uuid',

        // ]);

        // if ($validator->fails()) {
        //     $data['validation_error'] = $validator->getMessageBag();
        //     return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        // }

        // setup user_id to get only relevant dat only for normal users and not for admin
        if(!isset($request->is_admin) || (!$request->is_admin)){
            if(isset($request->user_uuid) && ('' != $request->user_uuid))
            {
                $result = $this->userService->getUser($request);
                if(!$result['status'])
                {
                    return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
                }
                $user= $result['data'];
                $request->merge(['user_id' => $user->id]);
            }
        }

        $result = $this->profileService->listProfiles($request);

        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }

        $data = $result['data'];
        return $this->commonService->getSuccessResponse('Success', $data);
    }

    public function deleteProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_uuid' => 'required|exists:profiles,uuid',
        ]);

        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }
        $result = $this->profileService->deleteProfile($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }

        $data['profile'] = $result['data'];
        return $this->commonService->getSuccessResponse('Record Deleted Successfully', []);

    }

    // /**
    //  * Switch Active Profile
    //  *
    //  * @param Request $request
    //  *
    //  * @return Array[][] $jsonResponse
    //  */
    public function switchProfile(Request $request)
    {
        $user = $request->user();
        $current_profile_id = $request->user()->active_profile_id;

        $switch_profile = Profile::where('user_id', $user->id)->where('id', '<>', $current_profile_id)->get();
        // dd($switch_profile->count());
        if ($switch_profile->count()) {
            $switch_profile_id = $switch_profile[0]->id;
            $user->active_profile_id = $switch_profile_id;

            if ($user->save()) {

                $current_user = User::where('id', $user->id)->with('profile')->first();
                $request->user()->active_profile_id = $switch_profile_id;
                $request->user()->save();

                $data['profile'] = Profile::where('id', $switch_profile_id)->with('user')->first();

                return sendSuccess('Profile Switched successfully.', $data);
            }

            return sendError('There is some problem, Please Try Again.', null);
        }
        return sendError('User Profile to switch does not exist.', null);
    }

    /**
     * Approve a teacher - ADMIN ROLE ONLY
     *
     * @param Request $request
     *
     * @return Array[][] $jsonResponse
     */
    public function approveTeacher(Request $request)
    {
        //  dd($request->all());
        $validator = Validator::make($request->all(), [
            'teacher_uuid' => 'exists:profiles,uuid',
        ]);

        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        //check if logged in user is Admin
        // dd($request->user()->profile->id);
        $request->merge(['profile_uuid' =>  $request->user()->profile->uuid]);
        $result = $this->profileService->checkAdmin($request);
        if(!$result['status'])
        {
            return $this->commonService->getNotAuthorizedResponse('You are not Authorized to perform this action');
        }

        //check if the teacher id is valid
        $request->merge(['profile_uuid' => $request->teacher_uuid]);
        $result = $this->profileService->checkTeacher($request);
        if(!$result['status'])
        {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $data = $result['data'];
        $tech_id = $data->id;
        // $request->merge(['teach_id', $data->id]);

        //Approve
        \DB::beginTransaction();
        $result = $this->profileService->approveTeacher($request, $tech_id);
        if(!$result['status'])
        {
            \DB::rollback();
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $approved = $result['data'];
        \DB::commit();

        return $this->commonService->getSuccessResponse('Teacher Approved Successfully', $approved);

    }

    /**
     * Reject a teacher - ADMIN ROLE ONLY
     *
     * @param Request $request
     *
     * @return Array[][] $jsonResponse
     */
    public function rejectTeacher(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'teacher_uuid' => 'exists:profiles,uuid',
            'reason' => 'string',
        ]);

        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        //check if logged in user is Admin
        // dd($request->user()->profile->id);
        $request->merge(['profile_uuid' =>  $request->user()->profile->uuid]);
        $result = $this->profileService->checkAdmin($request);
        if (!$result['status']) {
            return $this->commonService->getNotAuthorizedResponse('You are not Authorized to perform this action');
        }

        // check if the teacher id is valid
        $request->merge(['profile_uuid' => $request->teacher_uuid]);
        $result = $this->profileService->checkTeacher($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $data = $result['data'];
        $teacher_id = $data->id;
        // $request->merge(['teach_id', $data->id]);

        //Approve
        \DB::beginTransaction();
        $result = $this->profileService->rejectTeacher($request, $teacher_id);
        if (!$result['status']) {
            \DB::rollback();
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $rejected = $result['data'];
        \DB::commit();

        return $this->commonService->getSuccessResponse('Teacher Rejected Successfully', $rejected);
    }
}
