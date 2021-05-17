@extends('teacher::layouts.teacher')

@section('content')
    {{-- Dashboard Stats - START --}}
    <div class="row my-5">
        {{-- Total Entolled Students Count - START --}}
        <div class="col-xl-3 col-lg-5 col-md-6 col-sm-12 col-12 mb-3">
            <div class="card shadow bg_warning-s">
                <div class="card-body">
                    <div class="d-flex">
                        <img src="{{ asset('assets/images/enroll_icon.svg') }}" class="px-2 py-2" alt="">
                        <div class="col-12">
                            <span class="text-white font_w_700-s">Enrolled Students</span>
                        </div>
                    </div>
                </div>
                <div class="card-text">
                    <div class="col">
                        <h4 class="font-weight-bold float-right mx-3 text-white">100</h4>
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
                        <img src="{{ asset('assets/images/reading_book.svg') }}" class="px-2 py-1" alt="">
                        <div class="col-10">
                            <span class="text-white font_w_700-s">Free Students</span>
                        </div>
                    </div>
                </div>
                <div class="card-text">
                    <div class="col">
                        <h4 class="font-weight-bold float-right mx-3 text-white">200</h4>
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
                        <img src="{{ asset('assets/images/video_course_icon.svg') }}" class="px-2 py-2" alt="">
                        <div class="col-12">
                            <span class="text-white">Paid Video Course</span>
                        </div>
                    </div>
                </div>
                <div class="card-text">
                    <div class="col">
                        <h4 class="font-weight-bold float-right mx-3 text-white">150</h4>
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
                        <img src="{{ asset('assets/images/online_course_icon.svg') }}" class="px-2 py-1" alt="online-course-stats">
                        <div class="col-10">
                            <span class="text-white font_w_700-s">Online Course</span>
                        </div>
                    </div>
                </div>
                <div class="card-text">
                    <div class="col">
                        <h4 class="font-weight-bold float-right mx-3 text-white" id="course_stats_online_count-d">100</h4>
                    </div>
                </div>
            </div>
        </div>
        {{-- Online Courses Count - END --}}

    </div>
    {{-- Dashboard Stats - END --}}

@endsection
