<?php

namespace Modules\Common\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Common\Http\Controllers\API\ReportController as APIReportController;
use Modules\Common\Services\CommonService;

class ReportController extends Controller
{

    private $commonService;
    private $reportCtrlObj;

    public function __construct(
        CommonService $commonService,
        APIReportController $reportCtrlObj
    ) {
        $this->commonService = $commonService;
        $this->reportCtrlObj = $reportCtrlObj;
    }

    public function report(Request $request)
    {
        $ctrlObj = $this->reportCtrlObj;

        if(isset($request->course_title) && ('' != $request->course_title)){
            $request->merge(['title' => $request->course_title]);
        }
        $apiResponse = $ctrlObj->getStudentCourseReport($request)->getData();
        $data = $apiResponse->data;
        if($request->getMethod() =='GET'){
            $data->requestFilters = [];
            if(!$apiResponse->status){
                return abort(500, 'Smething went wrong');
            }
        }
        else{

            $data->requestFilters = $request->all();
        }
        return view('common::report.general', ['data' => $data]);
    }

    public function salesReport(Request $request)
    {
        $request->merge([
            'is_date_range' => true,
        ]);
        $ctrlObj = $this->reportCtrlObj;

        $apiResponse = $ctrlObj->getSalesReport($request)->getData();
        $data = $apiResponse->data;
        // dd($data);
        if($request->getMethod() =='GET'){
            $data->requestFilters = [];
            if(!$apiResponse->status){
                return abort(500, 'Smething went wrong');
            }
        }
        else{
            $data->requestFilters = $request->all();
        }
        return view('common::report.sales', ['data' => $data]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('common::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('common::create');
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
        return view('common::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('common::edit');
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
