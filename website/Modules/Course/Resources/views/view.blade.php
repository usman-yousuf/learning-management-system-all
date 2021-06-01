@extends('course::layouts.course_view_layout')

@section('course_content')
        {{--  list outline content - START  --}}
        <div class="outline_main_container-d course_details_container-d" id='outline_main_container-d'>
            <div class="row pb-4">
                <!--Total outline-->
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 mt-4 align-self-center">
                    <h3 class="total_videos_text-s">Total Outline: <span class="total_videos_count-d">{{ get_padded_number($course->outline_count ?? 0) }}</span></h3>
                </div>
                <!--Total outline end-->

                <!--Add outline-->
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 mt-4 ">
                    <div class="float-md-right">
                        <a href="javascript:void(0)" class="btn btn py-3 px-4 add_course_btn-s" data-toggle="modal" data-target="#add_outline">
                            <img src="{{ asset('assets/images/add_btn_icon.svg') }}" width="20" class="mx-2" alt="+">
                            <span class="mx-2 text-white">Add Outline</span>
                        </a>
                    </div>
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
                <div class="videos_container-d main_page-d">
                    <div class="row pt-4">
                        <!--Total Video-->
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 mt-1 align-self-center">
                            <h3 class="total_videos_text-s">Total Videos:<span class="total_videos_count-d">{{ get_padded_number($course->videos_count ?? 0) }}</span></h3>
                        </div>
                        <!--Total videos end-->
                        <!--Add Video-->
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 pt-sm-1 ">
                            <div class="float-md-right">
                                <a  href="javascript:void(0)" class="btn btn pt-3 pb-3 pl-4 pr-4 add_course_btn-s" data-toggle="modal" data-target="#add_video_modal">
                                    <img src="{{ asset('assets/images/add_btn_icon.svg') }}" width="20" class="mx-2" alt="+">
                                    <span class="ml-2 mr-2 text-white">Add Video</span>
                                </a>
                            </div>
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
            {{--  list Video Content - START  --}}
            <div class="course_slot_main_container-d course_details_container-d" id='course_slot_main_container-d' style="display:none;">
                <div class="videos_container-d main_page-d">
                    <div class="row pt-4">
                        <!--Total Video-->
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 mt-1 align-self-center">
                            <h3 class="total_videos_text-s">Total Slots:<span class="total_videos_count-d">{{ get_padded_number($course->total_slots_count ?? 0) }}</span></h3>
                        </div>
                        <!--Total videos end-->
                        <!--Add Video-->
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 pt-sm-1 ">
                            <div class="float-md-right">
                                <a  href="javascript:void(0)" class="btn btn pt-3 pb-3 pl-4 pr-4 add_course_btn-s" data-toggle="modal" data-target="#add_slot_modal">
                                    <img src="{{ asset('assets/images/add_btn_icon.svg') }}" width="20" class="mx-2" alt="+">
                                    <span class="ml-2 mr-2 text-white">Add Slot</span>
                                </a>
                            </div>
                        </div>
                        <!--add video end-->
                    </div>

                    <div class="slots_main_container-d main_page-d">
                        @include('course::partials.course_slot', ['page' => 'details', 'slots' => $course->slots])
                    </div>
                </div>
            </div>
            {{--  list Video Content - END  --}}
        @endif

        {{--  list Students Content - START  --}}
        <div class="student_main_container-d course_details_container-d" id='student_main_container-d' style="display:none;">
            <div class="">
                <div class="students_container-d main_page-d">
                    @include('course::partials.course_student', ['page' => 'details', 'students' => $course->enrolled_students])
                </div>
            </div>
        </div>
        {{--  list Students Content - END  --}}


        {{--  list Reviews - START  --}}
        <div class="reviews_main_container-d course_reviews_container-d" id='reviews_main_container-d' style="display:none;">
            <div class="">
                <div class="reviews_container-d main_page-d">
                    @include('course::partials.course_reviews', ['page' => 'details', 'reviews' => $course->reviews])
                </div>
            </div>
        </div>
        {{--  list Reviews - END  --}}


        @include('course::modals.outline', ['outlines' => $course->outlines])
        @include('course::modals.video_content', ['contents' => $course->contents])
        @include('course::modals.course_slot', ['slots' => $course->slots])
@endsection


@section('footer-scripts')
    <script src="{{ asset('assets/js/manage_courses.js') }}"></script>
@endsection

@section('header-css')
    <link rel="stylesheet" href="{{ asset('assets/css/course.css') }}" />
@endsection


@push('header-scripts')
    <script>
        let modal_delete_outline_url = "{{ route('course.delete-outline') }}";
        let modal_delete_slot_url = "{{ route('course.delete-slot') }}";
        let modal_delete_video_content_url = "{{ route('course.delete-video-content') }}";
    </script>
@endpush
