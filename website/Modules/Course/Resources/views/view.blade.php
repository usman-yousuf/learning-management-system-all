@extends('course::layouts.course_view_layout')


@section('page-title')
    View Course
@endsection

@section('course_content')
    @php
        // dd($course->queries);
    @endphp
        {{--  list outline content - START  --}}
        <div class="outline_main_container-d course_details_container-d" id='outline_main_container-d'>
            <div class="row pb-4">
                <!--Total outline-->
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 mt-4 align-self-center">
                    <h3 class="total_videos_text-s">Total Outline: <span class="total_videos_count-d">{{ get_padded_number($course->total_outlines_count ?? 0) }}</span></h3>
                </div>
                <!--Total outline end-->

                <!--Add outline-->
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 mt-4 ">
                    @if((\Auth::user()->profile_type != 'student') && (\Auth::user()->profile_type != 'parent') )
                        <div class="float-md-right">
                            <a href="javascript:void(0)" class="btn btn py-3 px-4 add_course_btn-s open_add_outline_modal-d">
                                <img src="{{ asset('assets/images/add_btn_icon.svg') }}" width="20" class="mx-2" alt="+">
                                <span class="mx-2 text-white">Add Outline</span>
                            </a>
                        </div>
                    @endif
                </div>
                <!--add outline end-->
            </div>

            <div class="outlines_container-d main_page-d">
                @include('course::partials.course_outline', ['page' => 'details', 'outlines' => $course->outlines])
            </div>
        </div>
        {{--  list outline content - END  --}}

        @if($course->nature == 'video')
            {{--  list Video Content - START  --}}
            <div class="video_content_main_container-d course_details_container-d" id='video_content_main_container-d' style="display:none;">
                <div class="videos_main_container-d main_page-d">
                    <div class="row pt-4">
                        <!--Total Video-->
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 mt-1 align-self-center">
                            <h3 class="total_videos_text-s">Total Videos:<span class="total_videos_count-d">{{ get_padded_number($course->videos_count ?? 0) }}</span></h3>
                        </div>
                        <!--Total videos end-->
                        <!--Add Video-->
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 pt-sm-1 ">
                            @if((\Auth::user()->profile_type != 'student') && (\Auth::user()->profile_type != 'parent') )
                                <div class="float-md-right">
                                    <a  href="javascript:void(0)" class="btn btn pt-3 pb-3 pl-4 pr-4 add_course_btn-s" data-toggle="modal" data-target="#add_video_modal">
                                        <img src="{{ asset('assets/images/add_btn_icon.svg') }}" width="20" class="mx-2" alt="+">
                                        <span class="ml-2 mr-2 text-white">Add Video</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                        <!--add video end-->
                    </div>

                    <div class="contents_container-d main_page-d">
                        @include('course::partials.video_course_content', ['page' => 'details', 'contents' => $course->contents])
                    </div>
                </div>
            </div>
            {{--  list Video Content - END  --}}
        @else
            {{--  list Slots - START  --}}
            <div class="course_slot_main_container-d course_details_container-d" id='course_slot_main_container-d' style="display:none;">
                <div class="slot_container-d main_page-d">
                    <div class="row pt-4">
                        <!--Total Video-->
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 mt-1 align-self-center">
                            <h3 class="total_videos_text-s">Total Slots:<span class="total_videos_count-d">{{ get_padded_number($course->total_slots_count ?? 0) }}</span></h3>
                        </div>
                        <!--Total videos end-->
                        <!--Add Video-->
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 pt-sm-1 ">
                            @if((\Auth::user()->profile_type != 'student') && (\Auth::user()->profile_type != 'parent') )
                                <div class="float-md-right">
                                    <a  href="javascript:void(0)" class="btn btn pt-3 pb-3 pl-4 pr-4 add_course_btn-s open_add_slot_modal-d">
                                        <img src="{{ asset('assets/images/add_btn_icon.svg') }}" width="20" class="mx-2" alt="+">
                                        <span class="ml-2 mr-2 text-white">Add Slot</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                        <!--add video end-->
                    </div>

                    <div class="slots_main_container-d main_page-d">
                        @include('course::partials.course_slot', ['page' => 'details', 'slots' => $course->slots])
                    </div>
                </div>
            </div>
            {{--  list Slots - END  --}}
        @endif

        {{--  Handout container - START  --}}
        <div class="course_details_container-d" id='handout_main_container-d' style="display:none;">
            <div class="handout_main_container-d main_page-d">
                <div class="row pt-4">
                    <!--Total Video-->
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 mt-1 align-self-center">
                        <h3 class="total_videos_text-s">Total Handouts: <span class="total_handout_count-d">{{ get_padded_number($course->total_handouts_count ?? 0) }}</span></h3>
                    </div>
                    <!--Total videos end-->
                    <!--Add Video-->
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 pt-sm-1 ">
                        @if((\Auth::user()->profile_type != 'student') && (\Auth::user()->profile_type != 'parent') )
                            <div class="float-md-right">
                                <a  href="javascript:void(0)" class="btn btn pt-3 pb-3 pl-4 pr-4 add_course_btn-s" data-toggle="modal" data-target="#add_handout_modal">
                                    <img src="{{ asset('assets/images/add_btn_icon.svg') }}" width="20" class="mx-2" alt="+">
                                    <span class="ml-2 mr-2 text-white">Add Handout</span>
                                </a>
                            </div>
                        @endif
                    </div>
                    <!--add video end-->
                </div>

                <div class="contents_container-d main_page-d mb-4">
                    @include('course::partials.course_handout_content', ['page' => 'details', 'handouts' => $course->handouts])
                </div>
            </div>
        </div>
        {{--  Handout container - END  --}}

        @if((\Auth::user()->profile_type == 'student') || (\Auth::user()->profile_type == 'parent') )
            {{--  list quizzez - START  --}}
            <div class="student_main_container-d course_details_container-d" id='course_quiz_main_container-d' style="display:none;">
                <div class="row pt-3 pb-5">
                    <!--quiz list-->
                    @include('quiz::_partials._quiz_listing', ['quizzez' => $course->quizzez])
                    @include('student::modals.quiz_confirmation_modal')
                    <!--quiz list end-->
                </div>
            </div>
            {{--  list quizzez - END  --}}
        @else
            {{--  list Students Content - START  --}}
            <div class="student_main_container-d course_details_container-d" id='student_main_container-d' style="display:none;">
                <div class="">
                    <div class="students_container-d main_page-d">
                        @include('course::partials.course_student', ['page' => 'details', 'students' => $course->enrolled_students])
                    </div>
                </div>
            </div>
            {{--  list Students Content - END  --}}
        @endif

        {{-- {{ dd($course) }} --}}


        {{--  list Reviews - START  --}}
        <div class="reviews_main_container-d course_details_container-d" id='reviews_main_container-d' style="display:none;">
            <div class="">
                <div class="row">
                    <div class="col-12 w-100 pt-4">
                        @if(\Auth::user()->profile_type != 'teacher')
                            <button class="btn btn-primary float-right" id="add_review-d">Add Review</button>
                        @endif
                    </div>
                </div>
                <div class="reviews_container-d main_page-d">
                    @include('course::partials.course_reviews', ['page' => 'details', 'reviews' => $course->reviews])
                    @include('student::modals.add_comment_modal', ['page' => 'details','get_course_id' => $course->uuid, 'get_course_name' => $course->title])
                </div>
            </div>
        </div>
        {{--  list Reviews - END  --}}

        <div class="course_setting_main_container-d course_details_container-d" id='course_setting_main_container-d' style="display:none;">
            <div class="">
                <div class="reviews_container-d main_page-d">
                    @include('course::partials.course_settings', ['page' => 'details', 'reviews' => $course->reviews])
                </div>
            </div>
        </div>


        @include('course::modals.outline', ['outlines' => $course->outlines])
        @include('course::modals.video_content', ['contents' => $course->contents])
        @include('course::modals.course_slot', ['slots' => $course->slots])
        @include('course::modals.course_handout_content', ['handouts' => $course->handouts])

        @include('course::modals.course_queries', ['queries' => $course->queries])

        @if((\Auth::user()->profile_type == 'student') || (\Auth::user()->profile_type == 'parent') )
            @include('student::modals.add_question_modal', ['course_detail' => $course])
        @endif

@endsection


@section('footer-scripts')
    <script src="{{ asset('assets/js/manage_courses.js') }}"></script>
    @if((\Auth::user()->profile_type == 'student') || (\Auth::user()->profile_type == 'parent') )
        <script src="{{ asset('assets/js/student.js') }}"></script>
    @endif
@endsection

@section('header-css')
    <link rel="stylesheet" href="{{ asset('assets/css/course.css') }}" />
@endsection


@push('header-scripts')
    <script>
        let modal_delete_outline_url = "{{ route('course.delete-outline') }}";
        let modal_delete_slot_url = "{{ route('course.delete-slot') }}";
        let modal_delete_video_content_url = "{{ route('course.delete-video-content') }}";
        let modal_delete_handout_url = "{{ route('course.delete-handout') }}";

        let modal_delete_query_response_url = "{{ route('query.delete-response') }}";
        let delete_course_review_url = "{{ route('student.deleteMyReview') }}";
    </script>
@endpush
