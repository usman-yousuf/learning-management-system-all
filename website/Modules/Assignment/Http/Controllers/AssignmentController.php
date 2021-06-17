<?php

namespace Modules\Assignment\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\Assignment\Http\Controllers\API\AssignmentController as APIAssignmentController;
use Modules\Common\Services\CommonService;

class AssignmentController extends Controller
{
    private $commonService;
    private $assignmentController;

    public function __construct(CommonService $commonService, APIAssignmentController $assignmentController)
    {
        $this->commonService = $commonService;
        $this->assignmentController = $assignmentController;
    }

    /**
     * Add update an Assigment
     *
     * @param Request $request
     *
     * @return void
     */
    public function addUpdateAssignment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_uuid' => 'required|exists:courses,uuid',
            'course_slot_uuid' => 'required|exists:course_slots,uuid',
            'total_marks' => 'required|numeric',
            'due_date' => 'required|date_format:Y-m-d',
            'assignment_title' => 'required',
            'media_1' => 'required|string',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        if(isset($request->assignment_uuid) && ('' == $request->assignment_uuid)){
            unset($request['assignment_uuid']);
        }
        $request->merge([
            'extended_date' => $request->due_date,
            'assignee_uuid' => $request->user()->profile->uuid,
            'title' => $request->assignment_title,
        ]);

        $ctrlObj = $this->assignmentController;
        $apiResponse = $ctrlObj->addUpdateAssignment($request)->getData();
        if ($apiResponse->status) {
            $data = $apiResponse->data;
            return $this->commonService->getSuccessResponse('Asssignment Saved Successfully', $data);
        }
        return json_encode($apiResponse);
    }













    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('assignment::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('assignment::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('assignment::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('assignment::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
