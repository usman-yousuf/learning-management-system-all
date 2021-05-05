<?php

namespace Modules\User\Services;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function getUserById($id)
    {
        $user = User::where('id', $id)->with('profile')->first();
        return getInternalSuccessResponse($user);
    }

    /**
     * Get User Details
     *
     * @param Request $request
     * @return void
     */
    public function getUser(Request $request)
    {
        $uuid = ( isset($request->user_uuid) && ('' != $request->user_uuid) )? $request->user_uuid : $request->user()->uuid;

        $user = User::where('uuid', $uuid)->with('profile')->first();
        return getInternalSuccessResponse($user);
    }

    /**
     * List all
     *
     * @param Request $request
     * @return void
     */
    public function checkSocialUser(Request $request)
    {
        $user = User::where('social_id', $request->social_id)->where('social_type', $request->social_type);
        // if($request->social_type != 'apple'){
        //     $user = $user->where('social_email', $request->social_email);
        // }
        $user = $user->first();

        if(null == $user){
            return getInternalErrorResponse('No Record Found', [], 404, 404);
        }
        else{
            return getInternalSuccessResponse($user);
        }

    }

    /**
     * Add|Update User
     *
     * @param Request $request
     * @param Integer $user_id
     * @return void
     */
    public function addUpdateUser(Request $request, $user_id = null)
    {
        if (null == $user_id) {
            $model = new User();
            $model->uuid = \Str::uuid();
            $model->username = (isset($request->username) && ('' != $request->username)) ? $request->username : \Str::uuid(); // username
            $model->created_at = date('Y-m-d H:i:s');
        } else {
            $model = User::where('id', $user_id)->first();
        }

        $model->is_social = $request->is_social;
        if($model->is_social){
            $model->email_verified_at = date('Y-m-d H:i:s');
            $model->social_id = $request->social_id;
            $model->social_type = $request->social_type;
            $model->social_email = (isset($request->social_email) && ('' != $request->social_email))? $request->social_email : '';
        }
        else{
            $model->email = $request->email;
            $model->password = \Hash::make($request->password);
            if (isset($request->email_verified_at) && ('' != $request->email_verified_at)) { // email_verified_at
                $model->email_verified_at = $request->email_verified_at;
            }
        }
        $model->profile_type = (isset($request->profile_type) && ('' != $request->profile_type)) ? $request->profile_type : 'student';
        $model->remember_token = (isset($request->remember_token) && ('' != $request->remember_token)) ? $request->remember_token : false;
        $model->updated_at = date('Y-m-d H:i:s');

        try {
            $model->save();
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }


    /**
     * Switch User Profile
     *
     * @param Integer $user_id
     * @param Integer $profile_id
     * @param Integer $profile_type
     *
     * @return void
     */
    public function switchUserProfile($user_id, $profile_id, $profile_type)
    {
        $model = User::where('id', $user_id)->first();

        $model->profile_id = $profile_id;
        $model->profile_type = $profile_type;

        $model->updated_at = date('Y-m-d H:i:s');

        try {
            $model->save();
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }

    /**
     * Update User Password
     *
     * @param Integer $user_id
     * @param String $new_password
     * @param String $old_password
     *
     * @return void
     */
    public function updatePassword($user_id, $new_password, $old_password = null)
    {
        $model = User::where('id', $user_id)->first();

        if(null == $model){
            return getInternalErrorResponse('No Record Found', [], 404, 404);
        }

        if(null != $old_password){
            if(!Hash::check($old_password, $model->password)){ // password and old passwords donot mactch
                return getInternalErrorResponse('Password does not match the old password', [], 422, 422);
            }
        }
        $model->password = Hash::make($new_password);

        try {
            $model->save();
            // dd($model->getAttributes());
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            // dd($ex);
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }

}
