@extends('teacher::layouts.teacher')
@section('page-title')
    Teacher Dashboard
@endsection
@section('content')
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
                            <span class="text-white font_w_700-s fs_smaller-s">Video Course</span>
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
    {{-- Dashboard Stats - END --}}

    {{--  Title of section and + btn - START  --}}
    <div class="row px-4">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 align-self-center">
            <h2 class="top_courses_text-s">Top 10 Courses</h2>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="float-md-right">
                <a href="javascript:void(0)" class="btn btn py-3 px-4 add_course_btn-s" data-toggle="modal" data-target="#activity_type_modal-d">
                    <img src="{{ asset('assets/images/add_btn_icon.svg') }}" width="20" id="add_course-d" class="mx-2" alt="+">
                    <span class="mx-2 text-white">Add Course</span>
                </a>
            </div>
        </div>
    </div>
    {{--  Title of section and + btn - END  --}}

    <section class="pt-5 pb-5 px-4">
        @if($top_courses['courses']->count())
            <div class="row">
                <!-- For Extra LARGE SCREEN - START -->
                <div class="col-12 d-none d-xl-block">
                    <div id="carouselExampleIndicators2" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            @foreach ($top_courses['courses']->chunk(3) as $three)
                                <div class="carousel-item @if ($loop->first) active @endif">
                                    <div class="row">
                                        @foreach ($three as $item)
                                            <!-- carousal item - show 3 at a time -->
                                            <div class="col-md-4 mb-3">
                                                <div class="card carousal_card-s">
                                                    <div class="carousal_item_image-s">
                                                        <img class="w-100" alt="course-image" src="{{ getFileUrl($item->course_image, null, 'course') }}" />
                                                        @if (null == $item->approver_id)
                                                            @if($item->is_approved)
                                                                <div class="text-center position-absolute py-1 under_review-label-s">Under Review</div>
                                                            @else
                                                                <div class="text-center position-absolute py-1 rejected_course-s">Rejected</div>
                                                            @endif
                                                        @endif
                                                    </div>

                                                    <!-- ------card content---- -->
                                                    <div class="">
                                                        <div class="d-flex mt-3 card_design_text-s">
                                                            <div class="container">
                                                                {{--  title and category - START  --}}
                                                                <div class="row">
                                                                    <div class="col-8">
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <h6><a href="{{ route('course.view', ['uuid' => $item->uuid]) }}" class='no_link-s hover_effect-s' title="{{ $item->title ?? '(not set)' }}" >{{ getTruncatedString($item->title ?? '(not set)', 20) }}</a></h6>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <span title="{{ ucwords($item->category->name ?? '(category not set)' ) }}">{{ getTruncatedString(ucwords($item->category->name ?? '(category not set)'), 20 ) }}</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col text-right">
                                                                        <a href="javascript:void(0)" class="btn btn px-lg-1 px-xl-3 {{ $item->is_course_free ? 'course_free_btn-s' : 'course_pay_btn-s' }}" disbaled="disbaled">{{ $item->is_course_free ? 'Free' : 'Paid' }}</a>
                                                                    </div>
                                                                </div>
                                                                {{--  title and category - END  --}}

                                                                <div class="row pt-3 pb-3">
                                                                    <div class="col-6 mb-3x">
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <img src="{{ asset('assets/images/youtube_icon.svg') }}" class="" alt="">
                                                                                <span class="mx-2">{{ ucwords($item->nature) }}</span>

                                                                                <br />
                                                                                <img src="{{ asset('assets/images/enrollment_icon.svg') }}" class="py-1" alt="">
                                                                                <span class="mx-2"><strong>{{ getPeopleCount($item->students_count ?? 10) }}</strong> Students</span>

                                                                                <br />
                                                                                <img src="{{ asset('assets/images/dollar-icon.svg') }}" width="18" class="mr-xl-1 mr-lg-1 mr-md-0 mr-1" alt="price icon">
                                                                                <span class="mx-xl-2 mx-lg-2 mx-md-0 mx-2">{{ getCoursePriceWithUnit($item) }}</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-6 text-center text-lg-center text-xl-right">
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <img src="{{ asset('assets/images/calendar_course_icon.svg') }}" class="" alt="" />
                                                                                <span class="ml-2">{{ date('d M Y', strtotime($item->start_date)) }}</span>
                                                                            </div>

                                                                            <div class="col-12 pr-xl-3 py-1">
                                                                                <h6 class=" mb-0">to</h6>
                                                                            </div>

                                                                            <div class="col-12">
                                                                                <img src="{{ asset('assets/images/calendar_course_icon.svg') }}" class="" alt="" />
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
                <!-- For Extra LARGE SCREEN - END -->

                <!-- For LARGE SCREEN - START -->
                <div class="col-12 d-none d-xl-none d-lg-block">
                    <div id="carouselExampleIndicators2" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            @foreach ($top_courses['courses']->chunk(2) as $two)
                                <div class="carousel-item @if ($loop->first) active @endif">
                                    <div class="row">
                                        @foreach ($two as $item)
                                            <!-- carousal item - show 3 at a time -->
                                            <div class="col-md-6 mb-3">
                                                <div class="card carousal_card-s">
                                                    <div class="carousal_item_image-s">
                                                        <img class="w-100" alt="course-image" src="{{ getFileUrl($item->course_image, null, 'course') }}" />
                                                        @if (null == $item->approver_id)
                                                            @if($item->is_approved)
                                                                <div class="text-center position-absolute py-1 under_review-label-s">Under Review</div>
                                                            @else
                                                                <div class="text-center position-absolute py-1 rejected_course-s">Rejected</div>
                                                            @endif
                                                        @endif
                                                    </div>

                                                    <!-- ------card content---- -->
                                                    <div class="">
                                                        <div class="d-flex mt-3 card_design_text-s">
                                                            <div class="container">
                                                                {{--  title and category - START  --}}
                                                                <div class="row">
                                                                    <div class="col-8">
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <h6><a href="{{ route('course.view', ['uuid' => $item->uuid]) }}" class='no_link-s hover_effect-s' title="{{ $item->title ?? '(not set)' }}" >{{ getTruncatedString($item->title ?? '(not set)', 20) }}</a></h6>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                            <span title="{{ ucwords($item->category->name ?? '(category not set)' ) }}">{{ getTruncatedString(ucwords($item->category->name ?? '(category not set)'), 20 ) }}</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col text-right">
                                                                        <a href="javascript:void(0)" class="btn btn px-lg-1 px-xl-3 {{ $item->is_course_free ? 'course_free_btn-s' : 'course_pay_btn-s' }}" disbaled="disbaled">{{ $item->is_course_free ? 'Free' : 'Paid' }}</a>
                                                                    </div>
                                                                </div>
                                                                {{--  title and category - END  --}}

                                                                <div class="row pt-3 pb-3">
                                                                    <div class="col-6 mb-3x">
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <img src="{{ asset('assets/images/youtube_icon.svg') }}" class="" alt="">
                                                                                <span class="mx-2">{{ ucwords($item->nature) }}</span>

                                                                                <br />
                                                                                <img src="{{ asset('assets/images/enrollment_icon.svg') }}" class="py-1" alt="">
                                                                                <span class="mx-2"><strong>{{ getPeopleCount($item->students_count ?? 10) }}</strong> Students</span>

                                                                                <br />
                                                                                <img src="{{ asset('assets/images/dollar-icon.svg') }}" width="18" class="mr-xl-1 mr-lg-1 mr-md-0 mr-1" alt="">
                                                                                <span class="mx-xl-2 mx-lg-2 mx-md-0 mx-2">{{ getCoursePriceWithUnit($item) }}</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-6 text-center text-lg-center text-xl-right">
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <img src="{{ asset('assets/images/calendar_course_icon.svg') }}" class="" alt="">
                                                                                <span class="ml-2">{{ date('d M Y', strtotime($item->start_date)) }}</span>
                                                                            </div>

                                                                            <div class="col-12 pr-xl-3 py-1">
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
                                {{--  show 2 item for large screen - END  --}}
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
                                                <div class="card carousal_card-s">
                                                    <div class="carousal_item_image-s">
                                                        <img class="w-100" alt="course-image" src="{{ getFileUrl($item->course_image, null, 'course') }}" />
                                                        @if (null == $item->approver_id)
                                                            @if($item->is_approved)
                                                                <div class="text-center position-absolute py-1 under_review-label-s">Under Review</div>
                                                            @else
                                                                <div class="text-center position-absolute py-1 rejected_course-s">Rejected</div>
                                                            @endif
                                                        @endif
                                                    </div>
                                                    <div class="">
                                                        <div class="d-flex mt-3 card_design_text-s">
                                                            <div class="container">
                                                                {{--  title and category - START  --}}
                                                                <div class="row">
                                                                    <div class="col-7">
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <h6><a href="{{ route('course.view', ['uuid' => $item->uuid]) }}" class='no_link-s hover_effect-s' title="{{ $item->title ?? '(not set)' }}" >{{ getTruncatedString($item->title ?? '(not set)', 12) }}</a></h6>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <span title='{{ ucwords($item->category->name ?? '(category not set)' ) }}'>{{ getTruncatedString(ucwords($item->category->name ?? '(category not set)'), 12 ) }}</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col text-right">
                                                                        <a href="javascript:void(0)" class="btn btn px-lg-1 px-xl-3 {{ $item->is_course_free ? 'course_free_btn-s' : 'course_pay_btn-s' }}" disbaled="disbaled">{{ $item->is_course_free ? 'Free' : 'Paid' }}</a>

                                                                    </div>
                                                                </div>
                                                                {{--  title and category - END  --}}

                                                                <div class="row pt-3 pb-3">
                                                                    <div class="col-6 mb-3x">
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <img src="{{ asset('assets/images/youtube_icon.svg') }}" class="" alt="">
                                                                                <span class="mx-2">{{ ucwords($item->nature) }}</span>

                                                                                <br />
                                                                                <img src="{{ asset('assets/images/enrollment_icon.svg') }}" class="py-1" alt="">
                                                                                <span class="mx-2"><strong>{{ getPeopleCount($item->students_count ?? 10) }}</strong> Students</span>

                                                                                <br />
                                                                                <img src="{{ asset('assets/images/dollar-icon.svg') }}" width="18" class="mr-xl-1 mr-lg-1 mr-md-0 mr-1" alt="">
                                                                                <span class="mx-xl-2 mx-lg-2 mx-md-0 mx-2">{{ getCoursePriceWithUnit($item) }}</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-6 text-center text-lg-center text-xl-right">
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <img src="{{ asset('assets/images/calendar_course_icon.svg') }}" class="" alt="">
                                                                                <span class="ml-2">{{ date('d M Y', strtotime($item->start_date)) }}</span>
                                                                            </div>

                                                                            <div class="col-12 pr-xl-3 py-1">
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
                                            <div class="card carousal_card-s">
                                                <div class="carousal_item_image-s">
                                                    <img class="w-100" alt="course-image" src="{{ getFileUrl($item->course_image, null, 'course') }}" />
                                                    @if (null == $item->approver_id)
                                                        @if($item->is_approved)
                                                            <div class="text-center position-absolute py-1 under_review-label-s">Under Review</div>
                                                        @else
                                                            <div class="text-center position-absolute py-1 rejected_course-s">Rejected</div>
                                                        @endif
                                                    @endif
                                                </div>
                                                <div class="">
                                                    <div class="d-flex mt-3 card_design_text-s">
                                                        <div class="container">
                                                            {{--  title and category - START  --}}
                                                            <div class="row">
                                                                <div class="col-7">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <h6><a href="{{ route('course.view', ['uuid' => $item->uuid]) }}" class='no_link-s hover_effect-s' title="{{ $item->title ?? '(not set)' }}" >{{ getTruncatedString($item->title ?? '(not set)', 20) }}</a></h6>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <span title="{{ ucwords($item->category->name ?? '(category not set)' ) }}">{{ getTruncatedString(ucwords($item->category->name ?? '(category not set)'), 20 ) }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col text-right">
                                                                    <a href="javascript:void(0)" class="btn btn px-lg-1 px-xl-3 {{ $item->is_course_free ? 'course_free_btn-s' : 'course_pay_btn-s' }}" disbaled="disbaled">{{ $item->is_course_free ? 'Free' : 'Paid' }}</a>
                                                                </div>
                                                            </div>
                                                            {{--  title and category - END  --}}

                                                            <div class="row pt-3 pb-3">
                                                                <div class="col-6 mb-3">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <img src="{{ asset('assets/images/youtube_icon.svg') }}" class="" alt="">
                                                                            <span class="mx-2">{{ ucwords($item->nature) }}</span>

                                                                            <br />
                                                                            <img src="{{ asset('assets/images/enrollment_icon.svg') }}" class="py-1" alt="">
                                                                            <span class="mx-2"><strong>{{ getPeopleCount($item->students_count ?? 10) }}</strong> Students</span>

                                                                            <br />
                                                                            <img src="{{ asset('assets/images/dollar-icon.svg') }}" width="18" class="mr-xl-1 mr-lg-1 mr-md-0 mr-1" alt="">
                                                                            <span class="mx-xl-2 mx-lg-2 mx-md-0 mx-2">{{ getCoursePriceWithUnit($item) }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6 text-center text-lg-center text-xl-right">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <img src="{{ asset('assets/images/calendar_course_icon.svg') }}" class="" alt="">
                                                                            <span class="ml-2">{{ date('d M Y', strtotime($item->start_date)) }}</span>
                                                                        </div>

                                                                        <div class="col-12 pr-xl-3 py-1">
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
        @else
            <div class="row">
                <div class="col-12 text-center mt-4 mb-4">
                    <p class="w-100 text-center">
                        <strong>
                            No Course(s) Found
                        </strong>
                    </p>
                </div>
            </div>
        @endif
    </section>

    <section class='pt-5 pb-5 px-4'>
        <div class="row">
            <div class="col-md-6 pl-xl-3 pl-lg-3 pl-3 pl-md-0 ">
                <div class="panel panel-default">
                    <div class="panel-heading mb-2">
                        <h3>Video Courses</h3>
                    </div>
                    <div class="panel-body shadow mb-5">
                        <div class="">
                              <canvas id="video_course_chart-d"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 pr-xl-3 pr-lg-3 pr-3 pr-md-0 ">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3>Online Courses</h3>
                    </div>
                    <div class="panel-body shadow">
                        <div class="">
                              <canvas id="online_course_chart-d"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{--  modals - START  --}}

    @include('course::modals.course_activity_type', [])
    @include('course::modals.online_course', [])
    @include('common::modals.waiting_popup', ['model_type' => 'Course'])
    @include('course::modals.video_content', ['page' => 'dashboard'])

    {{-- // const data_month_names = '{!! $month_names_graph_data !!}';
    // const videoCoursesData = '{!! $video_courses_graph_data !!}'; --}}
    {{-- // const onlineCoursesData = '{!! $online_courses_graph_data !!}'; --}}

    {{--  modals - END  --}}

@endsection

@section('footer-scripts')
@php
    // dd( json_decode($online_courses_graph_data) );
@endphp
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.3.2/chart.min.js" integrity="sha512-VCHVc5miKoln972iJPvkQrUYYq7XpxXzvqNfiul1H4aZDwGBGC0lq373KNleaB2LpnC2a/iNfE5zoRYmB4TRDQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>

        const month_names = JSON.parse('{!! $month_names_graph_data !!}');
        const videoCoursesData = JSON.parse('{!! $video_courses_graph_data !!}');
        const onlineCoursesData = JSON.parse('{!! $online_courses_graph_data !!}') // [3, 10, 70, 2, 50, 30, 80];

        // const month_names = [
        //     'January',
        //     'February',
        //     'March',
        //     'April',
        //     'May',
        //     'June',
        // ];
        // const videoCoursesData = [0, 10, 5, 2, 20, 30, 45];
        // const onlineCoursesData = [3, 10, 70, 2, 50, 30, 80];

        console.log(month_names, onlineCoursesData, videoCoursesData);

    </script>
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    <script src="{{ asset('assets/js/manage_courses.js') }}"></script>
@endsection

@section('header-css')
    <link rel="stylesheet" href="{{ asset('assets/css/course.css') }}" />
@endsection
