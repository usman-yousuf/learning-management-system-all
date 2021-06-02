<?php

namespace Modules\Common\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CommonController extends Controller
{

    public function aboutUs()
    {
        return view('common::index');
    }

    public function privacyPolicy()
    {
        return view('common::index');
    }

    /*
    public function uploadMedias(Request $request, $fieldName = 'media', $nature = 'profile_image', $multiple = false)
    {
        // dd($request->file($fieldName))  ;
        $uploadedFiles = [];
        if($multiple)
        {
            if ($request->hasFile($fieldName)){

                foreach ($request->file($fieldName) as $media) {
                    $file = $media;
                    // dd($request->file($fieldName))  ;

                    $video_xtensions = ['flv', 'mp4', 'mpeg', 'mkv', 'avi'];
                    $image_xtensions = ['png', 'jpg', 'jpeg', 'gif', 'bmp, '];
                    $doc_xtensions = ['pdf'];
                    $allowedFilesExtensions = array_merge($video_xtensions, $image_xtensions, $doc_xtensions);

                    $file_extension = $file->getClientOriginalExtension();
                    if (in_array($file_extension, $allowedFilesExtensions)) {
                        $temp['title'] = $file->getClientOriginalName();
                        $temp['tag'] = $nature;
                        $temp['type'] = (in_array($file_extension, $doc_xtensions))? 'pdf' : 'image';

                        $targetName = $nature . rand(1000, 9999) . '.' . $file_extension;
                        $temp['filename'] = $targetName;

                        // upoad file on server
                        $file->move(getUploadDir($nature), $targetName);
                        $targetPath = getUploadDir($nature).$targetName;
                        $temp['path'] = $nature .'/'.$targetName;
                        if (in_array($file_extension, $doc_xtensions)) {
                            $temp['ratio'] = 1;
                        }
                        else{
                            $imageSize = getimagesize($targetPath);
                            $temp['ratio'] = $imageSize[0] / $imageSize[1];
                        }

                        // generate thumbnail
                        $thumbnailFilename = $nature.'_thumbnail_' . rand(10, 999999) . '.png';
                        // dd($targetPath);
                        // $contents = \FFMpeg::openUrl($targetPath)
                        //     ->export()
                        //     ->addFilter(function (VideoFilters $filters) {
                        //         $filters->resize(new \FFMpeg\Coordinate\Dimension(640, 480));
                        //     })
                        //     // ->disk('local')
                        //     // ->save(getUploadDir($nature, true), $thumbnailFilename);
                        //     ->save($thumbnailFilename);
                        // $temp['thumbnail'] = getUploadDir($nature, true) . $thumbnailFilename;
                        $temp['thumbnail'] = $temp['path'];
                        $uploadedFiles[] = array_merge($uploadedFiles, $temp);
                    }
                    else{
                        return getInternalErrorResponse('File Extension is not supported.', null);
                    }
                }
            }else{
                return getInternalErrorResponse('Please provide files.', null);
            }
        }
        else{
            $file = $request->file($fieldName);

            $video_xtensions = ['flv', 'mp4', 'mpeg', 'mkv', 'avi'];
            $image_xtensions = ['png', 'jpg', 'jpeg', 'gif'];
            $doc_xtensions = ['pdf'];
            $allowedFilesExtensions = array_merge($video_xtensions, $image_xtensions, $doc_xtensions);

            $file_extension = $file->getClientOriginalExtension();
            if (in_array($file_extension, $allowedFilesExtensions)) {
                $temp['title'] = $file->getClientOriginalName();
                $temp['tag'] = $nature;
                $temp['type'] = (in_array($file_extension, $doc_xtensions)) ? 'pdf' : 'image';

                $targetName = $nature . rand(1000, 9999) . '.' . $file_extension;
                $temp['filename'] = $targetName;

                // upoad file on server
                $file->move(getUploadDir($nature), $targetName);
                $targetPath = getUploadDir($nature) . $targetName;
                $temp['path'] = $nature . '/' . $targetName;
                if(false == getimagesize($targetPath)){
                    $temp['ratio'] = 1;
                }
                else{
                    $imageSize = getimagesize($targetPath);
                    $temp['ratio'] = $imageSize[0] / $imageSize[1];
                }

                // generate thumbnail
                $thumbnailFilename = $nature . '_thumbnail_' . rand(10, 999999) . '.png';
                // dd($targetPath);
                // $contents = \FFMpeg::openUrl($targetPath)
                //     ->export()
                //     ->addFilter(function (VideoFilters $filters) {
                //         $filters->resize(new \FFMpeg\Coordinate\Dimension(640, 480));
                //     })
                //     // ->disk('local')
                //     // ->save(getUploadDir($nature, true), $thumbnailFilename);
                //     ->save($thumbnailFilename);
                // $temp['thumbnail'] = getUploadDir($nature, true) . $thumbnailFilename;
                $temp['thumbnail'] = $temp['path'];
                $uploadedFiles[] = array_merge($uploadedFiles, $temp);
            } else {
                return getInternalErrorResponse('File Extension is not supported.', null);
            }
        }

        return getInternalSuccessResponse($uploadedFiles);
    }
    */

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
