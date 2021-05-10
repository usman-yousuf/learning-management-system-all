<?php

namespace Modules\Course\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Course\Entities\CourseCategory;

class CourseCategoryService
{

    /**
     * Check if an Course category Exists given ID
     *
     * @param Integer $id
     * @return void
     */
    public function getCourseCategoryById($id)
    {
        $model =  CourseCategory::where('id', $id)->first();
        if(null == $model){
            return getInternalErrorResponse('No Course category Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check and fetch and Course category against given ID
     *
     * @param Integer $id
     * @return void
     */
    public function checkCourseCategoryById($id)
    {
        $model =  CourseCategory::where('id', $id)->first();
        if(null == $model){
            return getInternalErrorResponse('No Course Category Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check if an Course category Exists against given $request->course_category_uuid
     *
     * @param Request $request
     * @return void
     */
    public function checkCourseCateogry(Request $request)
    {
        $model = CourseCategory::where('uuid', $request->course_category_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No Course category Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Get Course category against given UUID
     *
     * @param Request $request
     * @return void
     */
    public function getCourseCategory(Request $request)
    {
        $model = CourseCategory::where('uuid', $request->course_category_uuid)->first();
        return getInternalSuccessResponse($model);
    }

    /**
     * Delete an Course category by given UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteCourseCategory(Request $request)
    {
        $model = CourseCategory::where('uuid', $request->course_category_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No Course category Found', [], 404, 404);
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
     * Get Course category based on filters
     *
     * @param Request $request
     * @return void
     */
    public function getCourseCategories(Request $request)
    {
        $models = CourseCategory::orderBy('created_at');

        // name
        if(isset($request->name) && ('' != $request->name)){
            $models->where('name', 'LIKE', "%{$request->name}%");
        }

        // description
        if (isset($request->description) && ('' != $request->description)) {
            $models->where('description', 'LIKE', "%{$request->description}%");
        }
        
        $cloned_models = clone $models;
        if(isset($request->offset) && isset($request->limit)){
            $models->offset($request->offset)->limit($request->limit);
        }

        $data['course_categories'] = $models->get();
        $data['total_count'] = $cloned_models->count();

        return getInternalSuccessResponse($data);
    }

    /**
     * Add|Update Course category
     *
     * @param Request $request
     * @param Integer $course_category_id
     * @return void
     */
    public function addUpdateAddress(Request $request, $course_category_id = null)
    {
        if (null == $course_category_id) {
            $model = new CourseCategory();
            $model->uuid = \Str::uuid();
            $model->created_at = date('Y-m-d H:i:s');
            //course_uuid
        } else {
            $model = CourseCategory::where('id', $course_category_id)->first();
        }
        $model->updated_at = date('Y-m-d H:i:s');

        $model->name = $request->name;
        $model->description = $request->description;

        try {
            $model->save();
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }
}
