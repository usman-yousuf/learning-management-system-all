<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Account Rejected</title>
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
                                <!--                                                  <td style="padding: 0 0 10px 0;border-bottom: 1px solid #cecece;">
                                                                                       <a href="#"><img src="http://codingpixeldemo.com/email-images/logo-construct.png" alt="logo" border="0" ></a>
                                                                                  </td>-->
                                <td style="padding: 0 0 10px 0;text-align: right;border-bottom: 1px solid #cecece;color:#7F8FA4;font-size: 11px;font-family: 'Lato', sans-serif;font-weight: 400;text-align: center;">
                                    Reason for rejection approval
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
                                <td style="padding: 0 0 20px 0;text-align:center;color:#334150;font-family: 'Lato', sans-serif;font-size: 16px;font-weight:400;">Admin Rejected your Approval</td>
                            </tr>
                            <tr>
                                <td style="padding: 0 0 20px 0;">
                                    <img src="http://codingpixeldemo.com/email-images/lock.png" style="margin: 0 auto;display: block;max-width: 20%;"/>
                                </td>
                            </tr>
                            <!-- <tr>
                                <td style="padding: 0 0 20px 0;">
                                    <a href="#" style="background-color: #249AF3;color:#fff;padding:10px 50px;font-size: 13px;font-family: 'Lato', sans-serif;font-weight:400;width: 120px;display: block;margin:0 auto;border-radius:5px;">Reset You Password</a>
                                </td>
                            </tr> -->
                            <tr>
                                <td style="text-align: center;">
                                    {{ $reason }}
                                </td>
                            </tr>


                            <tr>
                                <td style="padding: 20px 0 20px 0;text-align:center;color:#7F8FA4;font-family: 'Lato', sans-serif;font-size: 16px;font-weight:400;">If you did not make this request, just ignore this email. Otherwise, <br/>
                                    please correct the above points and update profile again
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
