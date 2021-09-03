@extends('teacher::layouts.teacher')
@section('page-title')
    Admin Dashboard
@endsection

@section('content')
    <div class="container-fluid px-lg-5 px-md-4 py-4">
        <div class="row mt-3">
            <div class="col-12">
                <!--Heading-->
                <h4><strong>Dashboard</strong></h4>
            </div>
        </div>

        <div class="row mt-3 mb-3">
            {{-- Total Entolled Students Count - START --}}
            <div class="col-xl-3 col-lg-5 col-md-6 col-sm-12 col-12 mb-3">
                <div class="card shadow bg_warning-s">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="bg_black-s rounded">
                                <img src="{{ asset('assets/images/enroll_icon.svg') }}" class="px-2 py-2" alt="">
                            </div>
                            <div class="col-11">
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

                            <div class="col-10">
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
                            <div class="col-11 d-flex text-wrap text-break">
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
                            <div class="col-10">
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

        <div class="row mt-2 mb-3">

            {{-- Teachers Count (Students who enrolled in free courses) -  START --}}
            <div class="col-xl-3 col-lg-5 col-md-6 col-sm-12 col-12 mb-3">
                <div class="card shadow bg_success-s">
                    <div class="card-body mb-1">
                        <div class="d-flex">
                            <div class="bg_black-s rounded">
                                <img src="{{ asset('assets/images/reading_book.svg') }}" class="px-2 py-1" alt="">
                            </div>

                            <div class="col-10">
                                <span class="text-white font_w_700-s fs_smaller-s">Teachers</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-text">
                        <div class="col">
                            <h4 class="font-weight-bold float-right mx-3 text-white">{{ $stats->total_teachers_count ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Teachers Count (Students who enrolled in free courses) -  END --}}

            {{-- Completed Courses Count - START --}}
            <div class="col-xl-3 col-lg-5 col-md-6 col-sm-12 col-12 mb-3">
                <div class="card shadow bg_pink-s">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="bg_black-s rounded">
                                <img src="{{ asset('assets/images/online_course_icon.svg') }}" class="px-2 py-1" alt="online-course-stats">
                            </div>
                            <div class="col-10">
                                <span class="text-white font_w_700-s fs_smaller-s">Completed Course</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-text">
                        <div class="col">
                            <h4 class="font-weight-bold float-right mx-3 text-white" id="course_stats_online_count-d">{{ $stats->total_completed_courses_count ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Completed Courses Count - END --}}

            {{-- Total Paid Students Count - START --}}
            <div class="col-xl-3 col-lg-5 col-md-6 col-sm-12 col-12 mb-3">
                <div class="card shadow bg_warning-s">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="bg_black-s rounded">
                                <img src="{{ asset('assets/images/enroll_icon.svg') }}" class="px-2 py-2" alt="">
                            </div>
                            <div class="col-11">
                                <span class="text-white font_w_700-s fs_smaller-s">Paying Students</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-text">
                        <div class="col">
                            <h4 class="font-weight-bold float-right mx-3 text-white">{{ $stats->total_paid_students_count ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Total Paid Students Count - END --}}

            {{-- Video Courses Count - START --}}
            <div class="col-xl-3 col-lg-5 col-md-6 col-sm-12 col-12 mb-3">
                <div class="card shadow bg_info-s">
                    <div class="card-body mb-1">
                        <div class="d-flex">
                            <div class="bg_black-s rounded">
                                <img src="{{ asset('assets/images/video_course_icon.svg') }}" class="px-2 py-2" alt="">
                            </div>
                            <div class="col-11 d-flex text-wrap text-break">
                                <span class="text-white font_w_700-s fs_smaller-s">Video Course</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-text">
                        <div class="col">
                            <h4 class="font-weight-bold float-right mx-3 text-white">{{ $stats->total_video_courses_count ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Video Courses Count - END --}}
        </div>

        <div class="row mt-2 mb-3">

            {{-- Online Paid Courses Count - START --}}
            <div class="col-xl-3 col-lg-5 col-md-6 col-sm-12 col-12 mb-3">
                <div class="card shadow bg_pink-s">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="bg_black-s rounded">
                                <img src="{{ asset('assets/images/online_course_icon.svg') }}" class="px-2 py-1" alt="online-course-stats">
                            </div>
                            <div class="col-10">
                                <span class="text-white font_w_700-s fs_smaller-s">Online Paid Course</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-text">
                        <div class="col">
                            <h4 class="font-weight-bold float-right mx-3 text-white" id="course_stats_online_count-d">{{ $stats->total_online_paid_courses_count ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Online Paid Courses Count - END --}}

            {{-- Parents Count  -  START --}}
            <div class="col-xl-3 col-lg-5 col-md-6 col-sm-12 col-12 mb-3">
                <div class="card shadow bg_success-s">
                    <div class="card-body mb-1">
                        <div class="d-flex">
                            <div class="bg_black-s rounded">
                                <img src="{{ asset('assets/images/reading_book.svg') }}" class="px-2 py-1" alt="">
                            </div>

                            <div class="col-10">
                                <span class="text-white font_w_700-s fs_smaller-s">Parents</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-text">
                        <div class="col">
                            <h4 class="font-weight-bold float-right mx-3 text-white">{{ $stats->total_parents_count ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Parents Count  -  END --}}

            {{-- Free Video Courses Count - START --}}
            <div class="col-xl-3 col-lg-5 col-md-6 col-sm-12 col-12 mb-3">
                <div class="card shadow bg_info-s">
                    <div class="card-body mb-1">
                        <div class="d-flex">
                            <div class="bg_black-s rounded">
                                <img src="{{ asset('assets/images/video_course_icon.svg') }}" class="px-2 py-2" alt="">
                            </div>
                            <div class="col-11 d-flex text-wrap text-break">
                                <span class="text-white font_w_700-s fs_smaller-s">Free Video Course</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-text">
                        <div class="col">
                            <h4 class="font-weight-bold float-right mx-3 text-white">{{ $stats->total_video_free_courses_count ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Free Video Courses Count - END --}}
        </div>
    </div>

@endsection

@section('footer-scripts')

    <script>
        let ADMIN_URL= "{{ route('adminDashboard') }}";
    </script>
    <script type="text/javascript" src='{{ asset('assets/js/admin.js') }}'></script>

@endsection
