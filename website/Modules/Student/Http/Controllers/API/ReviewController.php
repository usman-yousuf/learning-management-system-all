<?php

namespace Modules\Student\Http\Controllers\API;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Common\Services\CommonService;
use Modules\Course\Services\CourseDetailService;
use Modules\Student\Services\CourseReviewService;
use Modules\User\Services\ProfileService;

class ReviewController extends Controller
{
    private $commonService;
    private $courseDetailService;
    private $reviewService;
    private $profileService;

    public function __construct(CommonService $commonService, CourseReviewService $reviewService, CourseDetailService $courseDetailService, ProfileService $profileService )
    {
        $this->commonService = $commonService;
        $this->reviewService = $reviewService;
        $this->courseDetailService = $courseDetailService;
        $this->profileService = $profileService;
    }

    /**
     * Get a Single Course Review based on uuid
     *
     * @param Request $request
     * @return void
     */
    public function getReview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'review_uuid' => 'required',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and fetch Course Review
        $result = $this->reviewService->checkCourseReview($request);
        if(!$result['status']){
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $courseReview = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $courseReview);
    }

    /**
     * Delete Course Review  by UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteReview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'review_uuid' => 'required|exists:reviews,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and delete Course Review
        $result = $this->reviewService->deleteCourseReview($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $course_review = $result['data'];

        return $this->commonService->getSuccessResponse('Record Deleted Successfully', []);
    }

    /**
     * Get Course Reviews based on given filters
     *
     * @param Request $request
     * @return void
     */
    public function getReviews(Request $request)
    {
        if(isset($request->course_uuid) && ('' != $request->course_uuid)){
            $result = $this->courseDetailService->getCourseDetail($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $course = $result['data'];
            $request->merge(['course_id' => $course->id]);
        }

        //student_uuid
        if(isset($request->student_uuid) && ('' != $request->student_uuid)){
            $request->merge(['profile_uuid' => $request->student_uuid]);

            $result = $this->profileService->getProfile($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $student = $result['data'];
            $request->merge(['student_id' => $student->id]);
        }

        $result = $this->reviewService->getCourseReviews($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $course_slot = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $course_slot);
    }

    /**
     * Add|Update Course Review
     *
     * @param Request $request
     * @return void
     */
    public function updateReview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'review_uuid' => 'exists:reviews,uuid',
            'teacher_uuid' => 'exists:profiles,uuid',
            'course_uuid' => 'required',
            'star_rating' => 'required|integer',
            'body' => 'required|string',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // course_uuid
        if(isset($request->course_uuid) && ('' != $request->course_uuid)){
            $result = $this->courseDetailService->getCourseDetail($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $course = $result['data'];
            $request->merge(['course_id' => $course->id]);
        }

        //student_uuid
        if(isset($request->student_uuid) && ('' != $request->student_uuid)){
            $request->merge(['profile_uuid' => $request->student_uuid]);

            $result = $this->profileService->getProfile($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $student = $result['data'];
            $request->merge(['student_id' => $student->id]);
        }
        // find Course Review by uuid if given
        $course_review_id = null;
        if(isset($request->review_uuid) && ('' != $request->review_uuid)){
            $result = $this->reviewService->checkCourseReview($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $course_review = $result['data'];
            $course_review_id = $course_review->id;
        }

        $result = $this->reviewService->addUpdateCourseReview($request, $course_review_id);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $course_slot = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $course_slot);
    }
}
