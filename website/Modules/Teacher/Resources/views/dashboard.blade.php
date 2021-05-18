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
            @php
                // dd($top_courses);
            @endphp

            <!-- For LARGE SCREEN - START -->
            @foreach ($top_courses['courses']->chunk(3) as $three)
                <div class="col-12 d-none d-lg-block">
                    <div id="carouselExampleIndicators2" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
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
                                                                <div class="col-8">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <h6>{{ $item->name ?? '(not set)' }}</h6>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <span>{{ $item->category->name ?? '(category not set)' }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-4 text-right">
                                                                    <a href="javascript:void(0)" class="btn btn px-lg-1 px-xl-3 course_pay_btn-s" disbaled="disbaled">Paid</a>
                                                                </div>
                                                            </div>
                                                            {{--  title and category - END  --}}

                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <img src="{{ asset('assets/images/youtube_icon.svg') }}" class="" alt="">
                                                                    <span class="mx-2">Video Course</span>
                                                                </div>
                                                                <div class="col-6">
                                                                    <img src="{{ asset('assets/images/calendar_course_icon.svg') }}" class="" alt="">
                                                                    <span class="ml-2">01 Feb 2021</span>
                                                                    <h6 class="text-xl-right mb-0">to</h6>
                                                                    <img src="{{ asset('assets/images/calendar_course_icon.svg') }}" class="" alt="">
                                                                    <span class="ml-2">01 Feb 2021</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- ------card content End---- -->
                                                <!-- ------card video & enrollment---- -->
                                                <div class="">
                                                    <div class="d-flex mt-3">
                                                        <div class="col-7 card_video_text-s">
                                                            <div class="mb-3 course_video_link-s">
                                                                <a href="">
                                                                    <img src="assets/preview/youtube_icon.svg" class="" alt="">
                                                                    <span class="mx-2">Video Course</span>
                                                                </a>
                                                            </div>
                                                            <div class="mb-3 course_student_link-s">
                                                                <a href="">
                                                                    <img src="assets/preview/enrollment_icon.svg" class="" alt="">
                                                                    <span class="mx-2">Enrolled students: 40</span>
                                                                </a>
                                                            </div>
                                                        </div>

                                                        <div class="col-5 text-lg-right card_date_text-s">
                                                            <img src="assets/preview/calendar_course_icon.svg" class="" alt="">
                                                            <span class="ml-2">01 Feb 2021</span>
                                                            <h6 class="text-xl-right mb-0">to</h6>
                                                            <img src="assets/preview/calendar_course_icon.svg" class="" alt="">
                                                            <span class="ml-2">01 Feb 2021</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- ------card video & enrollment End---- -->
                                            </div>
                                        </div>
                                        <!-- carousal item End -->
                                    @endforeach
                                </div>
                            </div>
                            {{--  show 3 item for large screen - END  --}}
                        </div>
                    </div>
                </div>
            @endforeach
            <!-- For LARGE SCREEN - END -->

            <!-- FOR MEDIUM SCREEN - START -->
            <div class="col-12 d-none d-sm-block d-lg-none">
                <div id="carouselExampleIndicators2" class="carousel slide" data-ride="carousel">

                    <div class="carousel-inner">
                        <!-- -----------1ST carousel-item START----------- -->
                        <div class="carousel-item active">
                            <div class="row">
                                <!-- card 1  -->
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <img class="img-fluid" alt="100%x280" src="assets/preview/card1.png">
                                        <!-- ------card content---- -->
                                        <div class="">
                                            <div class="d-flex mt-3 card_design_text-s">
                                                <div class="row">
                                                    <div class="col-8 ml-2">
                                                        <h6>Website Designing</h6>
                                                    </div>
                                                    <div class="col-10 ml-2">
                                                        <span>Landing Page Design</span>
                                                    </div>
                                                </div>

                                                <div class="col-3 text-right mr-2">
                                                    <a href="" class="btn btn px-sm-1 px-lg-1 px-xl-4 course_pay_btn-s">Paid</a>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- ------card content End---- -->
                                        <!-- ------card video & enrollment---- -->
                                        <div class="">
                                            <div class="d-flex mt-3">
                                                <div class="col-7 card_video_text-s">
                                                    <div class="mb-3 course_video_link-s">
                                                        <a href="">
                                                            <img src="assets/preview/youtube_icon.svg" class="" alt="">
                                                            <span class="mx-2">Video Course</span>
                                                        </a>
                                                    </div>
                                                    <div class="mb-3 course_student_link-s">
                                                        <a href="">
                                                            <img src="assets/preview/enrollment_icon.svg" class="" alt="">
                                                            <span class="mx-2">Enrolled students: 40</span>
                                                        </a>
                                                    </div>
                                                </div>

                                                <div class="col-5 text-lg-right card_date_text-s">
                                                    <img src="assets/preview/calendar_course_icon.svg" class="" alt="">
                                                    <span class="ml-2">01 Feb 2021</span>
                                                    <h6 class="text-xl-right mb-0">to</h6>
                                                    <img src="assets/preview/calendar_course_icon.svg" class="" alt="">
                                                    <span class="ml-2">01 Feb 2021</span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- ------card video & enrollment End---- -->
                                    </div>
                                </div>
                                <!-- card 1 End -->

                            </div>
                        </div>
                        <!-- -----------1ST carousel-item END----------- -->
                    </div>
                </div>
            </div>
            <!-- FOR MEDIUM SCREEN - END -->


            <!-- FOR SMALL SCREEN - START -->
            <div class="col-12 d-block d-sm-none">
                <div id="carouselExampleIndicators2" class="carousel slide" data-ride="carousel">

                    <div class="carousel-inner">
                        <!-- -----------1ST carousel-item START----------- -->
                        <div class="carousel-item active">
                            <div class="row">
                                <!-- card 1  -->
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        <img class="img-fluid" alt="100%x280" src="assets/preview/card1.png">
                                        <!-- ------card content---- -->
                                        <div class="">
                                            <div class="d-flex mt-3 card_design_text-s">
                                                <div class="col-8">
                                                    <h6>Website Designing</h6>
                                                    <span>Landing Page Design</span>
                                                </div>

                                                <div class="col-4 text-right">
                                                    <a href="" class="btn btn px-lg-4 course_pay_btn-s">Paid</a>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- ------card content End---- -->
                                        <!-- ------card video & enrollment---- -->
                                        <div class="">
                                            <div class="d-flex mt-3">
                                                <div class="col-7 card_video_text-s">
                                                    <div class="mb-3 course_video_link-s">
                                                        <a href="">
                                                            <img src="assets/preview/youtube_icon.svg" class="" alt="">
                                                            <span class="mx-2">Video Course</span>
                                                        </a>
                                                    </div>
                                                    <div class="mb-3 course_student_link-s">
                                                        <a href="">
                                                            <img src="assets/preview/enrollment_icon.svg" class="" alt="">
                                                            <span class="mx-2">Enrolled students: 40</span>
                                                        </a>
                                                    </div>
                                                </div>

                                                <div class="col-5 text-lg-right card_date_text-s">
                                                    <img src="assets/preview/calendar_course_icon.svg" class="" alt="">
                                                    <span class="ml-2">01 Feb 2021</span>
                                                    <h6 class="text-xl-right mb-0">to</h6>
                                                    <img src="assets/preview/calendar_course_icon.svg" class="" alt="">
                                                    <span class="ml-2">01 Feb 2021</span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- ------card video & enrollment End---- -->
                                    </div>
                                </div>
                                <!-- card 1 End -->
                            </div>
                        </div>
                        <!-- -----------1ST carousel-item END----------- -->

                        <!-- -----------2ND carousel-item START----------- -->
                        <div class="carousel-item">
                            <div class="row">
                                <!--Medium Screen card 1  -->
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        <img class="img-fluid" alt="100%x280" src="assets/preview/card1.png">
                                        <!-- ------card content---- -->
                                        <div class="">
                                            <div class="d-flex mt-3 card_design_text-s">
                                                <div class="col-8">
                                                    <h6>Website Designing</h6>
                                                    <span>Landing Page Design</span>
                                                </div>

                                                <div class="col-4 text-right">
                                                    <a href="" class="btn btn px-lg-3 px-xl-4 course_pay_btn-s">Paid</a>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- ------card content End---- -->
                                        <!-- ------card video & enrollment---- -->
                                        <div class="">
                                            <div class="d-flex mt-3">
                                                <div class="col-7 card_video_text-s">
                                                    <div class="mb-3 course_video_link-s">
                                                        <a href="">
                                                            <img src="assets/preview/youtube_icon.svg" class="" alt="">
                                                            <span class="mx-2">Video Course</span>
                                                        </a>
                                                    </div>
                                                    <div class="mb-3 course_student_link-s">
                                                        <a href="">
                                                            <img src="assets/preview/enrollment_icon.svg" class="" alt="">
                                                            <span class="mx-2">Enrolled students: 40</span>
                                                        </a>
                                                    </div>
                                                </div>

                                                <div class="col-5 text-lg-right card_date_text-s">
                                                    <img src="assets/preview/calendar_course_icon.svg" class="" alt="">
                                                    <span class="ml-2">01 Feb 2021</span>
                                                    <h6 class="text-xl-right mb-0">to</h6>
                                                    <img src="assets/preview/calendar_course_icon.svg" class="" alt="">
                                                    <span class="ml-2">01 Feb 2021</span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- ------card video & enrollment End---- -->
                                    </div>
                                </div>
                                <!--Medium Screen card 1 End -->
                            </div>
                        </div>
                        <!-- -----------2ND carousel-item END----------- -->

                        <!-- -----------3RD carousel-item START----------- -->
                        <div class="carousel-item">
                            <div class="row">
                                <!-- card 1 Start  -->
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        <img class="img-fluid" alt="100%x280" src="assets/preview/card1.png">
                                        <!-- ------card content---- -->
                                        <div class="">
                                            <div class="d-flex mt-3 card_design_text-s">
                                                <div class="col-8">
                                                    <h6>Website Designing</h6>
                                                    <span>Landing Page Design</span>
                                                </div>

                                                <div class="col-4 text-right">
                                                    <a href="" class="btn btn px-lg-4 course_pay_btn-s">Paid</a>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- ------card content End---- -->
                                        <!-- ------card video & enrollment---- -->
                                        <div class="">
                                            <div class="d-flex mt-3">
                                                <div class="col-7 card_video_text-s">
                                                    <div class="mb-3 course_video_link-s">
                                                        <a href="">
                                                            <img src="assets/preview/youtube_icon.svg" class="" alt="">
                                                            <span class="mx-2">Video Course</span>
                                                        </a>
                                                    </div>
                                                    <div class="mb-3 course_student_link-s">
                                                        <a href="">
                                                            <img src="assets/preview/enrollment_icon.svg" class="" alt="">
                                                            <span class="mx-2">Enrolled students: 40</span>
                                                        </a>
                                                    </div>
                                                </div>

                                                <div class="col-5 text-lg-right card_date_text-s">
                                                    <img src="assets/preview/calendar_course_icon.svg" class="" alt="">
                                                    <span class="ml-2">01 Feb 2021</span>
                                                    <h6 class="text-xl-right mb-0">to</h6>
                                                    <img src="assets/preview/calendar_course_icon.svg" class="" alt="">
                                                    <span class="ml-2">01 Feb 2021</span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- ------card video & enrollment End---- -->
                                    </div>
                                </div>
                                <!-- card 1 End -->
                            </div>
                        </div>
                        <!-- -----------3RD carousel-item END----------- -->

                        <!-- -----------4TH carousel-item START----------- -->
                        <div class="carousel-item">
                            <div class="row">
                                <!-- card 1 Start  -->
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        <img class="img-fluid" alt="100%x280" src="assets/preview/card3.png">
                                        <!-- ------card content---- -->
                                        <div class="">
                                            <div class="d-flex mt-3 card_design_text-s">
                                                <div class="col-8">
                                                    <h6>Mobile app Designing</h6>
                                                    <span>Lyout Design</span>
                                                </div>

                                                <div class="col-4 text-right">
                                                    <a href="" class="btn btn px-lg-4 course_free_btn-s">Free</a>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- ------card content End---- -->
                                        <!-- ------card video & enrollment---- -->
                                        <div class="">
                                            <div class="d-flex mt-3">
                                                <div class="col-7 card_video_text-s">
                                                    <div class="mb-3 course_free_video-s">
                                                        <a href="">
                                                            <img src="assets/preview/online_icon.svg" class="" alt="">
                                                            <span class="mx-2">Online Course</span>
                                                        </a>
                                                    </div>
                                                    <div class="mb-3 course_free_student-s">
                                                        <a href="">
                                                            <img src="assets/preview/reading.svg" class="" alt="">
                                                            <span class="mx-2">Free Students: 30</span>
                                                        </a>
                                                    </div>
                                                </div>

                                                <div class="col-5 text-lg-right card_date_text-s">
                                                    <img src="assets/preview/calendar_course_icon.svg" class="" alt="">
                                                    <span class="ml-2">01 Feb 2021</span>
                                                    <h6 class="text-xl-right mb-0">to</h6>
                                                    <img src="assets/preview/calendar_course_icon.svg" class="" alt="">
                                                    <span class="ml-2">01 Feb 2021</span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- ------card video & enrollment End---- -->
                                    </div>
                                </div>
                                <!-- card 1 End -->
                            </div>
                        </div>
                        <!-- -----------4TH carousel-item END----------- -->

                        <!-- -----------5TH carousel-item START----------- -->
                        <div class="carousel-item">
                            <div class="row">
                                <!-- card 1 Start  -->
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        <img class="img-fluid" alt="100%x280" src="assets/preview/card1.png">
                                        <!-- ------card content---- -->
                                        <div class="">
                                            <div class="d-flex mt-3 card_design_text-s">
                                                <div class="col-8">
                                                    <h6>Website Designing</h6>
                                                    <span>Landing Page Design</span>
                                                </div>

                                                <div class="col-4 text-right">
                                                    <a href="" class="btn btn px-lg-4 course_pay_btn-s">Paid</a>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- ------card content End---- -->
                                        <!-- ------card video & enrollment---- -->
                                        <div class="">
                                            <div class="d-flex mt-3">
                                                <div class="col-7 card_video_text-s">
                                                    <div class="mb-3 course_video_link-s">
                                                        <a href="">
                                                            <img src="assets/preview/youtube_icon.svg" class="" alt="">
                                                            <span class="mx-2">Video Course</span>
                                                        </a>
                                                    </div>
                                                    <div class="mb-3 course_student_link-s">
                                                        <a href="">
                                                            <img src="assets/preview/enrollment_icon.svg" class="" alt="">
                                                            <span class="mx-2">Enrolled students: 40</span>
                                                        </a>
                                                    </div>
                                                </div>

                                                <div class="col-5 text-lg-right card_date_text-s">
                                                    <img src="assets/preview/calendar_course_icon.svg" class="" alt="">
                                                    <span class="ml-2">01 Feb 2021</span>
                                                    <h6 class="text-xl-right mb-0">to</h6>
                                                    <img src="assets/preview/calendar_course_icon.svg" class="" alt="">
                                                    <span class="ml-2">01 Feb 2021</span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- ------card video & enrollment End---- -->
                                    </div>
                                </div>
                                <!-- card 1 End -->
                            </div>
                        </div>
                        <!-- -----------5TH carousel-item END----------- -->

                        <!-- -----------6TH carousel-item START----------- -->
                        <div class="carousel-item">
                            <div class="row">
                                <!-- card 1 Start  -->
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        <img class="img-fluid" alt="100%x280" src="assets/preview/card1.png">
                                        <!-- ------card content---- -->
                                        <div class="">
                                            <div class="d-flex mt-3 card_design_text-s">
                                                <div class="col-8">
                                                    <h6>Website Designing</h6>
                                                    <span>Landing Page Design</span>
                                                </div>

                                                <div class="col-4 text-right">
                                                    <a href="" class="btn btn px-lg-4 course_pay_btn-s">Paid</a>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- ------card content End---- -->
                                        <!-- ------card video & enrollment---- -->
                                        <div class="">
                                            <div class="d-flex mt-3">
                                                <div class="col-7 card_video_text-s">
                                                    <div class="mb-3 course_video_link-s">
                                                        <a href="">
                                                            <img src="assets/preview/youtube_icon.svg" class="" alt="">
                                                            <span class="mx-2">Video Course</span>
                                                        </a>
                                                    </div>
                                                    <div class="mb-3 course_student_link-s">
                                                        <a href="">
                                                            <img src="assets/preview/enrollment_icon.svg" class="" alt="">
                                                            <span class="mx-2">Enrolled students: 40</span>
                                                        </a>
                                                    </div>
                                                </div>

                                                <div class="col-5 text-lg-right card_date_text-s">
                                                    <img src="assets/preview/calendar_course_icon.svg" class="" alt="">
                                                    <span class="ml-2">01 Feb 2021</span>
                                                    <h6 class="text-xl-right mb-0">to</h6>
                                                    <img src="assets/preview/calendar_course_icon.svg" class="" alt="">
                                                    <span class="ml-2">01 Feb 2021</span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- ------card video & enrollment End---- -->
                                    </div>
                                </div>
                                <!-- card 1 End -->
                            </div>
                        </div>
                        <!-- -----------6TH carousel-item END----------- -->

                        <!-- -----------7TH carousel-item START----------- -->
                        <div class="carousel-item">
                            <div class="row">
                                <!-- card 1 Start  -->
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        <img class="img-fluid" alt="100%x280" src="assets/preview/card1.png">
                                        <!-- ------card content---- -->
                                        <div class="">
                                            <div class="d-flex mt-3 card_design_text-s">
                                                <div class="col-8">
                                                    <h6>Website Designing</h6>
                                                    <span>Landing Page Design</span>
                                                </div>

                                                <div class="col-4 text-right">
                                                    <a href="" class="btn btn px-lg-4 course_pay_btn-s">Paid</a>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- ------card content End---- -->
                                        <!-- ------card video & enrollment---- -->
                                        <div class="">
                                            <div class="d-flex mt-3">
                                                <div class="col-7 card_video_text-s">
                                                    <div class="mb-3 course_video_link-s">
                                                        <a href="">
                                                            <img src="assets/preview/youtube_icon.svg" class="" alt="">
                                                            <span class="mx-2">Video Course</span>
                                                        </a>
                                                    </div>
                                                    <div class="mb-3 course_student_link-s">
                                                        <a href="">
                                                            <img src="assets/preview/enrollment_icon.svg" class="" alt="">
                                                            <span class="mx-2">Enrolled students: 40</span>
                                                        </a>
                                                    </div>
                                                </div>

                                                <div class="col-5 text-lg-right card_date_text-s">
                                                    <img src="assets/preview/calendar_course_icon.svg" class="" alt="">
                                                    <span class="ml-2">01 Feb 2021</span>
                                                    <h6 class="text-xl-right mb-0">to</h6>
                                                    <img src="assets/preview/calendar_course_icon.svg" class="" alt="">
                                                    <span class="ml-2">01 Feb 2021</span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- ------card video & enrollment End---- -->
                                    </div>
                                </div>
                                <!-- card 1 End -->
                            </div>
                        </div>
                        <!-- -----------7TH carousel-item END----------- -->

                        <!-- -----------8TH carousel-item START----------- -->
                        <div class="carousel-item">
                            <div class="row">
                                <!-- card 1 Start  -->
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        <img class="img-fluid" alt="100%x280" src="assets/preview/card1.png">
                                        <!-- ------card content---- -->
                                        <div class="">
                                            <div class="d-flex mt-3 card_design_text-s">
                                                <div class="col-8">
                                                    <h6>Website Designing</h6>
                                                    <span>Landing Page Design</span>
                                                </div>

                                                <div class="col-4 text-right">
                                                    <a href="" class="btn btn px-lg-4 course_pay_btn-s">Paid</a>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- ------card content End---- -->
                                        <!-- ------card video & enrollment---- -->
                                        <div class="">
                                            <div class="d-flex mt-3">
                                                <div class="col-7 card_video_text-s">
                                                    <div class="mb-3 course_video_link-s">
                                                        <a href="">
                                                            <img src="assets/preview/youtube_icon.svg" class="" alt="">
                                                            <span class="mx-2">Video Course</span>
                                                        </a>
                                                    </div>
                                                    <div class="mb-3 course_student_link-s">
                                                        <a href="">
                                                            <img src="assets/preview/enrollment_icon.svg" class="" alt="">
                                                            <span class="mx-2">Enrolled students: 40</span>
                                                        </a>
                                                    </div>
                                                </div>

                                                <div class="col-5 text-lg-right card_date_text-s">
                                                    <img src="assets/preview/calendar_course_icon.svg" class="" alt="">
                                                    <span class="ml-2">01 Feb 2021</span>
                                                    <h6 class="text-xl-right mb-0">to</h6>
                                                    <img src="assets/preview/calendar_course_icon.svg" class="" alt="">
                                                    <span class="ml-2">01 Feb 2021</span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- ------card video & enrollment End---- -->
                                    </div>
                                </div>
                                <!-- card 1 End -->
                            </div>
                        </div>
                        <!-- -----------8TH carousel-item END----------- -->

                        <!-- -----------9TH carousel-item START----------- -->
                        <div class="carousel-item">
                            <div class="row">
                                <!-- card 1 Start  -->
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        <img class="img-fluid" alt="100%x280" src="assets/preview/card1.png">
                                        <!-- ------card content---- -->
                                        <div class="">
                                            <div class="d-flex mt-3 card_design_text-s">
                                                <div class="col-8">
                                                    <h6>Website Designing</h6>
                                                    <span>Landing Page Design</span>
                                                </div>

                                                <div class="col-4 text-right">
                                                    <a href="" class="btn btn px-lg-4 course_pay_btn-s">Paid</a>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- ------card content End---- -->
                                        <!-- ------card video & enrollment---- -->
                                        <div class="">
                                            <div class="d-flex mt-3">
                                                <div class="col-7 card_video_text-s">
                                                    <div class="mb-3 course_video_link-s">
                                                        <a href="">
                                                            <img src="assets/preview/youtube_icon.svg" class="" alt="">
                                                            <span class="mx-2">Video Course</span>
                                                        </a>
                                                    </div>
                                                    <div class="mb-3 course_student_link-s">
                                                        <a href="">
                                                            <img src="assets/preview/enrollment_icon.svg" class="" alt="">
                                                            <span class="mx-2">Enrolled students: 40</span>
                                                        </a>
                                                    </div>
                                                </div>

                                                <div class="col-5 text-lg-right card_date_text-s">
                                                    <img src="assets/preview/calendar_course_icon.svg" class="" alt="">
                                                    <span class="ml-2">01 Feb 2021</span>
                                                    <h6 class="text-xl-right mb-0">to</h6>
                                                    <img src="assets/preview/calendar_course_icon.svg" class="" alt="">
                                                    <span class="ml-2">01 Feb 2021</span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- ------card video & enrollment End---- -->
                                    </div>
                                </div>
                                <!-- card 1 End -->
                            </div>
                        </div>
                        <!-- -----------9TH carousel-item END----------- -->

                        <!-- -----------10TH carousel-item START----------- -->
                        <div class="carousel-item">
                            <div class="row">
                                <!-- card 1 Start  -->
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        <img class="img-fluid" alt="100%x280" src="assets/preview/card1.png">
                                        <!-- ------card content---- -->
                                        <div class="">
                                            <div class="d-flex mt-3 card_design_text-s">
                                                <div class="col-8">
                                                    <h6>Website Designing</h6>
                                                    <span>Landing Page Design</span>
                                                </div>

                                                <div class="col-4 text-right">
                                                    <a href="" class="btn btn px-lg-4 course_pay_btn-s">Paid</a>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- ------card content End---- -->
                                        <!-- ------card video & enrollment---- -->
                                        <div class="">
                                            <div class="d-flex mt-3">
                                                <div class="col-7 card_video_text-s">
                                                    <div class="mb-3 course_video_link-s">
                                                        <a href="">
                                                            <img src="assets/preview/youtube_icon.svg" class="" alt="">
                                                            <span class="mx-2">Video Course</span>
                                                        </a>
                                                    </div>
                                                    <div class="mb-3 course_student_link-s">
                                                        <a href="">
                                                            <img src="assets/preview/enrollment_icon.svg" class="" alt="">
                                                            <span class="mx-2">Enrolled students: 40</span>
                                                        </a>
                                                    </div>
                                                </div>

                                                <div class="col-5 text-lg-right card_date_text-s">
                                                    <img src="assets/preview/calendar_course_icon.svg" class="" alt="">
                                                    <span class="ml-2">01 Feb 2021</span>
                                                    <h6 class="text-xl-right mb-0">to</h6>
                                                    <img src="assets/preview/calendar_course_icon.svg" class="" alt="">
                                                    <span class="ml-2">01 Feb 2021</span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- ------card video & enrollment End---- -->
                                    </div>
                                </div>
                                <!-- card 1 End -->
                            </div>
                        </div>
                        <!-- -----------10TH carousel-item END----------- -->

                    </div>
                </div>
            </div>
            <!-- FOR SMALL SCREEN  - END -->

        </div>
    </section>


@endsection
