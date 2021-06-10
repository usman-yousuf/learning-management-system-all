@extends('teacher::layouts.teacher')

@section('page-title')
    Quizzez
@endsection

@section('content')
<div class="container-fluid px-5">
    <div class="row py-4">
        <div class="col-xl-6 col-lg-6 col-md-4 col-sm-12 col-12 align-self-center ">
            <h3 class="top_courses_text-s mx-3 ">Quiz</h3>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-8 col-sm-12 col-12 ">
            <div class="float-md-right ">
                <a href="javascript:void(0)" class="btn btn py-3 px-5 add_course_btn-s " id="add_quiz_type_modal-d">
                    <img src="{{ asset('assets/images/add_button.svg') }} " width="20 " id="add_outline_btn-d " class="mx-2 " alt=" ">
                    <span class="mx-1 text-white" id="add-new-quiz-d">Add Test</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Quiz list - START -->
    <div class="row pb-4 quiz_main_container-d">
        <!-- List Item 1 - START -->
        {{-- {{ dd($data) }} --}}
        @forelse ($data->quizzes as $key => $item)
            <div class="col-12 my-2 bg_white-s br_10px-s single_quiz_container-d {{ 'uuid_'.$item->uuid ?? ''}}">
                <div class="row py-3">
                    <div class="col-xl-6 col-lg-6 col-md-12 col-12">
                        <a href="{{ route('quiz.testQuestion', $item->uuid ?? '______') }}">
                            <h5 class="fg-success-s">
                                {{ $item->title ?? '' }}
                            </h5>
                        </a>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-12 col-12 fg-success-s text--xl-right">
                        <h6>
                            @php

                                if($item->type == 'boolean')
                                {
                                    $type = 'true false';
                                }
                                else if($item->type == 'mcqs'){
                                    $type = 'MCQs';
                                }
                                else{
                                    $type = 'test';
                                }
                            @endphp

                            Type: <strong>{{ strtoupper($type ?? '') }}</strong>
                            <span>
                                <img class="pl-3" src="{{asset('assets/images/clock_icon.svg')}}" alt="">
                            </span>
                            <span class=''>
                                <strong class='duration-d'>
                                    {{ $item->duration_mins }}
                                </strong>
                                Minutes
                            </span>
                        </h6>
                    </div>
                </div>
                    <div class="row">
                        <div class="col-11 fg_dark-s">
                            <p>{{ $item->description ?? '' }}</p>
                        </div>
                    </div>
                <div class="row py-3">
                    <div class="col-xl-3 col-lg-6 col-md-5 col-12 fg_dark-s">
                        <span>
                            Total Students: <strong class='students_count-d'>{{ $item->students_count ?? '0' }}</strong>
                        </span>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-7 col-12 fg_dark-s">
                        <span>
                            Attending Test Student:  <strong class='attempts_count-d'>{{ $item->attempts_count ?? '0' }}</strong>
                        </span>
                    </div>
                </div>
            </div>
        @empty
        @endforelse
        <!-- List Item 1 - END -->
    </div>
    <!-- Quiz list - END -->


    <!-- The Modal -->
    <div class="modal quiz_type_modal-d" id="quiz_type_modal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content d-flex">
                <!-- Modal Header Start -->
                <div class="container-fluid">
                    <div class="row">
                        <div class=" col-12 modal-header border-0">
                            <h3 class="fg_green-s w-100 text-center font-weight-normal">Select Quiz Type</h3>
                            <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                        </div>
                    </div>
                </div>
                <!-- Modal Header End -->


                <!-- Modal Body Start -->
                <div class="modal-body">
                    <div class="container-fluid">
                        <form action="{{ route('quiz.update') }}" id="add_quiz_type-d" class="add_quiz_by_course-d">
                            <div class="row quiz_type_radio-s">
                                <div class="col text-center">
                                    <div class="form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input quiz_type-d" name="quiz_type" checked="checked" value="test"> Test
                                        </label>
                                    </div>
                                </div>

                                <div class="col text-center">
                                    <div class="form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input quiz_type-d" name="quiz_type" value="mcqs"> Multiple Choice
                                        </label>
                                    </div>
                                </div>

                                <div class="col text-center">
                                    <div class="form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input bg_dark-s quiz_type-d" name="quiz_type" value="boolean"> True|False
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="container w-75 ml-auto mt-4">
                                <div class="row">
                                    <div class="col-12 col-sm-6">
                                        <label class="font-weight-normal course_textarea-s ml-3" for="quiz_title">Quiz Title</label>
                                        <input type="text" class="form-control form-control-lg login_input-s quiz_title-d" name="quiz_title" id="quiz_title-d" placeholder="Web Desiging">
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <label class="font-weight-normal course_textarea-s ml-3" for="quiz_duration">Quiz Duration</label>
                                        <input type="text" class="form-control form-control-lg login_input-s quiz_duration-d" name="quiz_duration" id="quiz_duration-d" placeholder="30 minutes">
                                    </div>
                                </div>


                                <div class="row pt-5">
                                    <div class="col-12 col-sm-6">
                                        <label class="font-weight-normal course_textarea-s ml-3" for="ddl_course_uuid-d">Choose a Course:</label>
                                        <select name="course_uuid" id="ddl_course_uuid-d" class="form-control quiz_course_title-d input_radius-s">
                                            <option value="">Select an Option</option>
                                            @foreach (getTeacherCoursesList() as $uuid => $title)
                                                <option value="{{ $uuid }}">{{ $title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <label class="font-weight-normal course_textarea-s ml-3" for="ddl_course_slot-d">Select a Slot:</label>
                                        <select name="slot_uuid" id="ddl_course_slot-d" class="form-control quiz_course_title-d input_radius-s">
                                            <option value=''>Select an Option</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="row pt-5">
                                    <div class="col-12 col-sm-6">
                                        <label class="font-weight-normal course_textarea-s ml-3 quiz_duration-d" for="quiz_duration" for="cars">Due Date:</label>
                                        <input type="date" name="due_date" id="" class="form-control input_radius-s">
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        &nbsp;
                                    </div>
                                </div>

                                <div class="row pt-5">
                                    <div class="col-12 ">
                                        <label for="comment" class="course_textarea-s" for="comment_text">Quiz Description</label>
                                        <textarea class="form-control textarea_h-s quiz_description-d" rows="5" id="comment_bg-d" name="description"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class=" col-3 w-100 mx-auto align-self-center modal-footer border-0 mb-4">
                                        <button type="submit" class="text-center py-xl-3 py-lg-3 py-md-2 py-2 w-100 fg_white-s bg_success-s br_27px-s custom-button border border-white">Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Modal Body End -->


                <!-- Modal footer -->


                <!-- Modal Footer End -->
            </div>
        </div>
    </div>
    <!-- The Modal End -->




    <div class="cloneables_containers-d" style='display:none;'>
        <div class="col-12 my-2 bg_white-s br_10px-s single_quiz_container-d" id="cloneable_quiz_container-d">
            <div class="row py-3">
                <div class="col-xl-6 col-lg-6 col-md-12 col-12">
                    <a class='link-d' href="{{ route('quiz.testQuestion', $item->uuid ?? '______') }}">
                        <h5 class="fg-success-s title-d">
                            {{ $item->course->title ?? '' }}
                        </h5>
                    </a>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-12 col-12 fg-success-s text--xl-right">
                    <h6>
                        Type: <span class='type-d'>{{ strtoupper($item->type ?? '') }}</span>
                        <span>
                            <img class="pl-3" src="{{asset('assets/images/clock_icon.svg')}}" alt="">
                        </span>
                        <span class=''>
                            <strong class='duration-d'>
                                {{ $item->duration_mins ?? '0' }}
                            </strong>
                            Minutes
                        </span>
                    </h6>
                </div>
            </div>
                <div class="row">
                    <div class="col-11 fg_dark-s">
                        <p class="description-d">{{ $item->description ?? '' }}</p>
                    </div>
                </div>
            <div class="row py-3">
                <div class="col-xl-3 col-lg-6 col-md-5 col-12 fg_dark-s">
                    <span>
                        Total Students: <strong class='students_count-d'>{{ $item->students_count ?? '0' }}</strong>
                    </span>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-7 col-12 fg_dark-s">
                    <span>
                        Attending Test Student:  <strong class='attempts_count-d'>{{ $item->attempts_count ?? '0' }}</strong>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>




@endsection


@section('footer-scripts')
    <script src="{{ asset('assets/js/quiz.js') }}"></script>
@endsection

@section('header-css')
    <link rel="stylesheet" href="{{ asset('assets/css/course.css') }}" />
@endsection


@push('header-scripts')
    <script>
        let modal_delete_outline_url = "{{ route('course.delete-outline') }}";
        let modal_delete_slot_url = "{{ route('course.delete-slot') }}";
        let modal_delete_video_content_url = "{{ route('course.delete-video-content') }}";
        let quiz_get_slots_by_course = "{{ route('course.get-slots-by-course') }}";
    </script>
@endpush
