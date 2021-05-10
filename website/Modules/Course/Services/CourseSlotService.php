<?php

namespace Modules\Course\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Course\Entities\CourseSlot;

class CourseContentService
{

    /**
     * Check if an Course Slot Exists given ID
     *
     * @param Integer $id
     * @return void
     */
    public function getCourseSlotById($id)
    {
        $model =  CourseSlot::where('id', $id)->first();
        if(null == $model){
            return \getInternalErrorResponse('No Course Slot Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check and fetch and Course Slot against given ID
     *
     * @param Integer $id
     * @return void
     */
    public function checkCourseSlotById($id)
    {
        $model =  CourseSlot::where('id', $id)->first();
        if(null == $model){
            return getInternalErrorResponse('No Course SLot Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check if an Course SLot Exists against given $request->course_slot_uuid
     *
     * @param Request $request
     * @return void
     */
    public function checkCourseSLot(Request $request)
    {
        $model = CourseSlot::where('uuid', $request->course_slot_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No Address Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Get an Course slot against given UUID
     *
     * @param Request $request
     * @return void
     */
    public function getCourseSlot(Request $request)
    {
        $model = CourseSlot::where('uuid', $request->course_slot_uuid)->first();
        return getInternalSuccessResponse($model);
    }

    /**
     * Delete an Course Slot by given UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteCourseSlot(Request $request)
    {
        $model = CourseSlot::where('uuid', $request->course_slot_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No Course Outline Found', [], 404, 404);
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
     * Get Course Slot based on filters
     *
     * @param Request $request
     * @return void
     */
    public function getCourseSlots(Request $request)
    {
        $models = CourseSlot::orderBy('created_at');

        if(isset($request->course_uuid) && ('' != $request->course_uuid)){
            $models->where('course_uuid', $request->course_uuid);
        }

        // slot_start
        if(isset($request->slot_start) && ('' != $request->slot_start)){
            $models->where('slot_start', '=', "%{$request->slot_start}%");
        }

        // slot_end
        if (isset($request->slot_end) && ('' != $request->slot_end)) {
            $models->where('slot_end', '=', "%{$request->slot_end}%");
        }

        // day_nums
        if (isset($request->day_nums) && ('' != $request->day_nums)) {
            $models->where('day_nums', '=', "%{$request->day_nums}%");
        }

        $cloned_models = clone $models;
        if(isset($request->offset) && isset($request->limit)){
            $models->offset($request->offset)->limit($request->limit);
        }

        $data['course_slots'] = $models->get();
        $data['total_count'] = $cloned_models->count();

        return getInternalSuccessResponse($data);
    }

    /**
     * Add|Update Course Slots
     *
     * @param Request $request
     * @param Integer $course_slot_id
     * @return void
     */
    public function addUpdateCourseOultine(Request $request, $course_slot_id = null)
    {
        if (null == $course_slot_id) {
            $model = new CourseSlot();
            $model->uuid = \Str::uuid();
            $model->created_at = date('Y-m-d H:i:s');
        //course_uuid

        } else {
            $model = CourseSlot::where('id', $course_slot_id)->first();
        }
        $model->updated_at = date('Y-m-d H:i:s');

        $model->slot_start = $request->slot_start;
        $model->slot_end = $request->slot_end;
        $model->day_nums = $request->day_nums;
        
        try {
            $model->save();
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }
}
