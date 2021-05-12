<?php

namespace Modules\Course\Http\Controllers\API;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Common\Services\CommonService;
use Modules\Course\Services\CourseCategoryService;
use Modules\Course\Services\CourseDetailService;
use Modules\User\Services\ProfileService;

class CourseDetailController extends Controller
{
    private $commonService;
    private $courseDetailService;
    private $profileService;
    private $categoryService;
    public function __construct(CommonService $commonService, CourseDetailService $courseDetailService, ProfileService $profileService, CourseCategoryService $categoryService )
    {
        $this->commonService = $commonService;
        $this->courseDetailService = $courseDetailService;
        $this->profileService = $profileService;
        $this->categoryService = $categoryService;
    }

    /**
     * Get a Course Detail based on uuid
     *
     * @param Request $request
     * @return void
     */
    public function getCourseDetail(Request $request)
    {  
        $validator = Validator::make($request->all(), [
            'courses_uuid' => 'required',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and fetch Course Detail
        $result = $this->courseDetailService->checkCourseDetail($request);
        if(!$result['status']){
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $courseDetail = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $courseDetail);
    }

    /**
     * Delete an Course Detail by UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteCourseDetail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'courses_uuid' => 'required|exists:courses,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and delete an Address
        $result = $this->courseDetailService->deleteCourseDetail($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $courseDetail = $result['data'];

        return $this->commonService->getSuccessResponse('Record Deleted Successfully', []);
    }

    /**
     * Get Course Detail based on given filters
     *
     * @param Request $request
     * @return void
     */
    public function getCourseDetails(Request $request)
    {   
        if(isset($request->teacher_uuid) && ('' != $request->teacher_uuid)){
            $result = $this->profileService->getProfile($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $teacher = $result['data'];
            $request->merge(['teacher_id' => $teacher->id]);
        }

        if(isset($request->course_category_uuid) && (''!= $request->course_category_uuid)) {
            $result = $this->categoryService->checkCourseCateogry($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $category = $result['data'];
            $request->merge(['course_category_id' => $category->id]);
        }
        $result = $this->courseDetailService->getCourses($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $courseDetail = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $courseDetail);
    }

    /**
     * Add|Update Course Detail
     *
     * @param Request $request
     * @return void
     */
    public function updateCourseDetail(Request $request)
    {
        dd($this->courseDetailService->model()->teacher_id);
        $validator = Validator::make($request->all(), [
            'courses_uuid' => 'exists:courses,uuid',
            'course_category_uuid' => 'exists:course_categories,uuid',
            'teacher_uuid' => 'exists:profiles,uuid',
            'description' => 'string',
            'course_image' => 'string',
            'nature' => 'required|string',

            'is_course_free' => 'required|in:0,1',
            'is_handout_free' => 'required|in:0,1',
            'price_usd' => 'required|numeric',
            'discount_usd' => 'required|numeric',
            'price_pkr' => 'required|numeric',
            'discount_pkr' => 'required|numeric',
            'total_duration' => 'required|numeric',
            'is_approved' => 'required|in:0,1',

        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // get/set course_category_uuid
         $uuid = $this;
        if(isset($request->category_uuid) && ('' != $request->category_uuid)){
            $uuid = $request->category_uuid;
        }
        $request->merge(['course_category_uuid' => $uuid]);

        // valiadate and fetch course_category_id
        $result = $this->categoryService->getCourseCategory($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $course_category = $result['data'];
        $request->merge(['course_category_id' => $course_category->id]);

         // get/set teacher_uuid
        //  $uuid = $request->user()->teacher->uuid;
        $uuid = null;
         if(isset($request->teacher_uuid) && ('' != $request->teacher_uuid)){
             $uuid = $request->teacher_uuid;
         }
         $request->merge(['teacher_uuid' => $uuid]);
 
         // valiadate and fetch teacher_id
         $result = $this->profileService->getProfile($request);
         if (!$result['status']) {
             return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
         }
         $teacher = $result['data'];
         $request->merge(['teacher_id' => $teacher->id]);

        // find address by uuid if given
        $course_id = null;
        if(isset($request->course_uuid) && ('' != $request->course_uuid)){
            $result = $this->courseDetailService->checkCourseDetail($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $course = $result['data'];
            $address_id = $course->id;
        }

        $result = $this->courseDetailService->addUpdateCourseDetail($request, $address_id);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $course = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $course);
    }
}
