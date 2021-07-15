<?php

if (!function_exists('getFileUrl')) {
    /**
     * get File URL
     *
     * @param String $filename
     * @param String $alt_filename
     * @param String $nature
     *
     * @return void
     */
    function getFileUrl($filename = null, $alt_filename = null, $nature = null)
    {
        if(strpos($filename, 'http') !== false ){
            $given_url = $filename;
        }
        else{
            $given_url = asset('uploads/' . $filename);
        }
        // dd($given_url);
        $defaultFilePath = asset('assets/images/logo_only.svg');
        if('profile' == $nature){
            $defaultFilePath = asset('assets/images/placeholder_user.png');
        }
        else if('certificate' == $nature){
            $defaultFilePath = asset('assets/images/certification_placeholder.svg');
        }
        else if ('course' == $nature) {
            $defaultFilePath = asset('assets/images/certification_placeholder.svg');
        }
        else if('course_preview' == $nature){
            $defaultFilePath = asset('assets/images/dummy_course_cover.jpg');
        }
        else if ('video' == $nature) {
            $defaultFilePath = asset('assets/images/video_placeholder.svg');
        }
        else if ('office' == $nature) {
            $defaultFilePath = "https://upload.wikimedia.org/wikipedia/commons/thumb/5/5f/Microsoft_Office_logo_%282019%E2%80%93present%29.svg/1200px-Microsoft_Office_logo_%282019%E2%80%93present%29.svg.png";
        }
        else if ('assignment' == $nature) {
            $defaultFilePath = 'https://techterms.com/img/lg/pdf_109.png';
        }
        // $defaultFilePath = ('profile' == $nature) ? asset('assets/images/dummy_user.png') : asset('assets/images/logo_only.svg');
        // dd($defaultFilePath);
        // $video_xtensions = ['flv', 'mp4', 'mpeg', 'mkv', 'avi'];
        // $doc_xtensions = ['pdf'];

        $video_xtensions = explode(',', getAllowedFileExtensions('video'));
        $doc_xtensions = explode(',', getAllowedFileExtensions('doc'));
        $office_xtensions = explode(',', getAllowedFileExtensions('office'));
        $assignment_xtensions = explode(',', getAllowedFileExtensions('assignment'));
        $upload_assignment_xtensions = explode(',', getAllowedFileExtensions('upload_assignment'));
        $allowedFilesExtensions = explode(',', getAllowedFileExtensions('all'));
        // $image_xtensions = ['png', 'jpg', 'jpeg', 'gif'];

        $file_extension = pathinfo($given_url, PATHINFO_EXTENSION);
        if (in_array($file_extension, $video_xtensions)) {
            // $given_url = $alt_filename;
            // ignore
        }
        // || in_array($file_extension, $upload_assignment_xtensions)
        if(in_array($file_extension, $doc_xtensions) || (in_array($file_extension, $assignment_xtensions) )){
            $given_url = 'https://techterms.com/img/lg/pdf_109.png';
        }

        if (in_array($file_extension, $office_xtensions)) {
            $given_url = 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5f/Microsoft_Office_logo_%282019%E2%80%93present%29.svg/1200px-Microsoft_Office_logo_%282019%E2%80%93present%29.svg.png';
        }

        if (!empty($given_url) && (null != $given_url)) {
            $exists = @fopen($given_url, 'r'); // try to open file in read mode
            if ($exists) {
                $filePath = $given_url;
            } else {
                if(null != $alt_filename){
                    $altExists = @fopen($alt_filename, 'r');
                    if ($altExists) {
                        $filePath = $alt_filename;
                    } else {
                        $filePath = $defaultFilePath;
                    }
                } else {
                    $filePath = $defaultFilePath;
                }
            }
        } else {
            $filePath = $defaultFilePath;
        }
        return $filePath;
    }
}


if (!function_exists('getIconUrl')) {
    /**
     * get Icon File URL
     *
     * @param String $filename
     * @param String $alt_filename
     * @param String $nature
     *
     * @return void
     */
    function getIconUrl($type = null, $pageSection)
    {
        if (strpos($type, 'http') !== false) {
            return $type;
        }
        // dd($type, $pageSection);
        $type = strtolower($type);
        if('course_nature' == $pageSection){
            if('online' == $type){
                $defaultFilePath = asset('assets/images/online_icon.svg');
            } elseif ('video' == $type) {
                $defaultFilePath = asset('assets/images/youtube_icon.svg');
            }
            else{
                $defaultFilePath = asset('assets/images/youtube_icon.svg');
            }
        }
        else if('dashboard_search' == $pageSection){
            $defaultFilePath = asset('assets/images/search_icon.svg');
        }
        else if('is_course_free' == $pageSection){
            $defaultFilePath = asset('assets/images/dollar-icon.svg');
        }
        else{
            $defaultFilePath = asset('assets/images/youtube_icon.svg');
        }
        return $defaultFilePath;
    }
}


if( !function_exists('getStarRatingHTML')){
    /**
     * gte Star Rating
     *
     * @param integer $userRating
     * @return void
     */
    function getStarRatingHTML($userRating = 5)
    {
        $activeStarsCount = (int)$userRating;
        $showHalfStar = (strpos($userRating, ".") !== false);
        $string = '';

        // show full stars
        for ($i = 0; $i < $activeStarsCount; $i++) {
            $string .= "<img src=" . asset('assets/images/star.svg') ." class='star-s' alt='star' />";
        }
        // show half star
        if ($showHalfStar) {
            $string .= "<img src=" . asset('assets/images/half_star.svg') ." class='star-s' alt='half-star' />";
        }

        // add empty stars for
        if($activeStarsCount < 5){
            $emptyStarsCount = ($showHalfStar)? (5 - $activeStarsCount - 1) : (5 - $activeStarsCount);
            for($i = 0; $i < $emptyStarsCount; $i++){
                $string .= "<img src=" . asset('assets/images/empty_star.svg') . " class='star-s' alt='empty-star' />";
            }
        }

        // dd($activeStarsCount, $showHalfStar, $string);
        return $string;
    }
}


if (!function_exists('getFileBucketPath')) {
    /**
     * get Bcket Path of File
     *
     * @param String $fileName
     *
     * @return String full URL path if file
     */
    function getFileBucketPath($fileName)
    {
        return 'https://telemedicine.s3.us-east-2.amazonaws.com/' . $fileName;
    }
}

if(!function_exists('getNotificationText')){
    /**
     * Get Notification Text
     *
     * @param String $senderName
     * @param String $type
     * @return void
     */
    function getNotificationText($senderName, $type)
    {
        $key = listNotficationTypes()[$type];
        $text = 'Invalid Key';
        switch ($key) {
            case 'create_assignment':
                $text = "{$senderName} has Created an assignment for you";
                break;
            case 'submit_assignment':
                $text = "{$senderName} has submitted his/her Assignmnet";
                break;
            case 'create_quiz':
                $text = "{$senderName} has created quiz for you";
                break;
            case 'submit_quiz':
                $text = "{$senderName} has submitted his/her Quiz";
                break;


            case 'enrolled_course':
                $text = "{$senderName} has Enrolled Your Course";
                break;
            case 'left_course':
                $text = "{$senderName} has left your course";
                break;
            case 'add_content':
                $text = "{$senderName} has added new content your course";
                break;
            case 'course_outline':
                $text = "{$senderName} has added new outline your course";
                break;
            case 'handout_content':
                $text = "{$senderName} has added new handout content your course";
                break;
            case 'course_slot':
                $text = "{$senderName} has added new slot your course";
                break;
            case 'send_message':
                $text = "{$senderName} has send you zoom link";
                break;
            case 'upload_assignment':
                $text = "{$senderName} has upload assignmnet";
                break;
            case 'marked_assignment':
                $text = "{$senderName} has marked assignmnet";
                break;

            // // Feedback meessages
            // case 'sent_feedback':
            //     $text = "{$senderName} has Sent you a Feedback Message";
            //     break;
            // case 'respond_feedback':
            //     $text = "{$senderName} has Responded to your Feedback";
            //     break;

            // // Reviews
            // case 'give_review':
            //     $text = "{$senderName} has Given you a Review";
            //     break;

            // // Chat
            // case 'initiated_chat':
            //     $text = "{$senderName} has Initiated a Chat with you";
            //     break;

            // case 'sent_chat_message':
            //     $text = "{$senderName} has Sent a new Message";
            //     break;


            // default
            default:
                # code...
                break;
        }

        return $text;
    }
}

if (!function_exists('listNotficationTypes')) {
    /**
     * Return list of notification types in application
     *
     * @return void
     */
    function listNotficationTypes(){
        return [
            'create_assignment' => 'create_assignment',
            'submit_assignment' => 'submit_assignment',

            'create_quiz' => 'create_quiz',
            'submit_quiz' => 'submit_quiz',

            'enrolled_course' => 'enrolled_course',
            'left_course' => 'left_course',
            'add_content' => 'add_content',
            'course_outline' => 'course_outline',
            'handout_content' => 'handout_content',
            'course_slot' => 'course_slot',
            'send_message' => 'send_message',
            'upload_assignment' => 'upload_assignment',
            'marked_assignment' => 'marked_assignment'

            // feedback messages
            // 'sent_feedback' => 'sent_feedback',
            // 'respond_feedback' => 'respond_feedback',

            // Reviews
            // 'give_review' => 'give_review',

            // Chats
            // 'initiated_chat' => 'initiated_chat',
            // 'sent_chat_message' => 'sent_chat_message',
        ];
    }
}

if(!function_exists('getUploadDir'))
{
    /**
     * Get Profile URL
     *
     * @param string $nature
     * @param boolean $is_thumbnail
     * @return void
     */
    function getUploadDir($nature = 'profile_image', $is_thumbnail = false)
    {
        $path = public_path('uploads/');

        // profile images
        if ($nature == 'profile_image') {
            $path .= 'profile_image/';
            if ($is_thumbnail) {
                $path .= 'thumbnails/';
            }
        }

        // certificate
        if ($nature == 'certificate') {
            $path .= 'certificate/';
            if ($is_thumbnail) {
                $path .= 'thumbnail/';
            }
        }

        // experience
        if ($nature == 'experience') {
            $path .= 'experience/';
            if ($is_thumbnail) {
                $path .= 'thumbnail/';
            }
        }

        // course
        if ($nature == 'course') {
            $path .= 'course/';
            if ($is_thumbnail) {
                $path .= 'thumbnail/';
            }
        }

        return $path;
    }


}

if(!function_exists('getUnReadNotificationCount'))
{
    /**
     * Get UnRead Notifications Count
     *
     * @param string $request
     * @return void
     */
    function getUnReadNotificationCount()
    {
        request()->merge(['is_read' => 0]);
        $notification = new \Modules\Common\Services\NotificationService();


        $unReadNotification = $notification->getUnreadNotificationsCount(request());
        return $unReadNotification['data'];
    }
}
