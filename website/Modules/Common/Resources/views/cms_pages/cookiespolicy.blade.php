@extends('teacher::layouts.teacher')

@section('page-title')
    Cookies Policy
@endsection

@section('content')
    <div class="container-fluid py-4 pl-4 pr-4">
        <div class="row ">
            <div class="col-12">
                <h4 class="font_w_700-s">Cookies Policy</h4>
            </div>
            <div class="col-12">
                <p>
                    We would like you to know more about our use of cookies. That is why this document was drafted. It
                    explains what cookies are, how we use cookies, your choices regarding cookies and further information about cookies.
                </p>
                <p>This Cookies Policy is a part of our: 1) “Room A to Z” – <strong>Terms of Services</strong>; 2) “Room A to Z” – <strong>Payment Policy</strong>; and 3) “Room A to Z” – <strong>Privacy Policy</strong>, together with your consents comprising our agreement with you.</p>
                <h5><strong>“Room A to Z” does use cookies</strong></h5>
                <p>“Room A to Z” ("us", "we", or "our") use cookies and other technologies on the http://www.roomatoz.com website (the "Services"). By using the Services and clicking the respective buttons on our banners, you consent to the use of cookies for the purposes we describe in this policy.</p>
                <h5><strong>Cookies are…</strong></h5>
                <p> 
                    Those small files comprising bits of text are installed on your computer or mobile device each time
                    you open the respective Service. They enable our server to provide you with the information that is
                    customized with your needs when you use some service for the next time. Usually, your browser tells
                    our systems if any cookies files were installed in your computer and as a result, we may analyze the
                    information the cookies files give us.
                </p>
                <h5><strong>Kinds of cookies and purposes “Room A to Z” uses them</strong></h5>
                <p>“Room A to Z” uses the cookies for the following purposes when entering the Service:</p>
                <ul>
                    <li>to enhance your user experience: it enables “Room A to Z” to keep you sign in if you wish, so there is no need for you to re-enter the sign in information each time you come to “Room A to Z” Services;</li>
                    <li>to enable us to see your actions and preferences on the Services, analyze this information and provide you more relevant information on the Services you are currently using or may use;</li>
                    <li>to enable the behavioral advertising: if you searched something or someone on our Services some time ago, the cookies remembered that and would remind you on your related needs later.</li>
                </ul>
                <p>More specifically, here are the categories of cookies and description of their nature and purposes:</p>
                <table class="table">
                    <thead>
                        <tr>
                            <th class="col-4">Categories of Use</th>
                            <th class="col-8 align-self-center">Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Authentication</td>
                            <td>
                                If you are signed in to “Room A to Z”, cookies help us to customize and enhance your experience on our
                                Services.
                            </td>
                        </tr>
                        <tr>
                            <td>Preferences, features and services</td>
                            <td>Cookies can tell us which language you prefer and what your communications preferences are.</td>
                        </tr>
                        <tr>
                            <td>Performance, Analytics and Research</td>
                            <td>Cookies help us learn how well our site and plugins perform in different locations. We also use cookies to understand, improve, and research products, features, and services.</td>
                        </tr>
                        <tr>
                            <td>Advertising</td>
                            <td>“Room A to Z” may use cookies to provide you with more relevant advertising when you use the Services. Cookies will enable us to know more about your prior visits on “Room A to Z” and your activity within “Room A to Z”, including the date and time of visits, time spent on the Website as well as the pages viewed, and websites previously visited. “Room A to Z” may process such data through the external services, including but not limited to AdWords.</td>
                        </tr>
                    </tbody>
                </table>
                <h5><strong>Further opting out from the use of cookies</strong></h5>
                <p>If you would like to delete cookies or instruct your web browser to delete or refuse cookies, please visit the help pages of your web browser as specified below (separate support pages for each browser).</p>
                <p>Please note, however, that if you delete cookies or refuse to accept them, you might not be able to use all the features we offer, you may not be able to store your preferences, and some of our pages might not display properly since the Services will no longer be personalized to you. It may also stop you from saving customized settings.</p>
                <p>If you use “Room A to Z” without changing your browser settings, we will assume that you are happy to receive all cookies within our Services.</p>
                <h5><strong>Find more information about cookies</strong></h5>
                <p>You can learn more about cookies and the following third-party websites:</p>
                <ul>
                    <li>All About Cookies: http://www.allaboutcookies.org/</li>
                    <li>Network Advertising Initiative: http://www.networkadvertising.org/</li>
                </ul>
            </div>
        </div>
    </div>
@endsection


@section('footer-scripts')
    <script src="{{ asset('assets/js/manage_courses.js') }}"></script>
@endsection

@section('header-css')
    <link rel="stylesheet" href="{{ asset('assets/css/course.css') }}" />
@endsection


@push('header-scripts')
    <script>
        let modal_delete_outline_url = "{{ route('course.delete-outline') }}";
        let modal_delete_slot_url = "{{ route('course.delete-slot') }}";
        let modal_delete_video_content_url = "{{ route('course.delete-video-content') }}";
    </script>
@endpush
