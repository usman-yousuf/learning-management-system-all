<?php

namespace Modules\Common\Http\Controllers\API;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\AuthAll\Http\Controllers\API\AuthApiController;
use Modules\Common\Services\UploadedMediaService;

class DocumentController extends Controller
{
    private $uploadNediaService;

    public function __construct(Request $request)
    {
        // logout user if is deleted
        // dd($request->user(), \Auth::user());
        // if(\Auth::check()){
        //     dd('i am inn');
        // }
        // else{}

        // if ($request->user()->profile_id == null) {
        //     $authCtrlObj = new AuthApiController();
        //     $result = $authCtrlObj->signout($request)->getData();
        //     if ($result->status) {
        //         return getInternalErrorResponse('Session Expired');
        //     } else {
        //         return getInternalErrorResponse('Something went wrong logging out the user');
        //     }
        // }
        $this->uploadNediaService = new UploadedMediaService();
    }

    public function uploadFiles(Request $request)
    {
        dd($request->all(), $request->file('medias'));
        $request->merge([
            'model_id' => $request->profile_id,
            'model_name' => 'profiles',
            'tag' => 'lab_test',
        ]);
        // $result = $this->addUpdateMedia($request);
        // if (!$result['status']) {
        //     return $result;
        // }
        // $medias = $result['data'];
        // return getInternalSuccessResponse($medias);

        $result = $this->uploadNediaService->addUpdateMedia($request);
        return view('common::index');
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
