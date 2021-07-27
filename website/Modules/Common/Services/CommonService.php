<?php

namespace Modules\Common\Services;

use Illuminate\Support\Facades\Mail;
use Modules\AuthAll\Http\Controllers\TwilioController;

class CommonService
{
    private $responseHeaders = [];

    //  -------------------------------------------  //
    //                  Responses
    //  -------------------------------------------  //

    #region - Responses - START

        /**
         * Get Validation Error Response
         *
         * @param String $message
         * @param Array $data
         *
         * @return void
         */
        public function getValidationErrorResponse($message, $data)
        {
            $responseData = [
                'status' => false,
                'message' => $message,
                'data' => $data,
                'exceptionCode' => null,
                'responseCode' => 422,
            ];
            return response()->json($responseData, 422, $this->responseHeaders);
        }

        /**
         * Get No Record Found
         *
         * @param String $message
         * @return void
         */
        public function getNoRecordFoundResponse($message = null)
        {
            $responseData = [
                'status' => false,
                'message' => (null != $message)? $message : 'No Record(s) Found',
                'data' => null,
                'exceptionCode' => 404,
            ];
            return response()->json($responseData, 404, $this->responseHeaders);
        }


        /**
         * Get General Errro Response
         *
         * @param String $message
         * @param Array $data
         *
         * @return void
         */
        public function getGeneralErrorResponse($message = null, $data = [])
        {
            $responseData = [
                'status' => false,
                'message' => (null != $message)? $message : 'General Error',
                'data' => $data,
                'exceptionCode' => 500,
            ];
            return response()->json($responseData, 404, $this->responseHeaders);
        }

        /**
         * Get not Approved Response
         *
         * @param String $message
         * @param Array $data
         *
         * @return void
         */
        public function getNotApprovedErrorResponse($message = null, $data = [])
        {
            $responseData = [
                'status' => false,
                'message' => (null != $message)? $message : 'Not Approved',
                'data' => $data,
                'exceptionCode' => 202,
                'responseCode' => 403,
            ];
            return response()->json($responseData, 403, $this->responseHeaders);
        }

        /**
         * Get Error Response wehen something went wrong
         *
         * @param String $message
         * @param Array $data
         * @param Integer $responseCode
         * @param Integer $exceptionCode
         *
         * @return void
         */
        public function getProcessingErrorResponse($message, $data, $responseCode = 500, $exceptionCode = null)
        {
            $responseMessage = $message;
            $responseData = $data;
            if($exceptionCode == '23000'){
                $responseMessage = 'Something went wrong while storing data in database';
                $responseData = null;
            }
            $responseData = [
                'status' => false,
                'message' => $responseMessage,
                'data' => $responseData,
                'exceptionCode' => $exceptionCode,
                'responseCode' => $responseCode,
            ];
            return response()->json($responseData, $responseCode, $this->responseHeaders);
        }

        /**
         * Get Success Response
         *
         * @param String $message
         * @param Array $data
         * @param Integer $responseCode
         *
         * @return void
         */
        public function getSuccessResponse($message = null, $data = [], $responseCode = 200)
        {
            $responseData = [
                'status' => true,
                'message' => (null != $message)? $message : 'Success',
                'data' => $data,
            ];
            return response()->json($responseData, $responseCode, $this->responseHeaders);
        }

        /**
         * Get Not Authorized Response
         *
         * @param String $message
         * @param Array $data
         *
         * @return void
         */
        public function getNotAuthorizedResponse($message = null, $data = [], $responseCode = 403)
        {
            $message = ($message != null)? $message : "Your are not authorized to access it";
            $responseData = [
                'status' => true,
                'message' => $message,
                'data' => $data,
            ];
            return response()->json($responseData, $responseCode, $this->responseHeaders);
        }
    #endregion - Responses - END

    //  -------------------------------------------  //
    //                  Emails
    //  -------------------------------------------  //

    #region - EMail - START

        /**
         * Send Feedback Response Email
         *
         * @param String $targetEmail
         * @param Array $replyObj
         * @param String $subject
         *
         * @return void
         */
        public function sendFeedbackResponseEmail($targetEmail, $replybj, $subject = 'Feedback Response')
        {
            // return getInternalSuccessResponse();

            // $template, $templateParams,
            $template = 'email_template.feedback';
            $templateParams = [
                'message_body' => $replybj->taggedMessage->message,
                'response_body' => $replybj->message,
                'sender_name' => $replybj->taggedMessage->sender->first_name . ' ' . $replybj->taggedMessage->sender->last_name,
            ];

            try{
                Mail::send($template, $templateParams, function ($m) use ($targetEmail, $subject) {
                    $m->from(config('mail.from.address'), config('mail.from.name'));
                    $m->to($targetEmail)->subject($subject);
                });
                return getInternalSuccessResponse();
            }
            catch (\Exception $ex) {
                return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
            }
        }

        /**
         * Send Feedback Response Email
         *
         * @param String $targetEmail
         * @param String $subject
         * @param String $template
         * @param Array $templateParams
         *
         * @return void
         */
        public function sendVerificationEmail($targetEmail, $subject, $template, $templateParams)
        {
            // return getInternalSuccessResponse();

            try{
                Mail::send($template, $templateParams, function ($m) use ($targetEmail, $subject) {
                    $m->from(config('mail.from.address'), config('mail.from.name'));
                    $m->to($targetEmail)->subject($subject);
                });
                return getInternalSuccessResponse();
            } catch (\Exception $ex) {
                return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
            }
        }

        /**
         * Send Reset Password Email
         *
         * @param String $targetEmail
         * @param String $subject
         * @param String $template
         * @param Array $templateParams
         *
         * @return void
         */
        public function sendResetPasswordEmail($targetEmail, $subject, $template, $templateParams)
        {
            // return getInternalSuccessResponse();

            try{
                Mail::send($template, $templateParams, function ($m) use ($targetEmail, $subject) {
                    $m->from(config('mail.from.address'), config('mail.from.name'));
                    $m->to($targetEmail)->subject($subject);
                });
                return getInternalSuccessResponse();
            } catch (\Exception $ex) {
                return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
            }
        }



        /**
         * Send Rejection Email to teacher not approved
         *
         * @param String $targetEmail
         * @param String $subject
         * @param String $template
         * @param Array $templateParams
         *
         * @return void
         */
        public function sendRejectionTeacherApprovedEmail($targetEmail, $subject, $template,$templateParams)
        {
            // return getInternalSuccessResponse();

            try{
                Mail::send($template, $templateParams, function ($m) use ($targetEmail, $subject) {
                    $m->from(config('mail.from.address'), config('mail.from.name'));
                    $m->to($targetEmail)->subject($subject);
                });
                return getInternalSuccessResponse();
            } catch (\Exception $ex) {
                return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
            }
        }

    #endregion - Emails - END

        /**
         * Send SMS to number
         *
         * @param String $targetPhoneNumber
         * @param String $subject
         * @param String $code
         * @return void
         */
        public function sendResetPasswordMessage($targetPhoneNumber, $subject, $code = null)
        {
            $twilio = new TwilioController();
            try{
                $msgBody = "LMS {$subject} Code: {$code}";
                $result = $twilio->sendMessage($targetPhoneNumber, $msgBody);
                if (!$result['status']) {
                    return $result;
                }
                $data = [
                    'phone' => $targetPhoneNumber,
                    'code' => $code
                ];
                return getInternalSuccessResponse($data);
            }
            catch(\Exception $ex)
            {
                return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
            }
            // if (!$twilio->sendMessage($targetPhoneNumber, $message ' . $code)) {
            //     return sendError('Somthing went wrong while send Code over phone', NULL);
            // }
        }

        /**
         * Send Account Verification SMS
         *
         * @param String $targetPhoneNumber
         * @param String $code
         *
         * @return void
         */
        public function sendAccountVerificationSMS($targetPhoneNumber, $code)
        {
            $msgBody = 'Enter this code to verify your LMS account ' . $code;

            $twilio = new TwilioController();
            $result = $twilio->sendMessage($targetPhoneNumber, $msgBody);
            if(!$result['status']){
                return $result;
            }
            $data = [
                'phone' => $targetPhoneNumber,
                'code' => $code
            ];
            return getInternalSuccessResponse($data);
        }

        /**
         * Validate a Given Phone Number along with country code
         *
         * @param String $country_code (+coutryCode)
         * @param string $phone_number (the rest of the number)
         *
         * @return void
         */
        public function validatePhoneNumber($country_code, $phone_number)
        {
            $twilio = new TwilioController();
            $result = $twilio->validNumber($country_code . $phone_number, $country_code);
            if(!$result['status']){
                return $result;
            }

            return getInternalSuccessResponse($result, 'Phone Numbr is valid');

        }
}
