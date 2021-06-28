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
    
        <div class="row row-cols-sm-2 row-cols-md-2 row-cols-lg-2 row-cols-xl-5" id="course_details_stats_container-d">
            <!--card outline -->
            <div class="col px-1 mt-4">
                <div class="body shadow ">
                    <div class="card-body text-center single_course_stats-s outline_colum-s course_stats-d course_outline_stats-d " data-target_elm="outline_main_container-d">
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
                    <div class="card-body text-center single_course_stats-s quiz_colum-s course_stats-d course_students_stats-d active" data-target_elm="student_main_container-d">
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
        <!--quiz list-->
        <div class="row pt-5 pb-4 quiz_main_container-d">
            @forelse ($data->quizzes as $item)
                <div class="col-12 my-2 bg_white-s br_10px-s single_quiz_container-d shadow">
                    <div class="row py-3">
                        <div class="col-xl-7 col-lg-6 col-md-12 col-12">
                            <a class='no_link-s link-d'href="javascript:void(0)">
                                <h4 class=" title-d">
                                    {{ $item->title }}
                                </h4>
                                <h5 class="fg-success-s">
                                    {{ $item->course->title }}
                                </h5>
                            </a>
                        </div>
                        <div class="col-xl-5 col-lg-6 col-md-12 col-12  ">
                            <span class="text_muted-s">
                                Quiz Type
                            </span> 
                            <span class="ml-3 font-weight-bold  ">
                                {{ $item->type }}  
                            </span>
                        </div>
                    </div>
                        <div class="row">
                            <div class="col-11 fg_dark-s">
                                <p>{{ $item->description }}</p>
                            </div>
                        </div>
                    <div class="row py-3">
                        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 fg_dark-s mb-2 ">
                            <a href="" class="btn bg_success-s text-white w-50 br_21px-s" data-toggle="modal" data-target="#conformation_modal-d-{{ $item->uuid }}">
                                Start
                            </a>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-6 fg_dark-s mt-2 text-xl-right">
                            <span>
                                <img src="{{ asset('assets/images/student_quiz_clock.svg') }}" alt="clock">
                            </span>
                            <span class="pl-2 ">
                                <strong>{{ $item->duration_mins }}</strong> Minutes
                            </span>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-6 fg_dark-s mt-2 ">                       
                            <span >
                                <img  src="{{ asset('assets/images/student_quiz_calender.svg') }}" alt="clock">
                            </span>
                            <span class="pl-2">
                                {{ $item->modal_due_date }}
                            </span>
                        </div>
                    </div>
                </div>

                <!--quiz conformation modal-->
                    <div class="modal" id="conformation_modal-d-{{ $item->uuid }}">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content d-flex">
                                <!-- Modal Header -->
                                <div class="modal-header border-0 align-self-center  pt-5 mt-5 ">
                                    <a href="javascript:void(0)">
                                        <img class="img_h_300px w-100" src="{{ asset('assets/images/student_login_img.svg') }}" alt="congratulation-image" />
                                    </a>
                                </div>
                                <!-- Modal Header End -->
                    
                                <!-- Modal body -->
                                <div class="modal-body  text-center">
                                    <span class="fs_30px-s  ">
                                        Are you sure to start quiz?
                                    </span>
                                </div>
                                <!-- Modal body End -->
                                {{-- @php
                                    // $duration = date('M d, Y H:i:s', strtotime("+{$item->duration_mins} minutes"));
                                    $duration = date('M d, Y H:i:s', strtotime("+{$item->duration_mins} minutes"));
                                    echo $duration;
                                @endphp --}}

                                <!-- Modal footer -->
                                <div class="modal-footer align-self-center border-0 pb-5">
                                    <a href="{{ route('student.getQuiz', $item->uuid) }}" class="btn bg_success-s br_21px-s text-white px-5 mr-xl-5 mr-lg-5 mr-md-5 mr-2 " id="start_test_quiz-d">Yes</a>
                                    <a href="javascript:void(0)" class="btn br_21px-s courses_delete_btn-s px-5 ml-2 ml-xl-5 ml-lg-5 ml-md-5" data-dismiss="modal">No</a>
                                    {{-- <input type="hidden" name="" id="duration" value="{{ $duration }}">  --}}
                                </div>
                                <!-- Modal footer End -->
                            </div>
                        </div>
                    </div>
                <!--quiz conformation modal end-->
            @empty
            
            @endforelse
        </div>
        <!--quiz list end-->
    </div>

    <!--add comment modal-->
        <div class="modal fade" id="ask_question-d-{{ $course_detail->uuid }}" tabindex="-1" role="comment" aria-labelledby="view-head" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header d-block">    
                        <div class="container">
                            <!--modal header-->
                            <div class="row">
                                <div class="col-12 text-right">
                                    <a class="close pt-3 pr-0" data-dismiss="modal" aria-label="Close">
                                        <img class="float-right" src="{{ asset('assets/images/cancel_circle.svg') }}" alt="">
                                    </a>
                                </div>
                            </div>
                            <!--modal header end-->

                            <!--VIEW MODAL BODY-->
                            <div class="modal-body">
                                <div class="row pt-5">
                                    <div class="col-12 ">
                                        <h4 ><strong>Website Designing</strong></h4>
                                        <textarea class="form-control bg-light rounded-4 pt-2 mt-5" placeholder="Type your question......" id="" rows="6"></textarea>
                                    </div>
                                </div>
                            </div>
                            <!--view modal body end--> 
                            <!-- Modal footer -->
                            <div class="modal-footer border-0 mb-5 mt-xl-5 mt-lg-5 mt-sm-5 mt-3 justify-content-center">
                                <button type="button" class="bg_success-s br_24-s py-2 px-5 text-white  border border-white ">
                                    Send
                                </button>
                            </div>
                            <!-- Modal footer End -->      
                        </div>
                    </div>
                </div>
            </div>          
        </div>
    <!--add comment modal end-->

    {{-- <p id="demo"></p> --}}
@endsection
@section('footer-scripts')
    <script src="{{ asset('assets/js/student.js') }}"></script>
@endsection

{{-- 

@push('header-scripts')
    <script>
    
    </script>
@endpush --}}