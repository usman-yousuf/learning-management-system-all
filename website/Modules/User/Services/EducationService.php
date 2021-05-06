<?php

namespace Modules\User\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\User\Entities\Education;

class EducationService
{

    /**
     * Check if an Education Exists given ID
     *
     * @param Integer $id
     * @return void
     */
    public function getEducationById($id)
    {
        $education =  Education::where('id', $id)->first();
        if(null == $education){
            return \getInternalErrorResponse('No Education Found', [], 404, 404);
        }
        return getInternalSuccessResponse($education);
    }

    /**
     * Check and fetch and Education against given ID
     *
     * @param Integer $id
     * @return void
     */
    public function checkEducationById($id)
    {
        $education =  Education::where('id', $id)->first();
        if(null == $education){
            return getInternalErrorResponse('No Education Found', [], 404, 404);
        }
        return getInternalSuccessResponse($education);
    }

    /**
     * Check if an Education Exists against given $request->education_uuid
     *
     * @param Request $request
     * @return void
     */
    public function checkEducation(Request $request)
    {
        $model = Education::where('uuid', $request->education_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No Education Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Get an Address against given UUID
     *
     * @param Request $request
     * @return void
     */
    public function getEducation(Request $request)
    {
        $model = Education::where('uuid', $request->education_uuid)->first();
        return getInternalSuccessResponse($model);
    }

    /**
     * Delete an Education by given UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteEducation(Request $request)
    {
        $model = Education::where('uuid', $request->education_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No Education Found', [], 404, 404);
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

    /**
     * Get Education based on filters
     *
     * @param Request $request
     * @return void
     */
    public function getEducations(Request $request)
    {
        $models = Education::orderBy('created_at');

        if(isset($request->profile_id) && ('' != $request->profile_id)){
            $models->where('profile_id', $request->profile_id);
        }

        // title
        if(isset($request->title) && ('' != $request->title)){
            $models->where('title', 'LIKE', "%{$request->title}%");
        }

        // completed_at
        if (isset($request->completed_at) && ('' != $request->completed_at)) {
            $models->where('completed_at', 'LIKE', "%{$request->completed_at}%");
        }

        // university
        if (isset($request->university) && ('' != $request->university)) {
            $models->where('university', 'LIKE', "%{$request->university}%");
        }

        $cloned_models = clone $models;
        if(isset($request->offset) && isset($request->limit)){
            $models->offset($request->offset)->limit($request->limit);
        }

        $data['educations'] = $models->get();
        $data['total_count'] = $cloned_models->count();

        return getInternalSuccessResponse($data);
    }

    /**
     * Add|Update Education
     *
     * @param Request $request
     * @param Integer $education_id
     * @return void
     */
    public function addUpdateEducation(Request $request, $education_id = null)
    {
        if (null == $education_id) {
            $model = new Education();
            $model->uuid = \Str::uuid();
            $model->created_at = date('Y-m-d H:i:s');
            $model->profile_id = $request->user()->profile->id;
        } else {
            $model = Education::where('id', $education_id)->first();
        }
        $model->updated_at = date('Y-m-d H:i:s');

        $model->title = $request->title;
        if (isset($request->image) && ('' != $request->image)) { // image
            $model->image = $request->image;
        }
        $model->completed_at = $request->completed_at;
        $model->university = $request->university;

        try {
            $model->save();
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }
}
