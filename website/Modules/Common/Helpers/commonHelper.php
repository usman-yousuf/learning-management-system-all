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
        $given_url = asset('uploads/' . $filename);
        // dd($given_url);
        $defaultFilePath = ('profile' == $nature) ? asset('assets/images/dummy_user.png') : asset('assets/images/logo_only.svg');
        // dd($defaultFilePath);
        $video_xtensions = ['flv', 'mp4', 'mpeg', 'mkv', 'avi'];
        $doc_xtensions = ['pdf'];
        // $image_xtensions = ['png', 'jpg', 'jpeg', 'gif'];

        $file_extension = pathinfo($given_url, PATHINFO_EXTENSION);
        if (in_array($file_extension, $video_xtensions)) {
            $given_url = $alt_filename;
        }
        if(in_array($file_extension, $doc_xtensions)){
            $given_url = 'https://techterms.com/img/lg/pdf_109.png';
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
            case 'request_appointment':
                $text = "{$senderName} has Requested for an Appointment";
                break;
            case 'accept_appointment':
                $text = "{$senderName} has Accepted Your Appointment";
                break;
            case 'active_appointment':
                $text = "{$senderName} has begun Appointment";
                break;
            case 'reject_appointment':
                $text = "{$senderName} has Rejected Your Appointment";
                break;
            case 'cancel_appointment':
                $text = "{$senderName} has Cancelled Your Appointment";
                break;
            case 'mark_appointment_completed':
                $text = "{$senderName} has Marked Your Appointment as Completed";
                break;
            case 'added_prescription':
                $text = "{$senderName} has Added Prescription against you Appointment";
                break;

            // Feedback meessages
            case 'sent_feedback':
                $text = "{$senderName} has Sent you a Feedback Message";
                break;
            case 'respond_feedback':
                $text = "{$senderName} has Responded to your Feedback";
                break;

            // Reviews
            case 'give_review':
                $text = "{$senderName} has Given you a Review";
                break;

            // Chat
            case 'initiated_chat':
                $text = "{$senderName} has Initiated a Chat with you";
                break;

            case 'sent_chat_message':
                $text = "{$senderName} has Sent a new Message";
                break;


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
            'request_appointment' => 'request_appointment',
            'accept_appointment' => 'accept_appointment',
            'reject_appointment' => 'reject_appointment',
            'active_appointment' => 'active_appointment',
            'cancel_appointment' => 'cancel_appointment',
            'mark_appointment_completed' => 'mark_appointment_completed',
            'added_prescription' => 'added_prescription',


            // feedback messages
            'sent_feedback' => 'sent_feedback',
            'respond_feedback' => 'respond_feedback',

            // Reviews
            'give_review' => 'give_review',

            // Chats
            'initiated_chat' => 'initiated_chat',
            'sent_chat_message' => 'sent_chat_message',


            // 'message_receive' =>'message_receive'
            // , 'post_like' => 'post_like'
            // , 'post_comment' => 'post_comment'
            // , 'post' => 'post'
            // , 'new_item_in_category' => 'new_item_in_category'
            // , 'product' => 'product'
            // , 'saved_item_price_decreased' => 'saved_item_price_decreased'
            // , 'saved_item_price_increased' => 'saved_item_price_increased'
            // , 'referral_request' => 'referral_request'
            // , 'event_online' => 'purchased_ticket_event_online'
            // , 'is_order_pending' => 'order_pending'
            // , 'is_order_accepted' => 'order_accepted'
            // , 'is_order_rejected' => 'order_rejected'
            // , 'is_order_delivered' => 'order_delivered'
            // , 'is_order_shipped' => 'order_shipped'
            // , 'is_purchased_ticket' => 'buy_ticket'
            // , 'is_make_donation' => 'make_donation'
            // , 'product_like' => 'product_like'
            // , 'is_account_connect' => 'account_connect'
            // , 'is_follow' => 'follow'
            // , 'is_booked_service' => 'booked_service'
            // , 'is_booked_status' => 'booked_status'
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

        // prescriptions
        if ($nature == 'prescription') {
            $path .= 'prescription/';
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

        // lab_test
        if ($nature == 'lab_test') {
            $path .= 'lab_test/';
            if ($is_thumbnail) {
                $path .= 'thumbnail/';
            }
        }

        return $path;
    }
}
