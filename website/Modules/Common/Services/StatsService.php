<?php

namespace Modules\Common\Services;

use Modules\Common\Entities\Stats;
use Illuminate\Http\Request;

class StatsService
{

    /**
     * Update New User Stats (Signup)
     *
     * @param Request $request
     * @return void
     */
    public function updateNewUserStats(Request $request)
    {
        // get|set model
        $model = Stats::whereNull('deleted_at')->orderBy('created_at', 'desc')->first();
        if(null == $model){
            $model = new Stats();
            $model->created_at = date('Y-m-d H:i:s');
        }
        $model->updated_at = date('Y-m-d H:i:s');

        // social based users count
        if($request->is_social){
            if($request->social_type == 'google'){
                $model->google_users_count += 1;
            }
            else if ($request->social_type == 'apple') {
                $model->apple_users_count += 1;
            }
            else if ($request->social_type == 'facebook') {
                $model->facebook_users_count += 1;
            }
            else if ($request->social_type == 'twitter') {
                $model->twitter_users_count += 1;
            }
        }

        // profile type based users count
        if (isset($request->profile_type)){
            if('teacher' == $request->profile_type) {
                $model->total_teachers_count += 1;
            }
            else if ('parent' == $request->profile_type) {
                $model->total_parents_count += 1;
            }
            else if ('student' == $request->profile_type) {
                $model->total_students_count += 1;
            }
        }

        // device types based stats
        if($request->device_type == 'web')
        {
            $model->web_users_count += 1;
        }
        else{
            if($request->device_type == 'andriod')
            {
                $model->andriod_users_count += 1;
            }
            else{
                $model->ios_users_count += 1;
            }
        }

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

    // /**
    //  * Add Appointment Stats
    //  *
    //  * @param Request $request
    //  * @return void
    //  */
    // public function addAppointmentStats(Request $request)
    // {
    //     // get|set model
    //     $model = Stats::whereNull('deleted_at')->orderBy('created_at', 'desc')->first();
    //     if (null == $model) {
    //         $model = new Stats();
    //         $model->created_at = date('Y-m-d H:i:s');
    //     }
    //     $model->updated_at = date('Y-m-d H:i:s');

    //     $model->total_appointments_count += 1;

    //     if (isset($request->consultancy_type) && ('video' == $request->consultancy_type)) {
    //         $model->total_call_appointments_count += 1;
    //     } else {
    //         $model->total_chat_appointments_count += 1;
    //     }
    //     $model->total_pending_appointments_count += 1;

    //     // save stats
    //     try {
    //         $model->save();
    //         // dd($model->getAttributes());
    //         return getInternalSuccessResponse($model);
    //     } catch (\Exception $ex) {
    //         // dd($ex);
    //         return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
    //     }
    // }

    // /**
    //  * Update Appointment Stats
    //  *
    //  * @param Request $request
    //  *
    //  * @return void
    //  */
    // public function updateAppointmentStats(Request $request)
    // {
    //     $model = Stats::whereNull('deleted_at')->orderBy('created_at', 'desc')->first();
    //     if (null == $model) {
    //         $model = new Stats();
    //         $model->created_at = date('Y-m-d H:i:s');
    //     }
    //     $model->updated_at = date('Y-m-d H:i:s');

    //     if(isset($request->status)){
    //         if ('completed' == $request->status) {
    //             $model->total_completed_appointments_count += 1;
    //         } else if ('cancelled' == $request->status) {
    //             $model->total_cancelled_appointments_count += 1;
    //         }
    //     }

    //     // save stats
    //     try {
    //         $model->save();
    //         // dd($model->getAttributes());
    //         return getInternalSuccessResponse($model);
    //     } catch (\Exception $ex) {
    //         // dd($ex);
    //         return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
    //     }
    // }
}
