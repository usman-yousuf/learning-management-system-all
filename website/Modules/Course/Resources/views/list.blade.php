@extends('teacher::layouts.teacher')

@section('content')
    {{-- Dashboard Stats - START --}}
    <div class="row mt-5 mb-4">
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
                        <img src="{{ asset('assets/images/reading_book.svg') }}" class="px-2 py-1" alt="">
                        <div class="col-10">
                            <span class="text-white font_w_700-s">Free Students</span>
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
                        <img src="{{ asset('assets/images/video_course_icon.svg') }}" class="px-2 py-2" alt="">
                        <div class="col-12">
                            <span class="text-white">Paid Video Course</span>
                        </div>
                    </div>
                </div>
                <div class="card-text">
                    <div class="col">
                        <h4 class="font-weight-bold float-right mx-3 text-white">{{ $stats->total_online_paid_courses_count ?? 0 }}</h4>
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
                        <h4 class="font-weight-bold float-right mx-3 text-white" id="course_stats_online_count-d">{{ $stats->total_online_courses_count ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>
        {{-- Online Courses Count - END --}}

    </div>
    {{-- Dashboard Stats - END --}}

    <div class="online_courses_container">
        {{--  Title of section and + btn - START  --}}
        <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 align-self-center">
                <h3 class="top_courses_text-s">All {{ ucwords($course_nature) }} Courses</h3>
            </div>
        </div>
        {{--  Title of section and + btn - END  --}}

        <section class="pt-5 pb-5">
            <div class="row">
                @forelse ($courses->courses as $item)
                    @php
                        // $item = (object)$item;
                        // dd($item);
                    @endphp
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="card">
                            <img class="img-fluid mx-auto img_max_x_200-s" alt="course-image" src="{{ getFileUrl($item->course_image, null, 'course') }}">                                            <!-- ------card content---- -->
                            <div class="">
                                <div class="d-flex mt-3 card_design_text-s">
                                    <div class="container">
                                        {{--  title and category - START  --}}
                                        <div class="row">
                                            <div class="col">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h6><a href="{{ route('course.view', ['uuid' => $item->uuid]) }}" class='no_link-s'>{{ $item->title ?? '(not set)' }}</a></h6>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <span>{{ ucwords($item->category->name ?? '(category not set)') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col text-right">
                                                <a href="javascript:void(0)" class="btn btn px-lg-1 px-xl-3 course_pay_btn-s" disbaled="disbaled">Paid</a>
                                            </div>
                                        </div>
                                        {{--  title and category - END  --}}

                                        <div class="row pt-3 pb-3">
                                            <div class="col-6 mb-3x">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <img src="{{ asset('assets/images/youtube_icon.svg') }}" class="" alt="">
                                                        <span class="mx-2">Video</span>

                                                        <br />
                                                        <img src="{{ asset('assets/images/enrollment_icon.svg') }}" class="" alt="">
                                                        <span class="mx-2"><strong>{{ getPeopleCount($item->students_count ?? 10) }}</strong> Students</span>

                                                        <br />
                                                        <img src="{{ asset('assets/images/dollar-icon.svg') }}" class="" alt="">
                                                        <span class="mx-2">Paid</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6 text-center text-lg-center text-xl-right">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <img src="{{ asset('assets/images/calendar_course_icon.svg') }}" class="" alt="">
                                                        <span class="ml-2">{{ date('d M Y', strtotime($item->start_date)) }}</span>
                                                    </div>

                                                    <div class="col-12 pr-xl-3">
                                                        <h6 class=" mb-0">to</h6>
                                                    </div>

                                                    <div class="col-12">
                                                        <img src="{{ asset('assets/images/calendar_course_icon.svg') }}" class="" alt="">
                                                        <span class="ml-2">{{ date('d M Y', strtotime($item->end_date ?? 'now')) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center mt-4 mb-4">
                        <p class="w-100 text-center">
                            <strong>
                                No Course(s) Found
                            </strong>
                        </p>
                    </div>
                @endforelse

            </div>
        </section>

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
