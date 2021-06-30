@extends('teacher::layouts.teacher')

@section('content')
    <div class="container-fluid px-5 font_family_sans-serif-s">
        <!-- course basics - START -->
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-2 pt-4">
                <div class="row">
                    <!--back button-->
                    <div class="angle_left-s col-xl-1 col-lg-2 col-md-12 col-sm-12 col-12 text-left pr-0 ">
                        <a href="">
                            <img src="{{ asset('assets/images/angle_left.svg') }}" class="shadow p-3 mb-5 bg-white rounded" width="60" height="60" alt="back" />
                        </a>
                    </div>
                    <!--back button end-->
                    {{-- {{ dd($data) }} --}}
                    <!--main head-->
                    <div class=" col-xl-11 col-lg-10 col-md-12 col-sm-12 col-12 ">
                        <div class="d-flex justify-content-between align-self-center ">
                            <h2 class='course_detail_title_heading-d'>{{ $course_detail->title }}</h2>
                            <span class="text-align-left image_query-s">
                                <button type="button" 
                                class="btn bg_success-s br_21px-s text-white px-4 "  
                                data-toggle="modal" 
                                data-target="#ask_question-d-{{  $course_detail->uuid }}">
                                    Ask Question
                                </button>
                            </span>
                        </div>
                        <h5 class="text-success ">
                            Active
                        </h5>
                        <div class="col-xl-10 col-lg-11 col-md-12 col-sm-12 col-12 pl-0">
                            <p>Lorem Ipsum is simply dummy text of the printing and type setting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s. when an unknown printer took as galley of type and scambled it to make a type specimen book.</p>
                        </div>
                    </div>
                    <!--main head end-->
                </div>
            </div>
        </div>
        <!--course Basic end-->
    
        <div class="row row-cols-sm-2 row-cols-md-2 row-cols-lg-2 row-cols-xl-5" id="student_course_details_stats_container-d">
            <!--card outline -->
            <div class="col px-1 mt-4">
                <div class="body shadow ">
                    <div class="card-body text-center single_course_stats-s outline_colum-s course_stats-d course_outline_stats-d active" data-target_elm="outline_main_container-d">
                        <div class="d-flex">
                            <h5 class="text-centre mt-2">
                                <img src="{{ asset('assets/images/outline.svg') }}" class="py-1" alt="outline"> &nbsp; Outline
                            </h5>
                        </div>
                        <div class="card-text">
                            <div class="col text-center">
                                <strong class="mt-3 h1">
                                    <span class='course_outline_count-d'>30</span>
                                </strong>
                            </div>
                        </div>
                        <a href="javascript:void(0)" class="stretched-link"></a>
                    </div>
                </div>
            </div>
            <!--card outline end-->
    
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
                                <strong class="mt-3 h1"><span class="course_content_count-d">20</span></strong>
                            </div>
                        </div>
                        <a href="javascript:void(0)" class="stretched-link"></a>
                    </div>
                </div>
            </div>
            <!--Viedo Content - END -->

            <!--card handouts -->
            <div class="col px-1 mt-4">
                <div class="body shadow">
                    <div class="card-body text-center single_course_stats-s handouts_colum-s course_stats-d course_handouts_stats-d" data-target_elm="handout_main_container-d">
                        <div class="d-flex">
                            <h5 class=" mt-2 ">
                                <img src="{{ asset('assets/images/handouts_icon.svg') }}" class="py-1" alt="handout-icon" /> &nbsp; Handouts
                            </h5>
                        </div>
    
                        <div class="card-text">
                            <div class="col text-center">
                                <strong class="mt-3 h1">
                                    <span class="course_handouts_count-d" >
                                        30
                                    </span>
                                </strong>
                            </div>
                        </div>
                        <a href="javascript:void(0)" class="stretched-link"></a>
                    </div>
                </div>
            </div>
            <!--card handouts end-->
    
            <!--card Quiz-->
            <div class="col px-1 mt-4">
                <div class="body shadow">
                    <div class="card-body text-center single_course_stats-s quiz_colum-s course_stats-d course_students_stats-d" data-target_elm="student_main_container-d">
                        <div class="d-flex">
                            <h5 class="mt-2">
                                <img src="{{ asset('assets/images/read.svg') }}" class="py-1" alt="student-icon" /> &nbsp; Quiz
                            </h5>
                        </div>
                        <div class="card-text">
                            <div class="col text-center">
                                <strong class=" mt-3 h1">
    
                                    <span class="course_enrolled_count-d">20</span>
                                </strong>
                            </div>
                        </div>
                        <a href="javascript:void(0)" class="stretched-link"></a>
                    </div>
                </div>
            </div>
            <!-- card quiz end -->
    
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
                                <strong class="mt-3 h1"><span class="course_reviews_count-d">30</span></strong>
                            </div>
                        </div>
                        <a href="javascript:void(0)" class="stretched-link"></a>
                    </div>
                </div>
            </div>
            <!--card reviews end-->
        </div>
        @yield('student_course_content')
    </div>

@endsection