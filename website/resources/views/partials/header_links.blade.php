
@php
    $pageUrl = $_SERVER['REQUEST_URI'];

    $homeLinks = ['/home'];
    $coursesLinks = ['/courses', 'view-course/', 'our-courses'];
    $teachersLinks = ['/our-teachers'];
    $contactUsLinks = ['/contact-us'];
@endphp

    <!--home-->
    <li class="nav-item pl-2 pr-xl-4 pr-lg-3 pr-2">
        <a class="nav-link text-dark fs_19px-s green_bottom_on_hover-s @if( checkStringAgainstList($homeLinks, $pageUrl) ) active @endif" href="{{ route('home') }}"><strong>Home</strong></a>
    </li>
    <!--classes-->
    <li class="nav-item px-xl-4 px-lg-3 px-2">
        <a class="nav-link text-dark fs_19px-s green_bottom_on_hover-s @if( checkStringAgainstList($coursesLinks, $pageUrl) ) active @endif" href="{{ route('ourCourses') }}"><strong>Courses</strong></a>
    </li>
    <!--teacher-->
    <li class="nav-item px-xl-4 px-lg-3 px-2">
        <a class="nav-link text-dark fs_19px-s green_bottom_on_hover-s @if( checkStringAgainstList($teachersLinks, $pageUrl) ) active @endif" href="{{ route('ourTeachers') }}"><strong>Teachers</strong></a>
    </li>
    <!--contacts-->
    <li class="nav-item px-xl-4 px-lg-3 px-2">
        <a class="nav-link text-dark fs_19px-s green_bottom_on_hover-s @if( checkStringAgainstList($contactUsLinks, $pageUrl) ) active @endif" href="{{ route('contactUs', []) }}"><strong>Contact Us</strong></a>
    </li>
