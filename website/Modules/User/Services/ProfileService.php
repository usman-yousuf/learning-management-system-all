<?php

namespace Modules\User\Services;

use Modules\User\Entities\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\User\Entities\ProfileMeta;

class ProfileService
{
    public $relations;
    public $teacher_relations;

    public function __construct()
    {
        $this->relations = [
            'user',
            'address',
            // 'healthMatrix',
            // 'lifeStyle',
            // 'insurance',
            //  'meta',
        ];

        $this->student_relations = [
            // 'profileLabTests',
            // 'studentPrescriptions',
        ];

        $this->teacher_relations = [
            // 'ProfileCertifications',
            // 'teacherPrescriptions',
            // 'teacherReviews',
            // 'category'
        ];
          
    }
 
    /**
     * get Profile By ID
     *
     * @param Integer $profile_id
     * @return void
     */
    public function getProfileById($profile_id)
    {
        $model = Profile::where('id', $profile_id)->first();

        if (null == $model) {
            return getInternalErrorResponse('No Record Found', [], 404, 404);
        }

        // handle relations
        $relations = $this->relations;
        if($model->profile_type == 'teacher'){
            $relations = array_merge($relations, $this->teacher_relations);
        }
        else{
            $relations = array_merge($relations, $this->student_relations);
        }
        $model = Profile::where('id', $profile_id)->with($relations)->first();

        return getInternalSuccessResponse($model);
    }

    /**
     * Get User Details
     *
     * @param Request $request
     * @return void
     */
    public function getProfile(Request $request)
    {
        // logout user if is deleted
        if ($request->user()->profile == null) {
            $authCtrlObj = new AuthApiController();
            $result = $authCtrlObj->signout($request)->getData();
            if($result->status){
                return getInternalErrorResponse('Session Expired');
            }else{
                return getInternalErrorResponse('Something went wrong logging out the user');
            }
        }

        $uuid = ( isset($request->profile_uuid) && ('' != $request->profile_uuid) )? $request->profile_uuid : $request->user()->profile->uuid;
        $model = Profile::where('uuid', $uuid)->first();

        // handle relations
        $relations = $this->relations;
        if ($model->profile_type == 'teacher') {
            $relations = array_merge($relations, $this->teacher_relations);
        } else {
            $relations = array_merge($relations, $this->student_relations);
        }
        $model = Profile::where('uuid', $uuid)->with($relations)->first();

        return getInternalSuccessResponse($model);
    }

    /**
     * Validate a User Existence
     *
     * @param Request $request
     * @return void
     */
    public function checkProfile(Request $request)
    {
        // logout user if is deleted
        if ($request->user()->profile == null) {
            $authCtrlObj = new AuthApiController();
            $result = $authCtrlObj->signout($request)->getData();
            if ($result->status) {
                return getInternalErrorResponse('Session Expired');
            } else {
                return getInternalErrorResponse('Something went wrong logging out the user');
            }
        }

        // $uuid = (isset($request->profile_uuid) && ('' != $request->profile_uuid)) ? $request->profile_uuid : $request->user()->profile->uuid;
        $model = Profile::where('uuid', $request->profile_uuid)->first();

        if (null == $model) {
            return getInternalErrorResponse('No Record Found', [], 404, 404);
        }

        return getInternalSuccessResponse($model);
    }

    /**
     * Validate a Teacher Existence
     *
     * @param Request $request
     * @return void
     */
    public function checkTeacher(Request $request)
    {
        // logout user if is deleted
        if ($request->user()->profile == null) {
            $authCtrlObj = new AuthApiController();
            $result = $authCtrlObj->signout($request)->getData();
            if ($result->status) {
                return getInternalErrorResponse('Session Expired');
            } else {
                return getInternalErrorResponse('Something went wrong logging out the user');
            }
        }

        // $uuid = (isset($request->profile_uuid) && ('' != $request->profile_uuid)) ? $request->profile_uuid : $request->user()->profile->uuid;
        $model = Profile::where('uuid', $request->profile_uuid)->where('profile_type', 'teacher')->first();
        if (null == $model) {
            return getInternalErrorResponse('No Record Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Validate a Student Existence
     *
     * @param Request $request
     * @return void
     */
    public function checkStudent(Request $request)
    {
        //  dd($request->profile_uuid);
        // logout user if is deleted
        if ($request->user()->profile == null) {
            $authCtrlObj = new AuthApiController();
            $result = $authCtrlObj->signout($request)->getData();
            if ($result->status) {
                return getInternalErrorResponse('Session Expired');
            } else {
                return getInternalErrorResponse('Something went wrong logging out the user');
            }
        }
        // $uuid = (isset($request->profile_uuid) && ('' != $request->profile_uuid)) ? $request->profile_uuid : $request->user()->profile->uuid;
        $model = Profile::where('uuid', $request->profile_uuid)->where('profile_type', 'student')->first();
        if (null == $model) {
            return getInternalErrorResponse('No Record Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Add|Update Profile
     *
     * @param Request $request
     * @param Integer $profile_id
     * @return void
     */
    public function addUpdateProfile(Request $request, $profile_id = null)
    {
        if (null == $profile_id) {
            $model = new Profile();
            $model->uuid = \Str::uuid();
            $model->created_at = date('Y-m-d H:i:s');
            $model->user_id = $request->user_id;
            $model->profile_type = (isset($request->profile_type) && ('' != $request->profile_type)) ? $request->profile_type : 'student';
        } else {
            $model = Profile::where('id', $profile_id)->first();
        }
        $model->first_name = $request->first_name;
        $model->last_name = (isset($request->last_name) && ('' != $request->last_name))? $request->last_name : '';
        $model->updated_at = date('Y-m-d H:i:s');

        if(isset($request->dob) && ('' != $request->dob)){ // dob
            $model->dob = $request->dob;
        }
        if (isset($request->interests) && ('' != $request->interests)) { // interests
            $model->interests = $request->interests;
        }
        if (isset($request->gender) && ('' != $request->gender)) { // gender
            $model->gender = $request->gender;
        }
        if (isset($request->position) && ('' != $request->position)) { // position
            $model->position = $request->position;
        }
        if(isset($request->phone_code) && ('' != $request->phone_code)){ // phone_code
            $model->phone_code = $request->phone_code;
        }
        if (isset($request->phone_number) && ('' != $request->phone_number)) { // phone_number
            $model->phone_number = $request->phone_number;
        }

        // phone 2
        if(isset($request->phone_code_2) && ('' != $request->phone_code_2)){ // phone_code_2
            $model->phone_code_2 = $request->phone_code_2;
        }
        if (isset($request->phone_number_2) && ('' != $request->phone_number_2)) { // phone_number_2
            $model->phone_number_2 = $request->phone_number_2;
        }
        if (isset($request->profile_image) && ('' != $request->profile_image)) { // profile_image
            $model->profile_image = $request->profile_image;
        }
        try {
            $model->save();
            // dd($model->getAttributes());
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            // dd($ex);
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }

    /**
     * get the IDs of teachers against given category_id
     *
     * @param Integer $category_id
     * @return void
     */
    public function getTeacherIdsByCategory($category_id)
    {
        $result = $this->getTeachersByCategory($category_id);
        if (!$result['status']) {
            return $result;
        }
        $list = $result['data'];
        $teachers = $list['models'];
        $total = $list['total_models'];

        $ids = [];
        if($total){
            foreach($teachers as $item){
                $ids[] = $item->id;
            }
        }
        return getInternalSuccessResponse($ids);
    }

    /**
     * get Teacher Ids against given category. Alos with availibility on given time period
     *
     * @param Request $request
     * @return void
     */
    public function getTeacherIdsByCategoryAndAvailableTimeslot($request)
    {
        $result = $this->getTeachersByCategory($request->category_id);
        if (!$result['status']) {
            return $result;
        }
        $list = $result['data'];
        $teachers = $list['models'];
        $total = $list['total_models'];

        $ids = [];
        if ($total) {
            foreach ($teachers as $item) {
                $teacher_start_time = $item->start_time;
                $teacher_end_time = $item->end_time;

                $isTimeInRange = isTimeInRange($teacher_start_time, $teacher_end_time, $request->requested_start_time);

                if($isTimeInRange){ // available teachers only
                    $ids[] = $item->id;
                }
            }
        }
        return getInternalSuccessResponse($ids);
    }

    /**
     * Get Teachers by Category ID
     *
     * @param Request $category_id
     * @return void
     */
    public function getTeachersByCategory($category_id)
    {
        $request = app('request');
        $request->merge([
            'profile_type' => 'teacher',
            'category_id' => $category_id
        ]);
        $result = $this->listProfiles($request);
        if(!$result['status']){
            return $result;
        }
        return $result;
    }

    /**
     * List Profiles based on different filters
     *
     * @param Request $request
     * @return void
     */
    public function listProfiles(Request $request)
    {
        // \DB::enableQueryLog();
        // dd($request->profile_uuid);
        // get All or user specif models
        $models = Profile::orderBy('id', 'DESC');

        // profile_uuid based models
        // if(isset($request->profile_uuid) && ('' != $request->profile_uuid)){
        //     $models->where('uuid', 'LIKE',  "%{$request->profile_uuid}%");
        // }
        
        // profile_uuid based models
         if(isset($request->user_id) && ('' != $request->user_id)){
            $models->where('id', $request->user_id);
        }
        // profile_type based models
        if (isset($request->profile_type) && ('' != $request->profile_type)) {
            $models->where('profile_type', $request->profile_type);
        }

        // status based models
        if (isset($request->status) && ('' != $request->status)) {
            $models->where('status', $request->status);
        }

        // interests based models
        if (isset($request->interests) && ('' != $request->interests)) {
            $models->where('interests','LIKE', "%{$request->interests}%");
        }
        // category based models
        // if (isset($request->category_id) && ('' != $request->category_id)) {
        //     $models->where('category_id', $request->category_id);
        // }

        // start_time and end time
        // if (isset($request->start_date) && isset($request->end_date)) {
        //     $models->where('start_time', '>=', $request->start_time);
        //     $models->where('start_time', '<=', $request->end_time);
        // }

        // ethicity based models
        // if (isset($request->ethicity) && ('' != $request->ethicity)) {
        //     $models->where('ethicity', $request->ethicity);
        // }

        // gender based models
        if (isset($request->gender) && ('' != $request->gender)) {
            $models->where('gender', $request->gender);
        }

        // first_name based models
        if (isset($request->first_name) && ('' != $request->first_name)) {
            $models->where('first_name', 'LIKE', "%{$request->first_name}%");
        }

        // last_name based models
        if (isset($request->last_name) && ('' != $request->last_name)) {
            $models->where('last_name', 'LIKE', "%{$request->last_name}%");
        }

         // phone_code based models
        if (isset($request->phone_code) && ('' != $request->phone_code)) {
            $models->where('phone_code', $request->phone_code);
        }

        // phone_number based models
        if (isset($request->phone_number) && ('' != $request->phone_number)) {
            $models->where('phone_number', $request->phone_number);
        }


        // is_convicted based models
        // if (isset($request->is_convicted) && ('' != $request->is_convicted)) {
        //     $models->where('is_convicted', $request->is_convicted);
        // }

        // dob based models
        if (isset($request->dob) && ('' != $request->dob)) {
            $models->where('dob', $request->dob);
        }

        
        // bulk_profile_ids
        if (isset($request->bulk_profile_ids) && (!empty($request->bulk_profile_ids))) {
            $models->whereIn('id', $request->bulk_profile_ids);
        }


        // license_authority based models
        // if (isset($request->license_authority) && ('' != $request->license_authority)) {
        //     $models->where('license_authority', $request->license_authority);
        // }

        // apply pagination filter
        $cloned_models = clone $models;
        if (isset($request->offset) && isset($request->offset)) {
            $models->offset($request->offset)->limit($request->limit);
        }

        $rows = $models->get();

        // dd(\DB::getQueryLog());
        $models = [];
        if($rows->count()){
            foreach ($rows as $item) {
                // handle relations
                $relations = $this->relations;
                if ($item->profile_type == 'teacher') {
                    $relations = array_merge($relations, $this->teacher_relations);
                } else if($item->profile_type == 'student') {
                    $relations = array_merge($relations, $this->student_relations);
                }
                $models[] = Profile::where('id', $item->id)->with($relations)->first();
            }
        }


        $data = [
            'models' => $models,
            'total_models' => $cloned_models->count(),
        ];
        return getInternalSuccessResponse($data);
    }

    /**
     * delete Profile 
     *
     * @param Request $request
     * @return void
     */
    public function deleteProfile(Request $request)
    {
         // logout user if is deleted
         if ($request->user()->profile == null) {
            $authCtrlObj = new AuthApiController();
            $result = $authCtrlObj->signout($request)->getData();
            // $result = $this->authCtrlObj->signout($request)->getData();
            if($result->status){
                return getInternalErrorResponse('Session Expired');
            }else{
                return getInternalErrorResponse('Something went wrong logging out the user');
            }
        }
        $uuid = ( isset($request->profile_uuid) && ('' != $request->profile_uuid) )? $request->profile_uuid : $request->user()->profile->uuid;
        $model = Profile::where('uuid', $uuid)->first();
        
        if (null == $model) {
            return getInternalErrorResponse('No Profile Found', [], 404, 404);
        }

        try{
            $model->delete();
        }
        catch(\Exception $ex)
        {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode(), 500);
        }
        return getInternalSuccessResponse($model);
    }


    //     /**
    //  * Check if an Student Exists against given $request->student_uuid
    //  *
    //  * @param Request $request
    //  * @return void
    //  */
    // public function checkStudent(Request $request)
    // {
    //     // dd($request->all());
    //     $model = Profile::where('uuid', $request->student_uuid)->where('profile_type', 'student')-> with('user')->first();
    //     // dd($model);
    //     if (null == $model) {
    //         return getInternalErrorResponse('No Student Found', [], 404, 404);
    //     }
    //     return getInternalSuccessResponse($model);
    // }

    public function updateCourseStudentMetaStats($student_id,$mode)
    {
        $model = ProfileMeta::where('profile_id', $student_id)->first();
        
        if(null == $model)
        {
            $model = new ProfileMeta();
            $model->profile_id = $student_id;
            $model->uuid = \Str::uuid();
            $model->created_at = date('Y-m-d H:i:s');
            $model->total_courses_count = 1;
        }
        else {
            // $model->total_courses_count += 1;
            $model->total_courses_count  = ($mode == 'add')? + $model->total_courses_count + 1 : $model->total_courses_count -1;

        }
        $model->updated_at = date('Y-m-d H:i:s');

        // save stats
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
