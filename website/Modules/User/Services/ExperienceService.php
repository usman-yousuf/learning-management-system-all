<?php

namespace Modules\User\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\User\Entities\Experience;

class ExperienceService
{

    /**
     * Check if an Experience Exists given ID
     *
     * @param Integer $id
     * @return void
     */
    public function getExperienceById($id)
    {
        $experience =  Experience::where('id', $id)->first();
        if(null == $experience){
            return \getInternalErrorResponse('No Experience Found', [], 404, 404);
        }
        return getInternalSuccessResponse($experience);
    }

    /**
     * Check and fetch and Experience against given ID
     *
     * @param Integer $id
     * @return void
     */
    public function checkExperienceById($id)
    {
        $experience =  Experience::where('id', $id)->first();
        if(null == $experience){
            return getInternalErrorResponse('No Experience Found', [], 404, 404);
        }
        return getInternalSuccessResponse($experience);
    }

    /**
     * Check if an Experience Exists against given $request->experience_uuid
     *
     * @param Request $request
     * @return void
     */
    public function checkExperience(Request $request)
    {
        $experience = Experience::where('uuid', $request->experience_uuid)->first();
        if (null == $experience) {
            return getInternalErrorResponse('No Experience Found', [], 404, 404);
        }
        return getInternalSuccessResponse($experience);
    }

    /**
     * Get an Experience against given UUID
     *
     * @param Request $request
     * @return void
     */
    public function getExperience(Request $request)
    {
        $experience = Experience::where('uuid', $request->experience_uuid)->first();
        return getInternalSuccessResponse($experience);
    }

    /**
     * Delete an Experience by given UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteExperience(Request $request)
    {
        $experience = Experience::where('uuid', $request->experience_uuid)->first();
        if (null == $experience) {
            return getInternalErrorResponse('No Experience Found', [], 404, 404);
        }

        try{
            $experience->delete();
        }
        catch(\Exception $ex)
        {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode(), 500);
        }
        return getInternalSuccessResponse($experience);
    }

    /**
     * Get Experiencees based on filters
     *
     * @param Request $request
     * @return void
     */
    public function getExperiences(Request $request)
    {
        $models = Experience::orderBy('created_at');

        if(isset($request->profile_id) && ('' != $request->profile_id)){
            $models->where('profile_id', $request->profile_id);
        }

        // job_exp
        if(isset($request->job_exp) && ('' != $request->job_exp)){
            $models->where('job_exp', 'LIKE', "%{$request->job_exp}%");
        }

        // teaching_exp
        if (isset($request->teaching_exp) && ('' != $request->teaching_exp)) {
            $models->where('teaching_exp', 'LIKE', "%{$request->teaching_exp}%");
        }

        $cloned_models = clone $models;
        if(isset($request->offset) && isset($request->limit)){
            $models->offset($request->offset)->limit($request->limit);
        }

        $data['experiences'] = $models->get();
        $data['total_count'] = $cloned_models->count();

        return getInternalSuccessResponse($data);
    }

    /**
     * Add|Update Experience
     *
     * @param Request $request
     * @param Integer $experience_id
     * @return void
     */
    public function addUpdateExperience(Request $request, $experience_id = null)
    {
        if (null == $experience_id) {
            $model = new Experience();
            $model->uuid = \Str::uuid();
            $model->created_at = date('Y-m-d H:i:s');
            $model->profile_id = $request->user()->profile->id;
        } else {
            $model = Experience::where('id', $experience_id)->first();
        }
        $model->updated_at = date('Y-m-d H:i:s');

        $model->job_exp = $request->job_exp;
        $model->teaching_exp = $request->teaching_exp;

        if (isset($request->experience_image) && ('' != $request->experience_image)) { // image
            $model->image = $request->experience_image;
        }

        try {
            $model->save();
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }
}
