@php
    $pageUrl = $_SERVER['REQUEST_URI'];

    $coursesLinks = ['/courses', 'view-course/'];
    $dashboardLinks = ['/dashboard'];
    $studentLinks = ['/student-list'];

    $privacyLinks = ['/privacy'];
    $aboutLinks = ['/about-us'];
    $quizLinks = ['/all-quizez', 'view-quiz/'];

    $calendarLinks = ['/activities'];
    $salesLinks = ['/sales-report'];
    $reportLinks = ['/general-report'];
    $paymentRefundLinks  = ['/payment-refund-policy'];
    $termsAndServices = ['/terms-and-services'];
    $cookiesPolicy = ['/cookies-policy'];

    $nonApprovedTeacher = ['/non-approved-teacher'];
    $nonApprovedTeacherCourses = ['/non-approved-teacher-courses'];

    $chatLinks = ['/chat'];
    $cmsAdditionalParams = isset(app('request')->last_page)? ['last_page' => app('request')->last_page] : [];
@endphp
    @if(\Auth::check())
        @if (request()->user()->profile->profile_type == 'admin')
            <a href="{{ route('adminDashboard')}}" class="list-group-item list-group-item-action p-3 @if( checkStringAgainstList($dashboardLinks, $pageUrl) ) active @endif">
                <img src="{{ asset('assets/images/home_icon.svg') }}" class="ml-3" width="25" alt="home" selected />
                <span class="px-3">Dashboard</span>
            </a>

            <a href="{{ route('listNonApprovedTeachers')}}" class="list-group-item d-flex list-group-item-action p-3 @if( checkStringAgainstList($nonApprovedTeacher, $pageUrl) ) active @endif">
                <div class="pt-2">
                    <img src="{{ asset('assets/images/timer.svg') }}" class="ml-3 mr-1" width="30" alt="home" selected />
                </div> &nbsp;
                <div class="pl-2">
                    <span >Non Approved Teacher</span>
                </div>
            </a>

            <a href="{{ route('getNonApprovedCourses')}}" class="list-group-item d-flex list-group-item-action p-3 @if( checkStringAgainstList($nonApprovedTeacherCourses, $pageUrl) ) active @endif">
                <div class="pt-2">
                    <img src="{{ asset('assets/images/waiting-list.svg') }}" class="ml-3 mr-1" width="25" alt="home" selected />
                </div>
                <div class="pl-3">
                    <span >Non Approved Teacher Courses</span>
                </div>
            </a>

        @else

            @if (request()->user()->profile->profile_type == 'student')
                <a href="{{ route('student.dashboard')}}" class="list-group-item list-group-item-action p-3 @if( checkStringAgainstList($dashboardLinks, $pageUrl) ) active @endif">
                    <img src="{{ asset('assets/images/home_icon.svg') }}" class="ml-3" width="25" alt="home" selected />
                    <span class="px-3">Dashboard</span>
                </a>

                <a href="{{ route('course.listTopCourses') }}" class="list-group-item list-group-item-action p-3 @if( checkStringAgainstList($coursesLinks, $pageUrl) ) active @endif">
                    <img src="{{ asset('assets/images/course_icon.svg') }}" class="ml-3" width="25" alt="">
                    <span class="px-3">Courses</span>
                </a>
            @endif

            @if (request()->user()->profile->profile_type == 'teacher' && request()->user()->profile->approver_id != null)
                <a href="{{ route('teacher.dashboard')}}" class="list-group-item list-group-item-action p-3 @if( checkStringAgainstList($dashboardLinks, $pageUrl) ) active @endif">
                    <img src="{{ asset('assets/images/home_icon.svg') }}" class="ml-3" width="25" alt="home" selected />
                    <span class="px-3">Dashboard</span>
                </a>

                <a href="{{ route('course.listTopCourses') }}" class="list-group-item list-group-item-action p-3 @if( checkStringAgainstList($coursesLinks, $pageUrl) ) active @endif">
                    <img src="{{ asset('assets/images/course_icon.svg') }}" class="ml-3" width="25" alt="">
                    <span class="px-3">Courses</span>
                </a>
                <a href="{{ route('student.student-list') }}" class="list-group-item list-group-item-action p-3 @if( checkStringAgainstList($studentLinks, $pageUrl) ) active @endif">
                    <img src="{{ asset('assets/images/student_icon.svg') }}" class="ml-3 filter-green-student" width="25" alt="">
                    <span class="px-3">Students</span>
                </a>
                <a href="{{ route('quiz.index') }}" class="list-group-item list-group-item-action p-3 @if( checkStringAgainstList($quizLinks, $pageUrl) ) active @endif">
                    <img src="{{ asset('assets/images/quiz_icon.svg') }}" class="ml-3" width="25" alt="">
                    <span class="px-3">Quiz</span>
                </a>
                <a href="{{ route('report.general') }}" class="list-group-item list-group-item-action p-3 @if( checkStringAgainstList($reportLinks, $pageUrl) ) active @endif">
                    <img src="{{ asset('assets/images/report_icon.svg') }}" class="ml-3" width="25" alt="">
                    <span class="px-3">Report</span>
                </a>
                <a href="{{ route('report.sales') }}" class="list-group-item list-group-item-action p-3 @if( checkStringAgainstList($salesLinks, $pageUrl) ) active @endif">
                    <img src="{{ asset('assets/images/sales-report_icon.svg') }}" class="ml-3" width="25" alt="">
                    <span class="px-3">Sales Report</span>
                </a>
            @endif

            @if (request()->user()->profile->profile_type != 'teacher' || (request()->user()->profile->profile_type == 'teacher' && request()->user()->profile->approver_id != null))
                <a href="{{ route('chat.index') }}" class="list-group-item list-group-item-action p-3 @if( checkStringAgainstList($chatLinks, $pageUrl) ) active @endif">
                    <img src="{{ asset('assets/images/side_bar_chat_icon.svg') }}" class="ml-3" width="25" alt="">
                    <span class="px-3">Chat</span>
                </a>
                <a href="{{ route('activity.index') }}" class="list-group-item list-group-item-action p-3 @if( checkStringAgainstList($calendarLinks, $pageUrl) ) active @endif">
                    <img src="{{ asset('assets/images/calendar_icon.svg') }}" class="ml-3" width="25" alt="">
                    <span class="px-3">Calendar</span>
                </a>
            @endif
        @endif
    @endif
    {{--  <a href="javascript:void(0)" class="list-group-item list-group-item-action p-3">
        <img src="{{ asset('assets/images/certificate_icon.svg') }}" class="ml-3" width="25" alt="">
        <span class="px-3">Certification</span>
    </a>  --}}
    {{--  <a href="javascript:void(0)" class="list-group-item list-group-item-action p-3">
        <img src="{{ asset('assets/images/payment_icon.svg') }}" class="ml-3" width="25" alt="">
        <span class="px-3">Payment</span>
    </a>  --}}

    @if(!\Auth::check())
        <a href="{{ route('course.listTopCourses') }}" class="list-group-item list-group-item-action p-3 @if( checkStringAgainstList($coursesLinks, $pageUrl) ) active @endif">
            <img src="{{ asset('assets/images/course_icon.svg') }}" class="ml-3" width="25" alt="">
            <span class="px-3">Courses</span>
        </a>
    @endif
    <a href="{{ route('cms.privacy-policy', $cmsAdditionalParams) }}" class="list-group-item list-group-item-action cms_pages-d p-3 @if( checkStringAgainstList($privacyLinks, $pageUrl) ) active @endif">
        <img src="{{ asset('assets/images/privacy_icon.svg') }}" class="ml-3" width="25" alt="privacy-icon">
        <span class="px-3">Privacy</span>
    </a>
    <!-- //payment refund tab -->
    <a href="{{ route('cms.payment-refund-policy', $cmsAdditionalParams) }}" class="list-group-item d-flex list-group-item-action cms_pages-d p-3 @if( checkStringAgainstList($paymentRefundLinks, $pageUrl) ) active @endif">
        <div class="pt-2">
            <img src="{{ asset('assets/images/refund.svg') }}" class="ml-3 " width="25" alt="privacy-icon">
        </div>&nbsp;
        <div class="pl-3">
            <span>Payment Refund Policy</span>
        </div>

    </a>
    <!-- //terms and services tab -->
    <a href="{{ route('cms.terms-and-services', $cmsAdditionalParams) }}" class="list-group-item d-flex list-group-item-action cms_pages-d p-3 @if( checkStringAgainstList($termsAndServices, $pageUrl) ) active @endif">
        <div class="pt-2">
        <img src="{{ asset('assets/images/insurance-policy.svg') }}" class="ml-3" width="25" alt="privacy-icon">
        </div>&nbsp;
        <div class="pl-3">
            <span>Terms And Services</span>
        </div>
    </a>
    <!-- //cookies policy tab -->
    @php

    @endphp
    <a href="{{ route('cms.cookies-policy', $cmsAdditionalParams) }}" class="list-group-item list-group-item-action cms_pages-d p-3 @if( checkStringAgainstList($cookiesPolicy, $pageUrl) ) active @endif">
        <img src="{{ asset('assets/images/cookies.svg') }}" class="ml-3" width="30" alt="privacy-icon">&nbsp;
        <span class="px-1">Cookies Policy</span>
    </a>
    <a href="{{ route('cms.about-us', $cmsAdditionalParams) }}" class="list-group-item list-group-item-action cms_pages-d p-3 @if( checkStringAgainstList($aboutLinks, $pageUrl) ) active @endif">
        <img src="{{ asset('assets/images/about_icon.svg') }}" class="ml-3" width="25" alt="about icon">
        <span class="px-3">About Us</span>
    </a>
    @if(\Auth::check())
        <a href="{{ route('signout') }}" class="list-group-item list-group-item-action logout_link-d p-3 my-5">
            <img src="{{ asset('assets/images/logout_icon.svg') }}" class="ml-3" width="25" alt="">
            <span class="px-3">Log Out</span>
        </a>
    @endif
