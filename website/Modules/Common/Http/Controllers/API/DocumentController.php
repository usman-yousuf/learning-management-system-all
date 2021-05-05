<?php

namespace Modules\Common\Http\Controllers\API;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\AuthAll\Http\Controllers\API\AuthApiController;
use Modules\Common\Services\CommonService;
use Modules\Common\Services\UploadedMediaService;

class DocumentController extends Controller
{
    private $uploadNediaService;
    private $commonService;

    public function __construct(CommonService $commonService, UploadedMediaService $uploadNediaService)
    {
        // $this->statsService = new StatsService();
        $this->commonService = $commonService;
        $this->uploadNediaService = $uploadNediaService;
    }

    /**
     * Upload Files on server
     *
     * @param Request $request
     * @return void
     */
    public function uploadFiles(Request $request)
    {
        $mediaNature = 'profile_image';
        $fieldName = 'medias';
        $isMultiple = isset($request->multiple)? (boolean)$request->multiple : false;
        if($request->nature == 'profile'){
            $request->merge([
                'model_name' => 'profiles'
                , 'tag' => 'profile_image'
                , 'model_id' => $request->profile_id,
            ]);
        }
        else if($request->nature == 'certificate'){
            $request->merge([
                'model_name' => 'educations'
                , 'tag' => 'education'
                , 'profile_id' => $request->profile_id,
                // , 'model_id' => // not available // store it and then save it
            ]);
            $mediaNature = 'certificate';
        }
        else if ($request->nature == 'experience') {
            $request->merge([
                'model_name' => 'experiences', 'tag' => 'experience', 'profile_id' => $request->profile_id,
                // , 'model_id' => // not available // store it and then save it
            ]);
            $mediaNature = 'experience';
        }

        // upload files onto server
        $result = $this->uploadNediaService->uploadMedias($request, $fieldName, $mediaNature, $isMultiple);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        // $result = $this->addUpdateMedia($request);
        // dd($request->all());
        // if (!$result['status']) {
        //     return $result;
        // }
        // $medias = $result['data'];
        // return $this->commonService->getSuccessResponse('Media(s) uploaded Successfully.', $result['data']);

        return $this->commonService->getSuccessResponse('Media(s) Uploaded Successfully.', $result['data']);
    }

    public function deleteFile(Request $request)
    {

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
