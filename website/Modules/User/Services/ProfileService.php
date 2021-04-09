<?php

namespace Modules\User\Services;

use Modules\AuthAll\Http\Controllers\API\AuthApiController;
use Modules\User\Entities\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileService
{
    public $relations;
    public $doctor_relations;

    public function __construct()
    {
        $this->relations = [
            'user',
            'address',
            'healthMatrix',
            'lifeStyle',
            'insurance',
            'meta',
        ];

        $this->patient_relations = [
            'profileLabTests',
            'patientPrescriptions',
        ];

        $this->doctor_relations = [
            'ProfileCertifications',
            // 'doctorPrescriptions',
            'doctorReviews',
            'category'
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
        if($model->profile_type == 'doctor'){
            $relations = array_merge($relations, $this->doctor_relations);
        }
        else{
            $relations = array_merge($relations, $this->patient_relations);
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
        if ($model->profile_type == 'doctor') {
            $relations = array_merge($relations, $this->doctor_relations);
        } else {
            $relations = array_merge($relations, $this->patient_relations);
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
     * Validate a Doctor Existence
     *
     * @param Request $request
     * @return void
     */
    public function checkDoctor(Request $request)
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
        $model = Profile::where('uuid', $request->profile_uuid)->where('profile_type', 'doctor')->first();
        if (null == $model) {
            return getInternalErrorResponse('No Record Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Validate a Patient Existence
     *
     * @param Request $request
     * @return void
     */
    public function checkPatient(Request $request)
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
        $model = Profile::where('uuid', $request->profile_uuid)->where('profile_type', 'patient')->first();
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
        } else {
            $model = Profile::where('id', $profile_id)->first();
        }
        $model->first_name = $request->first_name;
        $model->last_name = (isset($request->last_name) && ('' != $request->last_name))? $request->last_name : '';
        $model->updated_at = date('Y-m-d H:i:s');

        $model->profile_type = (isset($request->profile_type) && ('' != $request->profile_type)) ? $request->profile_type : 'patient';

        // update dob
        if(isset($request->dob) && ('' != $request->dob)){ // dob
            $model->dob = $request->dob;
        }
        // update gender
        if (isset($request->gender) && ('' != $request->gender)) { // gender
            $model->gender = $request->gender;
        }
        // update ethnicity
        if (isset($request->ethnicity) && ('' != $request->ethnicity)) { // ethnicity
            $model->ethnicity = $request->ethnicity;
        }

        if (isset($request->language) && ('' != $request->language)) { // language
            $model->language = $request->language;
        }
        if (isset($request->nok) && ('' != $request->nok)) { // next of kin
            $model->nok = $request->nok;
        }
        if (isset($request->license_number) && ('' != $request->license_number)) { // license_number
            $model->license_number = $request->license_number;
        }
        if (isset($request->license_authority) && ('' != $request->license_authority)) { // license_authority
            $model->license_authority = $request->license_authority;
        }
        if (isset($request->license_organization) && ('' != $request->license_organization)) { // license_organization
            $model->license_organization = $request->license_organization;
        }
        if (isset($request->social_security) && ('' != $request->social_security)) { // social_security
            $model->social_security = $request->social_security;
        }
        if (isset($request->specialization) && ('' != $request->specialization)) { // specialization
            $model->specialization = $request->specialization;
        }
        if (isset($request->age) && ('' != $request->age)) { // age
            $model->age = $request->age;
        }
        if (isset($request->bio) && ('' != $request->bio)) { // bio
            $model->bio = $request->bio;
        }
        if (isset($request->position) && ('' != $request->position)) { // position
            $model->position = $request->position;
        }
        if(isset($request->category_id) && ('' != $request->category_id)){ // category_id
            $model->category_id = $request->category_id;
        }

        if(isset($request->phone_code) && ('' != $request->phone_code)){ // phone_code
            $model->phone_code = $request->phone_code;
        }
        if (isset($request->phone_number) && ('' != $request->phone_number)) { // phone_number
            $model->phone_number = $request->phone_number;
        }
        if (isset($request->is_policy_holder) && ('' != $request->is_policy_holder)) { // is_policy_holder
            $model->is_policy_holder = $request->is_policy_holder;
        }
        if (isset($request->profile_picture) && ('' != $request->profile_picture)) { // profile_picture
            $model->profile_image = $request->profile_picture;
        }
        if (isset($request->emergency_contact) && ('' != $request->emergency_contact)) { // emergency_contact
            $model->emergency_contact = $request->emergency_contact;
        }

        // doctors specific data

        if (isset($request->organizations) && ('' != $request->organizations)) { // organizations
            $model->organizations = $request->organizations;
        }
        if (isset($request->start_time) && ('' != $request->start_time)) { // start_time
            $model->start_time = $request->start_time;
        }
        if (isset($request->end_time) && ('' != $request->end_time)) { // end_time
            $model->end_time = $request->end_time;
        }
        if (isset($request->is_convicted) && ('' != $request->is_convicted)) { // is_convicted
            $model->is_convicted = $request->is_convicted;
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
     * get the IDs of doctors against given category_id
     *
     * @param Integer $category_id
     * @return void
     */
    public function getDoctorIdsByCategory($category_id)
    {
        $result = $this->getDoctorsByCategory($category_id);
        if (!$result['status']) {
            return $result;
        }
        $list = $result['data'];
        $doctors = $list['models'];
        $total = $list['total_models'];

        $ids = [];
        if($total){
            foreach($doctors as $item){
                $ids[] = $item->id;
            }
        }
        return getInternalSuccessResponse($ids);
    }

    /**
     * get Doctor Ids against given category. Alos with availibility on given time period
     *
     * @param Request $request
     * @return void
     */
    public function getDoctorIdsByCategoryAndAvailableTimeslot($request)
    {
        $result = $this->getDoctorsByCategory($request->category_id);
        if (!$result['status']) {
            return $result;
        }
        $list = $result['data'];
        $doctors = $list['models'];
        $total = $list['total_models'];

        $ids = [];
        if ($total) {
            foreach ($doctors as $item) {
                $doctor_start_time = $item->start_time;
                $doctor_end_time = $item->end_time;

                $isTimeInRange = isTimeInRange($doctor_start_time, $doctor_end_time, $request->requested_start_time);

                if($isTimeInRange){ // available doctors only
                    $ids[] = $item->id;
                }
            }
        }
        return getInternalSuccessResponse($ids);
    }

    /**
     * Get Doctors by Category ID
     *
     * @param Request $category_id
     * @return void
     */
    public function getDoctorsByCategory($category_id)
    {
        $request = app('request');
        $request->merge([
            'profile_type' => 'doctor',
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
        // get All or user specif models
        $models = Profile::orderBy('id', 'DESC');

        // profile_type based models
        if (isset($request->profile_type) && ('' != $request->profile_type)) {
            $models->where('profile_type', $request->profile_type);
        }

        // status based models
        if (isset($request->status) && ('' != $request->status)) {
            $models->where('status', $request->status);
        }

        // category based models
        if (isset($request->category_id) && ('' != $request->category_id)) {
            $models->where('category_id', $request->category_id);
        }

        // start_time and end time
        if (isset($request->start_date) && isset($request->end_date)) {
            $models->where('start_time', '>=', $request->start_time);
            $models->where('start_time', '<=', $request->end_time);
        }

        // ethicity based models
        if (isset($request->ethicity) && ('' != $request->ethicity)) {
            $models->where('ethicity', $request->ethicity);
        }

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

        // is_convicted based models
        if (isset($request->is_convicted) && ('' != $request->is_convicted)) {
            $models->where('is_convicted', $request->is_convicted);
        }

        // dob based models
        if (isset($request->dob) && ('' != $request->dob)) {
            $models->where('dob', $request->dob);
        }

        // license_number based models
        if (isset($request->license_number) && ('' != $request->license_number)) {
            $models->where('license_number', $request->license_number);
        }

        // license_authority based models
        if (isset($request->license_authority) && ('' != $request->license_authority)) {
            $models->where('license_authority', $request->license_authority);
        }

        // apply pagination filter
        $cloned_models = clone $models;
        if (isset($request->offset) && isset($request->offset)) {
            $models->offset($request->offset)->limit($request->limit);
        }

        $rows = $models->get();
        $models = [];
        if($rows->count()){
            foreach ($rows as $item) {
                // handle relations
                $relations = $this->relations;
                if ($item->profile_type == 'doctor') {
                    $relations = array_merge($relations, $this->doctor_relations);
                } else {
                    $relations = array_merge($relations, $this->patient_relations);
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

}
