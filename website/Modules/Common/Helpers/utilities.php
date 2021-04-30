<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Response;



if(!function_exists('getFormattedDate')){
    /**
     * get Date in given format
     *
     * @param String $datetime
     * @param string $format
     * @return void
     */
    function getFormattedDate($datetime, $format = 'Y-m-d H:i:s')
    {
        return date($format, strtotime($datetime));
    }
}

if(!function_exists('calculateAge')){
    /**
     * Calculate Age based on given date of birth
     *
     * @param date $dob
     * @return void
     */
    function calculateAge($dob)
    {
        # object oriented
        $from = new DateTime($dob);
        $to   = new DateTime('today');
        $age = $from->diff($to)->y;

        # procedural
        // $age = date_diff(date_create('1970-02-01'), date_create('today'))->y;

        return $age;
    }
}

if(!function_exists('isTimeInRange')){
    function isTimeInRange($startTime, $endTime = null, $input)
    {
        // dd($startTime, $endTime, $input);
        $from = (null != $startTime)? $startTime : date('H:i');
        $to = (null != $endTime)? $endTime : date('H:i');
        $time = date('H:i', strtotime($input));

        $timearray = explode(":", $time);
        $timeint = (int)$timearray[0] + ((int)$timearray[1] / 60);
        if ($timeint < 1) $timeint += 24;

        $fromarray = explode(":", $from);
        $fromint = (int)$fromarray[0];

        $toarray = explode(":", $to);
        $toint = (int)$toarray[0];

        if (($fromint > 12 && $fromint > $toint) || $fromint == 0) {
            $toint += 24;
            if ($timeint <= 12) $timeint += 24;
        }

        if ($timeint >= $fromint && $timeint <= $toint) {
            return true;
        } else {
            return false;
        }
    }
}

//  ---------------------------------------------   //
//              Response Messages - START
//  ---------------------------------------------   //

if (!function_exists('getInternalSuccessResponse')) {
    /**
     * SUccess Response for internal Access
     *
     * @param array $data [OPTIONAL]
     * @param string $message [OPTIONAL]
     * @param integer $code [OPTIONAL]
     *
     * @return void
     */
    function getInternalSuccessResponse($data = [], $message = 'Success', $code = 200)
    {
        return ['status' => true, 'message' => $message, 'data' => $data, 'exceptionCode' => null, 'responseCode' => $code];
    }
}

if (!function_exists('getInternalErrorResponse')) {
    /**
     * Get Internal Error Response
     *
     * @param string $message [OPTIONAL]
     * @param array $data [OPTIONAL]
     * @param integer $code [OPTIONAL]
     *
     * @return void
     */
    function getInternalErrorResponse($message = 'error', $data = [], $exceptionCode = null, $responseCode = 500)
    {
        return ['status' => false, 'message' => $message, 'data' => $data, 'exceptionCode' => $exceptionCode, 'responseCode' => $responseCode];
    }
}


//  ---------------------------------------------   //
//              Response Messages - END
//  ---------------------------------------------   //


if (!function_exists('sendSuccess')) {
    /**
     * Send Back the success Response
     *
     * @param [type] $message [OPTIONAL]
     * @param [type] $data [OPTIONAL]
     * @param integer $responseCode [OPTIONAL]
     *
     * @return void
     */
    function sendSuccess($message, $data, $responseCode = 200)
    {
        return Response::json(['status' => true, 'message' => $message, 'data' => $data], $responseCode);
    }
}


//  ---------------------------------------------   //
//              String|Array Methods - START
//  ---------------------------------------------   //

if(!function_exists('getRandomString')){
    /**
     * get Random String
     *
     * @param [type] $length_of_string
     * @return void
     */
    function getRandomString($length = 60)
    {
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($str_result), 0, $length);
    }
}

if (!function_exists('getTruncatedString')) {
    /**
     * Undocumented function
     *
     * @param String $givenString
     * @param integer $targetLength [OPTIONAL]
     *
     * @return String trimmedString
     */
    function getTruncatedString($givenString, $targetLength = 25)
    {
        $str = $givenString;
        if (strlen($givenString) > $targetLength) {
            $str = substr($str, 0, $targetLength - 3);
            $str = $str . '...';
        }
        // if( strlen( $input) > $targetLength) {
        //     $str = explode( "\n", wordwrap( $input, $targetLength));
        //     $str = $str[0] . '...';
        // }

        return $str;
    }
}

if (!function_exists('print_array')) {
    /**
     * Print an Array in pre-formatted text
     *
     * @param Array $arr
     * @param boolean $exit [OPTIONAL]
     *
     * @return void
     */
    function print_array($arr, $exit = false)
    {
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
        if ($exit) {
            exit;
        }
    }
}

//  ---------------------------------------------   //
//              String|Array Methods - END
//  ---------------------------------------------   //



//  ---------------------------------------------   //
//          IP and localizations - START
//  ---------------------------------------------   //

if (!function_exists('get_local_timezone')) {
    /**
     * get Timezone from given IP address
     *
     * @param ip $givenIp
     * @return void
     */
    function get_local_timezone($givenIp)
    {
        if ($givenIp == '127.0.0.1' || $givenIp == '::1') {
            $ip = "119.73.121.52";
        } else {
            $ip = $givenIp;
        }

        $tz = \Session::get('timezone') ?? '';
        if ($tz == '') {
            $url = 'http://ip-api.com/json/' . $ip;
            $tz = file_get_contents($url);
            $tz = json_decode($tz, true)['timezone'];

            \Session::put('timezone', $tz);
        }
        return $tz;
    }
}

if (!function_exists('get_locale_datetime')) {
    /**
     * Get dateTime Timezone of Guest User hitting the application
     *
     * @param String $utc_datetime
     * @param String $targetFormat
     *
     * @return void
     */
    function get_locale_datetime($utc_datetime, $givenIp, $targetFormat = 'Y-m-d H:i:s')
    {
        $tz = get_local_timezone($givenIp);
        $dateString = Carbon::create($utc_datetime)->timezone($tz)->format($targetFormat);

        return $dateString;
    }
}

if (!function_exists('get_utc_datetime')) {
    /**
     * Get DateTime in UTC against given user locale
     *
     * @param String $local_datetime
     * @param ip $givenIp
     * @param String $targetFormat
     *
     * @return String Converted DateTime in UTC
     */
    function get_utc_datetime($local_datetime, $givenIp, $targetFormat = 'Y-m-d H:i:s')
    {
        $locale_tz = get_local_timezone($givenIp);
        $tz_to = 'UTC';

        $dt = new \DateTime($local_datetime, new \DateTimeZone($locale_tz));
        $dt->setTimeZone(new \DateTimeZone($tz_to));
        $utc_datetime = $dt->format($targetFormat);

        return $utc_datetime;
    }
}


//  ---------------------------------------------   //
//          IP and localizations - END
//  ---------------------------------------------   //





//  ---------------------------------------------   //
//          Document and File - START
//  ---------------------------------------------   //

if(!function_exists('getAllowedFileExtensions')){
    function getAllowedFileExtensions($nature = 'image'){
        $allowedExtensions = '';
        if('image' == $nature){
            $allowedExtensions .= 'jpg,jpeg,svg,png,gif';
        }

        if('video' == $nature)
        {
            $allowedExtensions .= 'flv,mp4,mpeg,mkv,avi';
        }

        if ('certificate' == $nature) {
            $allowedExtensions .= 'jpg,jpeg,svg,png,gif,pdf';
        }

        if ('experience' == $nature) {
            $allowedExtensions .= 'jpg,jpeg,svg,png,gif,pdf';
        }

        if('doc' == $nature){
            $allowedExtensions .= 'pdf';
        }

        if ('all' == $nature) {
            $allowedExtensions .= 'jpg,jpeg,svg,png,gif,pdf';
        }

        return $allowedExtensions;
    }
}

//  ---------------------------------------------   //
//          Document and File - END
//  ---------------------------------------------   //

