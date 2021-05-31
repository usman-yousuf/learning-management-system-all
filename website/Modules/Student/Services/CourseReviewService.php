<?php

namespace Modules\Student\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Common\Entities\Stats;
use Modules\Student\Entities\Review;

class CourseReviewService
{

    /**
     * Check if an Course Review Exists given ID
     *
     * @param Integer $id
     * @return void
     */
    public function getCourseReviewById($id)
    {
        $model =  Review::where('id', $id)->first();
        if(null == $model){
            return \getInternalErrorResponse('No Course Review Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check and fetch and Course Review against given ID
     *
     * @param Integer $id
     * @return void
     */
    public function checkCourseReviewById($id)
    {
        $model =  Review::where('id', $id)->first();
        if(null == $model){
            return getInternalErrorResponse('No Course Review Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check if an Course Review Exists against given $request->course_review_uuid
     *
     * @param Request $request
     * @return void
     */
    public function checkCourseReview(Request $request)
    {
        $model = Review::where('uuid', $request->review_uuid)->with(['student','course'])->first();
        if (null == $model) {
            return getInternalErrorResponse('No Course Review Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Get an Course Review against given UUID
     *
     * @param Request $request
     * @return void
     */
    public function getCourseReview(Request $request)
    {
        $model = Review::where('uuid', $request->review_uuid)->with(['student','course'])->first();
        return getInternalSuccessResponse($model);
    }

    /**
     * Delete an Course Review by given UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteCourseReview(Request $request)
    {
        $model = Review::where('uuid', $request->review_uuid)->first();
        $model_stats = Stats::orderBy('DESC');

        if (null == $model) {
            return getInternalErrorResponse('No Course Review Found', [], 404, 404);
        }

        try{
            $model->delete();

            $model_stats->total_rater_count -= 1;
            $model_stats->total_rating_count -= 1;

        }
        catch(\Exception $ex)
        {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode(), 500);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Get Course Review based on filters
     *
     * @param Request $request
     * @return void
     */
    public function getCourseReviews(Request $request)
    {
        $models = Review::orderBy('created_at');

        //course_Review_uuid
        if(isset($request->review_uuid) && ('' != $request->review_uuid)){
            $models->where('uuid', $request->review_uuid);
        }

        //course_uuid
        // dd($request->course_id);
        if(isset($request->course_id) && ('' != $request->course_id)){
            $models->where('course_id', $request->course_id);
        }

        //student_id
        if(isset($request->student_id) && ('' != $request->student_id)){
            $models->where('student_id', $request->student_id);
        }

        // star_rating
        if (isset($request->star_rating) && ('' != $request->star_rating)) {
            $models->where('star_rating', '=', "{$request->star_rating}");
        }

        // body
        if (isset($request->body) && ('' != $request->body)) {
            $models->where('body', 'LIKE', "%{$request->body}%");
        }

        $cloned_models = clone $models;
        if(isset($request->offset) && isset($request->limit)){
            $models->offset($request->offset)->limit($request->limit);
        }

        $data['reviews'] = $models->with(['student','course'])->get();
        $data['total_count'] = $cloned_models->count();

        return getInternalSuccessResponse($data);
    }

    /**
     * Add|Update Course Review
     *
     * @param Request $request
     * @param Integer $course_review_id
     * @return void
     */
    public function addUpdateCourseReview(Request $request, $course_review_id = null)
    {
        if (null == $course_review_id) {
            $model = new Review();
            $model->uuid = \Str::uuid();
            $model->created_at = date('Y-m-d H:i:s');
        } else {
            $model = Review::where('id', $course_review_id)->first();
            $model_stats = Stats::orderBy('DESC');

        }
        $model->updated_at = date('Y-m-d H:i:s');
        $model->course_id = $request->course_id;
        $model->student_id = $request->student_id;
        $model->star_rating = $request->star_rating;
        $model->body = $request->body;

        
        //update rater count and rating count
        $model_stats->total_rater_count += 1;
        $model_stats->total_rating_count += 1;

        try {
            $model->save();
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }
}
