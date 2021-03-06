@php
    $target_layout = (\Auth::check()) ? 'teacher::layouts.teacher' : 'layouts.landing_page';
@endphp

@extends($target_layout)

@section('page-title')
    Courses by Nature
@endsection

@section('content')
    @if(\Auth::check())
        @if((\Auth::user()->profile_type == 'teacher') || (\Auth::user()->profile_type == 'admin'))
            {{-- Dashboard Stats - START --}}
            <div class="row mt-5 mb-4">
                {{-- Total Entolled Students Count - START --}}
                <div class="col-xl-3 col-lg-5 col-md-6 col-sm-12 col-12 mb-3">
                    <div class="card shadow bg_warning-s">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="bg_black-s rounded">
                                    <img src="{{ asset('assets/images/enroll_icon.svg') }}" class="px-2 py-2" alt="">
                                </div>
                                <div class="col-12">
                                    <span class="text-white font_w_700-s fs_smaller-s">Enrolled Students</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-text">
                            <div class="col">
                                <h4 class="font-weight-bold float-right mx-3 text-white">{{ $stats->total_students_count ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Total Entolled Students Count - END --}}

                {{-- Free Students Count (Students who enrolled in free courses) -  START --}}
                <div class="col-xl-3 col-lg-5 col-md-6 col-sm-12 col-12 mb-3">
                    <div class="card shadow bg_success-s">
                        <div class="card-body mb-1">
                            <div class="d-flex">
                                <div class="bg_black-s rounded">
                                    <img src="{{ asset('assets/images/reading_book.svg') }}" class="px-2 py-1" alt="">
                                </div>
                                <div class="col-12">
                                    <span class="text-white font_w_700-s fs_smaller-s">Free Students</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-text">
                            <div class="col">
                                <h4 class="font-weight-bold float-right mx-3 text-white">{{ $stats->total_free_students_count ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Free Students Count (Students who enrolled in free courses) -  END --}}

                {{-- Paid Video Courses Count - START --}}
                <div class="col-xl-3 col-lg-5 col-md-6 col-sm-12 col-12 mb-3">
                    <div class="card shadow bg_info-s">
                        <div class="card-body mb-1">
                            <div class="d-flex">
                                <div class="bg_black-s rounded">
                                    <img src="{{ asset('assets/images/video_course_icon.svg') }}" class="px-2 py-2" alt="">
                                </div>
                                <div class="col-12">
                                    <span class="text-white font_w_700-s fs_smaller-s">Paid Video Course</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-text">
                            <div class="col">
                                <h4 class="font-weight-bold float-right mx-3 text-white">{{ $stats->total_video_paid_courses_count ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Paid Video Courses Count - END --}}

                {{-- Online Courses Count - START --}}
                <div class="col-xl-3 col-lg-5 col-md-6 col-sm-12 col-12 mb-3">
                    <div class="card shadow bg_pink-s">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="bg_black-s rounded">
                                    <img src="{{ asset('assets/images/online_course_icon.svg') }}" class="px-2 py-1" alt="online-course-stats">
                                </div>
                                <div class="col-12">
                                    <span class="text-white font_w_700-s fs_smaller-s">Online Course</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-text">
                            <div class="col">
                                <h4 class="font-weight-bold float-right mx-3 text-white" id="course_stats_online_count-d">{{ $stats->total_online_courses_count ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Online Courses Count - END --}}

            </div>
            {{-- Dashboard Stats - END --}}
        @endif
    @endif
    @php
        $additional_class = 'mt-5';
        if(\Auth::check()){
            if((\Auth::user()->profile_type != 'teacher') && (\Auth::user()->profile_type != 'admin')){
                $additional_class = 'mt-5 mb-4';
            }
        }

    @endphp
    <div class="online_courses_container px-2 {{ $additional_class }}">
        <div class="container-fluid">
            {{--  Title of section and + btn - START  --}}
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 align-self-center">
                    <h3 class="top_courses_text-s">All {{ ucwords($course_nature) }} Courses</h3>
                </div>
            </div>
            {{--  Title of section and + btn - END  --}}

            <section class="pt-3 pb-3">
                <div class="row w-100">
                        @include('course::partials._course_listing', [
                            'courses' => $courses,
                            'section' => 'courses_by_nature',
                            'nature' => $course_nature
                        ])
                </div>
            </section>
        </div>
    </div>

    {{--  modals - END  --}}

@endsection

@section('footer-scripts')
    <script src="http://www.shieldui.com/shared/components/latest/js/shieldui-all.min.js"></script>
    {{--  <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    <script src="{{ asset('assets/js/manage_courses.js') }}"></script>  --}}
@endsection

@section('header-css')
    <link rel="stylesheet" href="http://www.shieldui.com/shared/components/latest/css/light-bootstrap/all.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/css/course.css') }}" />
@endsection
