<?php

namespace Modules\Common\Services;

use Modules\Common\Http\Controllers\API\AuthController;
use Modules\Common\Entities\UploadedMedia;
use Illuminate\Http\Request;

class UploadedMediaService
{
    private $relations;

    public function __construct()
    {
        $this->relations = [];
    }


    /**
     * Upload Appointment Prescriptions
     *
     * @param Appointment $appointment
     * @param Request $request
     *
     * @return void
     */
    public function UploadAppointmentPrescriptions($appointment, $request)
    {
        $medias = [];
        $presciptions = $request->uploadedFiles;
        foreach ($presciptions as $key => $item) {
            $item = (object)$item;
            $model = new UploadedMedia();
            $model->model_id = $appointment->id;
            $model->model_name	 = 'appointments';
            $model->doctor_id = $appointment->doctor_id; // doctor prescribed
            $model->profile_id = $appointment->patient_id; // patient is the reciver for this presciption

            $model->title = $item->title;
            $model->type = $item->type;
            $model->path = $item->path;
            $model->thumbnail = $item->thumbnail;
            $model->ratio = $item->ratio;
            $model->tag = 'prescription';
            $model->created_at = date('Y-m-d H:i:s');
            $model->updated_at = date('Y-m-d H:i:s');

            try {
                $model->save();
                $medias[] = $model;
                // dd($model->getAttributes());
            } catch (\Exception $ex) {
                // dd($ex);
                return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
            }
        }
        return getInternalSuccessResponse($medias);
    }

    /**
     * Upload Certifications
     *
     * @param Request $request
     * @return void
     */
    public function uploadDoctorCertificates(Request $request)
    {
        $request->merge([
            'model_id' => $request->profile_id,
            'model_name' => 'profiles',
            'tag' => 'certificate',
        ]);
        $result = $this->addUpdateMedia($request);
        if (!$result['status']) {
            return $result;
        }
        $medias = $result['data'];
        return getInternalSuccessResponse($medias);
    }

    public function uploadPatientLabTests(Request $request)
    {
        $request->merge([
            'model_id' => $request->profile_id,
            'model_name' => 'profiles',
            'tag' => 'lab_test',
        ]);
        $result = $this->addUpdateMedia($request);
        if (!$result['status']) {
            return $result;
        }
        $medias = $result['data'];
        return getInternalSuccessResponse($medias);
    }

    /**
     * Upload Appointmen Prescription
     *
     * @param Request $request
     *
     * @return void
     */
    public function addUpdateMedia(Request $request)
    {

        $savedMedias = [];
        $medias = $request->uploadedFiles;
        // dd($medias);
        if(!empty($medias)){
            foreach ($medias as $key => $item) {
                $item = (object)$item;
                $model = new UploadedMedia();
                $model->model_id = (isset($request->model_id) && ('' != $request->model_id)) ? $request->model_id : $request->profile_id; // model_id
                $model->model_name = (isset($request->model_name) && ('' != $request->model_name)) ? $request->model_name : 'profiles'; // model_name
                $model->profile_id = (isset($request->profile_id) && ('' != $request->profile_id)) ? $request->profile_id : $request->user()->profile_id; // profile_id
                $model->doctor_id = (isset($request->doctor_id) && ('' != $request->doctor_id)) ? $request->doctor_id : null; // doctor prescribed

                $model->title = $item->title;
                $model->path = $item->path;
                $model->thumbnail = $item->thumbnail;
                $model->ratio = $item->ratio;
                $model->tag = $item->tag; // (isset($request->tag) && ('' != $request->tag)) ? $request->tag : 'profile_image';
                $model->type = $item->type; // (isset($item->type) && ('' != $request->media_type))? $request->media_type : 'image';
                $model->created_at = date('Y-m-d H:i:s');
                $model->updated_at = date('Y-m-d H:i:s');

                try {
                    $model->save();
                    $savedMedias[] = $model;
                    // dd($model->getAttributes());
                } catch (\Exception $ex) {
                    // dd($ex);
                    return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
                }
            }
        }
        return getInternalSuccessResponse($savedMedias);
    }

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



}
