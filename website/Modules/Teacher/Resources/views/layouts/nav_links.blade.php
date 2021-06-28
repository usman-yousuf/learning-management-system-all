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

    $chatLinks = ['/chat'];
@endphp

    @if (request()->user()->profile->profile_type == 'student')
        <a href="{{ route('student.dashboard')}}" class="list-group-item list-group-item-action p-3 @if( checkStringAgainstList($dashboardLinks, $pageUrl) ) active @endif">
            <img src="{{ asset('assets/images/home_icon.svg') }}" class="ml-3" width="25" alt="home" selected />
            <span class="px-3">Dashboard</span>
        </a>

        <a href="{{ route('student.courseDetail') }}" class="list-group-item list-group-item-action p-3 @if( checkStringAgainstList($coursesLinks, $pageUrl) ) active @endif">
            <img src="{{ asset('assets/images/course_icon.svg') }}" class="ml-3" width="25" alt="">
            <span class="px-3">Courses</span>
        </a>
        <a href="{{ route('activity.index') }}" class="list-group-item list-group-item-action p-3 @if( checkStringAgainstList($calendarLinks, $pageUrl) ) active @endif">
            <img src="{{ asset('assets/images/calendar_icon.svg') }}" class="ml-3" width="25" alt="">
            <span class="px-3">Calendar</span>
        </a>
    @endif

    @if (request()->user()->profile->profile_type == 'teacher')
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
        {{--  <a href="{{ route('chat.index') }}" class="list-group-item list-group-item-action p-3 @if( checkStringAgainstList($chatLinks, $pageUrl) ) active @endif">
            <img src="{{ asset('assets/images/side_bar_chat_icon.svg') }}" class="ml-3" width="25" alt="">
            <span class="px-3">Chat</span>
        </a>  --}}
        <a href="{{ route('activity.index') }}" class="list-group-item list-group-item-action p-3 @if( checkStringAgainstList($calendarLinks, $pageUrl) ) active @endif">
            <img src="{{ asset('assets/images/calendar_icon.svg') }}" class="ml-3" width="25" alt="">
            <span class="px-3">Calendar</span>
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
    {{--  <a href="javascript:void(0)" class="list-group-item list-group-item-action p-3">
        <img src="{{ asset('assets/images/certificate_icon.svg') }}" class="ml-3" width="25" alt="">
        <span class="px-3">Certification</span>
    </a>  --}}
    {{--  <a href="javascript:void(0)" class="list-group-item list-group-item-action p-3">
        <img src="{{ asset('assets/images/payment_icon.svg') }}" class="ml-3" width="25" alt="">
        <span class="px-3">Payment</span>
    </a>  --}}
    <a href="{{ route('cms.privacy-policy') }}" class="list-group-item list-group-item-action p-3 @if( checkStringAgainstList($privacyLinks, $pageUrl) ) active @endif">
        <img src="{{ asset('assets/images/privacy_icon.svg') }}" class="ml-3" width="25" alt="privacy-icon">
        <span class="px-3">Privacy</span>
    </a>
    <a href="{{ route('cms.about-us') }}" class="list-group-item list-group-item-action p-3 @if( checkStringAgainstList($aboutLinks, $pageUrl) ) active @endif">
        <img src="{{ asset('assets/images/about_icon.svg') }}" class="ml-3" width="25" alt="about icon">
        <span class="px-3">About Us</span>
    </a>
    <a href="{{ route('signout') }}" class="list-group-item list-group-item-action p-3 my-5">
        <img src="{{ asset('assets/images/logout_icon.svg') }}" class="ml-3" width="25" alt="">
        <span class="px-3">Log Out</span>
    </a>
