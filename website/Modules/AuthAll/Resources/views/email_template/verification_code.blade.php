<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Verification</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700,900&display=swap" rel="stylesheet">
    <!--font-family: 'Lato', sans-serif;-->

</head>
<body>
<table style="width:700px;height: 100%;background-color: #F5F6F8;margin:0 auto;padding:20px;">
    <tr>
        <td align="center" valign="top">
            <table border="0" cellpadding="20" style="width:100%;padding-bottom: 0px;background-color: #fff;">
                <tr>
                    <td align="center" valign="top" style="">
                        <table border="0" cellpadding="20" cellspacing="0"  style="width:100%;padding-bottom:0px;">
                            <tr>
                                <td style="padding: 0 0 10px 0;text-align: right;border-bottom: 1px solid #cecece;color:#7F8FA4;font-size: 11px;font-family: 'Lato', sans-serif;font-weight: 400;text-align: center;">
                                    Verification Code
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td align="center" valign="top" style="">
                        <table border="0" cellpadding="20" cellspacing="0"  style="width:100%;padding-bottom:0px;">
                            <tr>
                                <td style="padding: 0 0 20px 0;font-family: 'Lato', sans-serif;font-size: 20px;text-align:center;color:#334150;font-weight: 800;">Hi !</td>
                            </tr>
                            <tr>
                                <td style="padding: 0 0 20px 0;text-align:center;color:#334150;font-family: 'Lato', sans-serif;font-size: 16px;font-weight:400;">There was a request for signup!</td>
                            </tr>
                            <tr>
                                <td style="padding: 0 0 20px 0;">
                                    <img src="https://cleaques.s3.us-east-2.amazonaws.com/assets/verification.png" style="margin: 0 auto;display: block;max-width: 20%;"/>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center;">
                                    {{ $code }}
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 20px 0 20px 0;text-align:center;color:#7F8FA4;font-family: 'Lato', sans-serif;font-size: 16px;font-weight:400;">If you did not make this request, just ignore this email. Otherwise, <br/>
                                    please use the above code to verify
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 20px 0 20px 0;text-align:center;color:#7F8FA4;font-family: 'Lato', sans-serif;font-size: 16px;font-weight:400;"></td>
                            </tr>
                            <tr>
                                <td style="padding: 0 0 20px 0;font-family: 'Lato', sans-serif;font-size: 20px;text-align:center;color:#334150;font-weight: 800;">Need Help?
                                </td>
                            </tr>


                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 0 0 20px 0;text-align:center;color:#7F8FA4;font-family: 'Lato', sans-serif;font-size: 16px;font-weight:400;">
                        ⓒ {{ config('app.name') }}
                    </td>

                </tr>

            </table>
        </td>
    </tr>
</table>
</body>
</html>
