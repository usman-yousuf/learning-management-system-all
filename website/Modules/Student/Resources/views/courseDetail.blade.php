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
                            <h2 class='course_detail_title_heading-d'>{{ getTruncatedString($course_detail->title , 50) }}</h2>
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

    </div>
    <div class="main_work_container-d">
        @include("student::partials.quiz")
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
                            <form action="{{ route('quiz.addQuestion', $course_detail->uuid) }}" id="add_Question-d" method="post">
                                @csrf
                                <div class="modal-body">
                                    <div class="row pt-5">
                                        <div class="col-12 ">
                                            <h4 ><strong>{{ $course_detail->title }}</strong></h4>
                                            <textarea class="form-control bg-light rounded-4 pt-2 mt-5" name="body" placeholder="Type your question......" id="" rows="6"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <!--view modal body end-->
                                <!-- Modal footer -->
                                <div class="modal-footer border-0 mb-5 mt-xl-5 mt-lg-5 mt-sm-5 mt-3 justify-content-center">
                                    <button type="submit" class="bg_success-s br_24-s py-2 px-5 text-white  border border-white ">
                                        Send
                                    </button>
                                </div>
                            </form>
                            <!-- Modal footer End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!--add comment modal end-->

@endsection
@section('footer-scripts')
        <script>
            // student_course_detail_page
            let Start_Quiz_Page = "{{ route('student.courseDetail') }}";
            let Student_Course_Detail_Page = "{{ route('student.courseDetail') }}";
        </script>
    <script src="{{ asset('assets/js/student.js') }}"></script>
    <script src="{{ asset('assets/js/manage_student_courses.js') }}"></script>
@endsection

{{--

@push('header-scripts')
    <script>

    </script>
@endpush --}}
