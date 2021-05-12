<?php

namespace Modules\Course\Http\Controllers\API;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Common\Services\CommonService;
use Modules\Course\Services\CourseCategoryService;

class CategoryController extends Controller
{
    private $commonService;
    private $categoryService;

    public function __construct(CommonService $commonService, CourseCategoryService $categoryService)
    {
        $this->commonService = $commonService;
        $this->categoryService = $categoryService;
    }

    /**
     * Get a Single Category based on uuid
     *
     * @param Request $request
     * @return void
     */
    public function getCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_category_uuid' => 'required',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and fetch an Category
        $result = $this->categoryService->checkCourseCateogry($request);
        if(!$result['status']){
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $category = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $category);
    }

    /**
     * Delete an Category by UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_category_uuid' => 'required|exists:course_categories,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and delete an Category
        $result = $this->categoryService->deleteCourseCategory($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $category = $result['data'];

        return $this->commonService->getSuccessResponse('Record Deleted Successfully', []);
    }

    /**
     * Get Category based on given filters
     *
     * @param Request $request
     * @return void
     */
    public function getCategories(Request $request)
    {
        if(isset($request->course_category_uuid) && ('' != $request->course_category_uuid)){
            $result = $this->categoryService->checkCourseCateogry($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            // $profile = $result['data'];
            // $request->merge(['profile_id' => $profile->id]);
        }

        $result = $this->categoryService->getCourseCategories($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $category = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $category);
    }

    /**
     * Add|Update an Category
     *
     * @param Request $request
     * @return void
     */
    public function updateCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_category_uuid' => 'exists:course_categories,uuid',
            'name' => 'required|string',
            'description' => 'required:string',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // find category by uuid if given
        $course_category_id = null;
        if(isset($request->course_category_uuid) && ('' != $request->course_category_uuid)){
            $result = $this->categoryService->checkCourseCateogry($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $category = $result['data'];
            $course_category_id = $category->id;
        }

        $result = $this->categoryService->addUpdateCategory($request, $course_category_id);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $category = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $category);
    }
}
