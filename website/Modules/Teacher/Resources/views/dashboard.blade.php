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

    {{--  Title of section and + btn - START  --}}
    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 align-self-center">
            <h3 class="top_courses_text-s">Top 10 Courses</h3>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="float-md-right">
                <a href="" class="btn btn py-3 px-4 add_course_btn-s" data-toggle="modal" data-target="#activity_type_modal-d">
                    <img src="{{ asset('assets/images/add_btn_icon.svg') }}" width="20" id="add_course-d" class="mx-2" alt="+">
                    <span class="mx-2 text-white">Add Course</span>
                </a>
            </div>
        </div>
    </div>
    {{--  Title of section and + btn - END  --}}

    <section class="pt-5 pb-5">
        <div class="row">
            <!-- For LARGE SCREEN - START -->
            <div class="col-12 d-none d-lg-block">
                <div id="carouselExampleIndicators2" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        @foreach ($top_courses['courses']->chunk(3) as $three)
                            <div class="carousel-item @if ($loop->first) active @endif">
                                <div class="row">
                                    @foreach ($three as $item)
                                        <!-- carousal item - show 3 at a time -->
                                        <div class="col-md-4 mb-3">
                                            <div class="card">
                                                <img class="img-fluid mx-auto" alt="course-image" src="{{ getFileUrl($item->course_image, null, 'course') }}">
                                                <!-- ------card content---- -->
                                                <div class="">
                                                    <div class="d-flex mt-3 card_design_text-s">
                                                        <div class="container">
                                                            {{--  title and category - START  --}}
                                                            <div class="row">
                                                                <div class="col">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <h6>{{ $item->name ?? '(not set)' }}</h6>
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
                                                <!-- ------card content End---- -->
                                            </div>
                                        </div>
                                        <!-- carousal item End -->
                                    @endforeach
                                </div>
                            </div>
                            {{--  show 3 item for large screen - END  --}}
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- For LARGE SCREEN - END -->

            <!-- FOR MEDIUM SCREEN - START -->
            <div class="col-12 d-none d-sm-block d-lg-none">
                <div id="carouselExampleIndicators2" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        @foreach ($top_courses['courses']->chunk(2) as $two)
                            <div class="carousel-item @if ($loop->first) active @endif">
                                <div class="row">
                                    @foreach ($two as $item)
                                        <!-- carousal item - show 3 at a time -->
                                        <div class="col-md-6 mb-3">
                                            <div class="card">
                                                <img class="img-fluid mx-auto" alt="course-image" src="{{ getFileUrl($item->course_image, null, 'course') }}">
                                                <!-- ------card content---- -->
                                                <div class="">
                                                    <div class="d-flex mt-3 card_design_text-s">
                                                        <div class="container">
                                                            {{--  title and category - START  --}}
                                                            <div class="row">
                                                                <div class="col">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <h6>{{ $item->name ?? '(not set)' }}</h6>
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
                                                <!-- ------card content End---- -->
                                            </div>
                                        </div>
                                        <!-- carousal item End -->
                                    @endforeach
                                </div>
                            </div>
                            {{--  show 3 item for large screen - END  --}}
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- FOR MEDIUM SCREEN - END -->


            <!-- FOR SMALL SCREEN - START -->
            <div class="col-12 d-block d-sm-none">
                <div id="mobile_screenCarousal-d" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        @foreach ($top_courses['courses'] as $item)
                            <div class="carousel-item @if ($loop->first) active @endif">
                                <div class="row">
                                    <!-- carousal item - show 3 at a time -->
                                    <div class="col-md-12 mb-3">
                                        <div class="card">
                                            <img class="img-fluid mx-auto" alt="course-image" src="{{ getFileUrl($item->course_image, null, 'course') }}">
                                            <!-- ------card content---- -->
                                            <div class="">
                                                <div class="d-flex mt-3 card_design_text-s">
                                                    <div class="container">
                                                        {{--  title and category - START  --}}
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <h6>{{ $item->name ?? '(not set)' }}</h6>
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
                                            <!-- ------card content End---- -->
                                        </div>
                                    </div>
                                    <!-- carousal item End -->
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- FOR SMALL SCREEN  - END -->

        </div>
    </section>

    <section class='pt-5 pb-5'>
        <!-- ----graph Start---  -->
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading mb-2">
                        <h3>Video Course</h3>
                    </div>
                    <div class="panel-body shadow mb-5">
                        <div class="video_course_chart-d"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3>Online Courses</h3>
                    </div>
                    <div class="panel-body shadow">
                        <div class="online_course_chart-d"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ----graph End---  -->
    </section>

    {{--  modals - START  --}}

        @include('course::modals.course', [])


    {{--  modals - END  --}}

@endsection

@section('footer-scripts')
    <script src="http://www.shieldui.com/shared/components/latest/js/shieldui-all.min.js"></script>
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
@endsection

@section('header-css')
    <link rel="stylesheet" href="http://www.shieldui.com/shared/components/latest/css/light-bootstrap/all.min.css" />
@endsection
