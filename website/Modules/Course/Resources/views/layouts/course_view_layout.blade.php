{{-- @yield('page-title', 'LMS') --}}
{{-- @stack('header-scripts')
@yield('header-css') --}}
{{-- @yield('content') --}}
{{-- @stack('footer-head-scripts')
@yield('footer-scripts') --}}

@extends('teacher::layouts.teacher')

@section('content')
    <div class="container-fluid px-5">
        {{-- course basics - START --}}
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-2 pt-4">
                <div class="row">
                    <!--back button-->
                    <div class="angle_left-s col-xl-1 col-lg-2 col-md-12 col-sm-12 col-12 text-left pr-0 ">
                        <a href='{{ route('teacher.dashboard') }}'>
                            <img src="{{ asset('assets/images/angle_left_icon.svg') }}" class="shadow p-3 mb-5 bg-white rounded" width="60" height="60" alt="back" />
                        </a>
                    </div>
                    <!--back button end-->

                    <!--main head-->
                    <div class=" col-xl-11 col-lg-10 col-md-12 col-sm-12 col-12 ">
                        <div class="d-flex justify-content-between align-self-center ">
                            <h2 class='course_detail_title_heading-d' data-uuid="{{ $course->uuid ?? '' }}">{{ $course->title ?? '' }}</h2>
                            <span class="text-align-left image_query-s">
                                <img src="{{ asset('assets/images/chat_icon.svg') }}" class="rounded-circle px-1 py-1" width="55" alt="messages" />
                                <img src="{{ asset('assets/images/manual.svg') }}" class="rounded-circle px-1 py-1 " width="55" alt="manual" />
                                <img src="{{ asset('assets/images/setting_icon.svg') }}" class="rounded-circle px-1 py-1" width="55" alt="setting">
                            </span>
                        </div>
                        <h5 class="text-success ">
                            {{ ucwords($course->category->name ?? '') }} -
                            {{ ucwords($course->status ?? '') }}
                        </h5>
                        <div class="col-xl-10 col-lg-11 col-md-12 col-sm-12 col-12 pl-0">
                            <p>{{ $course->description ?? '' }}</p>
                        </div>
                    </div>
                    <!--main head end-->
                </div>
            </div>
        </div>
        {{-- course basics - END --}}

        <div class="row row-cols-sm-2 row-cols-md-2 row-cols-lg-2 row-cols-xl-5" id="course_details_stats_container-d">
            <!--card outline -->
            <div class="col px-1 mt-4">
                <div class="body shadow ">
                    <div class="card-body text-center single_course_stats-s outline_colum-s course_stats-d course_outline_stats-d active" data-target_elm="outline_main_container-d">
                        <div class="d-flex">
                            <h5 class="text-centre mt-2">
                                <img src="{{ asset('assets/images/outline_icon.svg') }}" class="py-1" alt="outline"> &nbsp; Outline
                            </h5>
                        </div>
                        <div class="card-text">
                            <div class="col text-center">
                                <strong class="mt-3 h1">
                                    <span class='course_outline_count-d'>{{ get_padded_number($course->total_outlines_count ?? 0) }}</span>
                                </strong>
                            </div>
                        </div>
                        <a href="javascript:void(0)" class="stretched-link"></a>
                    </div>
                </div>
            </div>
            <!--card outline end-->


            @if($course->nature == 'video')
                <!--Viedo Content - START -->
                <div class="col px-1 mt-4">
                    <div class="body shadow rounded">
                        <div class="card-body text-center single_course_stats-s content_colum-s course_stats-d course_video_stats-d" data-target_elm="video_content_main_container-d">
                            <div class="d-flex ">
                                <h5 class="text-centre mt-2 ">
                                    <img src="{{ asset('assets/images/content_icon.svg') }}" class="py-1" alt="video-icon" /> &nbsp; Video
                                </h5>
                            </div>
                            <div class="card-text">
                                <div class="col text-center">
                                    <strong class="mt-3 h1"><span class="course_content_count-d"> {{ get_padded_number($course->total_videos_count ?? 0) }}</span></strong>
                                </div>
                            </div>
                            <a href="javascript:void(0)" class="stretched-link"></a>
                        </div>
                    </div>
                </div>
                <!--Viedo Content - END -->
            @else
                <!--slots - START -->
                <div class="col px-1 mt-4">
                    <div class="body shadow rounded">
                        <div class="card-body text-center single_course_stats-s content_colum-s course_stats-d course_slot_stats-d" data-target_elm="course_slot_main_container-d">
                            <div class="d-flex ">
                                <h5 class="text-centre mt-2 ">
                                    <img src="{{ asset('assets/images/content_icon.svg') }}" class="py-1" alt="slot-icon" /> &nbsp; Slots
                                </h5>
                            </div>
                            <div class="card-text">
                                <div class="col text-center">
                                    <strong class="mt-3 h1"><span class="course_slot_count-d"> {{ get_padded_number($course->total_slots_count ?? 0) }}</span></strong>
                                </div>
                            </div>
                            <a href="javascript:void(0)" class="stretched-link"></a>
                        </div>
                    </div>
                </div>
                <!--slots - END -->
            @endif

            <!--card handouts -->
            <div class="col px-1 mt-4">
                <div class="body shadow">
                    <div class="card-body text-center single_course_stats-s handouts_colum-s course_stats-d course_handouts_stats-d">
                        <div class="d-flex">
                            <h5 class=" mt-2 ">
                                <img src="{{ asset('assets/images/handouts_icon.svg') }}" class="py-1" alt="handout-icon" /> &nbsp; Handouts
                            </h5>
                        </div>

                        <div class="card-text">
                            <div class="col text-center">
                                <strong class="mt-3 h1"><span class="course_handouts_count-d" />{{ get_padded_number($course->handout_count ?? 0) }}</span></strong>
                            </div>
                        </div>
                        <a href="javascript:void(0)" class="stretched-link"></a>
                    </div>
                </div>
            </div>
            <!--card handouts end-->

            <!--card Students-->
            <div class="col px-1 mt-4">
                <div class="body shadow">
                    <div class="card-body text-center single_course_stats-s students_colum-s course_stats-d course_students_stats-d" data-target_elm="student_main_container-d">
                        <div class="d-flex">
                            <h5 class="mt-2">
                                <img src="{{ asset('assets/images/enrolled_icon.svg') }}" class="py-1" alt="student-icon" /> &nbsp; Students
                            </h5>
                        </div>
                        <div class="card-text">
                            <div class="col text-center">
                                <strong class=" mt-3 h1">
                                    <span class="course_enrolled_count-d">{{ get_padded_number($course->student_count ?? 0) }}</span>
                                </strong>
                            </div>
                        </div>
                        <a href="javascript:void(0)" class="stretched-link"></a>
                    </div>
                </div>
            </div>
            <!-- card Students end -->

            <!-- card reviews -->
            <div class="col px-1 mt-4 ">
                <div class="body shadow rounded">
                    <div class="card-body text-center single_course_stats-s reviews_colum-s course_stats-d course_reviews_stats-d" data-target_elm="reviews_main_container-d">
                        <div class="d-flex">
                            <h5 class="mt-2">
                                <img src="{{ asset('assets/images/reviews_icon.svg') }}" class="py-1" alt="review-icon"> &nbsp; Reviews
                            </h5>
                        </div>

                        <div class="card-text">
                            <div class="col text-center">
                                <strong class="mt-3 h1"><span class="course_reviews_count-d">{{ get_padded_number($course->total_rater_count ?? 0) }}</span></strong>
                            </div>
                        </div>
                        <a href="javascript:void(0)" class="stretched-link"></a>
                    </div>
                </div>
            </div>
            <!--card reviews end-->
        </div>

        @yield('course_content')
    </div>

@endsection
