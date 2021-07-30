@extends('teacher::layouts.teacher')

@section('page-title')
   Mcqs
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mx-xl-3 mx-lg-3 flex-column-reverse flex-lg-row">
        <div class="col-12 col-md-12 col-lg-8 col-xl-8">
            <!-- multiple choice top heading - START -->
            <div class="row mt-3">
                <div class="col-xl-6 col-lg-6 col-md-12 col-12 mt-5">
                    <h4><strong>{{ $data->title }}</strong></h4>
                </div>
                <div class="col-12 mt-4">
                    <p>{{ $data->description }}</p>
                </div>
                <hr class="col-xl-11 col-lg-10 col-md-10 col-sm-10 col-10 w-100 ml-3 dotted_border_for_hr-s">
            </div>
            <!-- multiple choice top heading - END -->

            <!-- Multiple Choice Questions - START -->
            <!-- question -1 - start -->
            <div class="row mt-3">
                <div class="col-xl-2 col-lg-3 col-md-3 col-3">
                    <span>Question: 1</span><br>
                    <span>
                        <img src="assets/preview/edit_icon.svg" alt="">
                        <img src="assets/preview/delete_icon.svg" alt="">
                    </span>
                </div>
                <div class="col-9">
                    <p>Lorem Ipsum is simply dummy text of the printing and has been the industry's standard dummy text ever since the 1500s?</p>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-xl-4 multiple_choice_radio-s  offset-xl-2 offset-lg-3 offset-md-3 offset-3">
                            <div class="form-check mt-3">
                                <label class="form-check-label">
                                 <input type="radio" class="form-check-input green" name="optradio">Option 1
                                </label>
                            </div>
                            <div class="form-check mt-3">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="optradio">Option 2
                                </label>
                            </div>
                            <div class="form-check mt-3">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="optradio" >Option 3
                                </label>
                            </div>
                            <div class="form-check mt-3">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="optradio" >Option 3
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- question -1 - end -->
            <!-- Multiple Choice Questions - END -->
        </div>

        <!-- multiple choice form - START -->
        <div class="mt-5 mt-lg-5 mt-xl-5 col-12 col-md-12 col-lg-4 col-xl-4">
            <div class="row mt-5 mt-md-5 mt-lg-5 mt-xl-5 pt-xl-4  pt-lg-4 ">
                <div class="col-12 px-xl-0 px-lg-0">
                    <div class="card w-auto shadow border-0">
                        <form action="{{ route('quiz.multiple-choice', $data->uuid) }}" id="frm_mcq_question-d" method="POST" class="frm_mcq_question-d"}}">
                            @csrf
                            <input type="hidden" name="quiz_mcqs_uuid" value="{{ $data->uuid }}">
                                <input type="hidden" name="assignee_id" value="{{ $data->assignee->uuid }}">
                                <input type="hidden" name="mcqs_question" id="mcqs_uuid-d" value="">

                            <div class="card-body">
                                <h5 class="card-title text_muted-s">ADD Question</h5>
                                <textarea class="w-100 min_h_132px-s max_h_132px-s rounded pl-2 br_color_grey-s" name="question_mcqs" id="question_mcqs-d">
                                </textarea>
                                <div class="row mt-4">
                                    <div class="col">
                                        <div class="col-12 ml-1 text_muted-s">
                                            <span>Add Option</span>
                                        </div>
                                        <!-- multiple choice options - START -->
                                        <div class="row">
                                            <div class="col-9 ">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text br_top_bottom_40px_left-s bg_white-s br_right_0px-s">
                                                            <input type="checkbox" class="radio_size-s" aria-label="Radio button for following text input" class="mcqs_opt-d" name="mcqs_opt-uuid" id="uuid_mcqs_opt-d">
                                                        </div>
                                                    </div>
                                                    <input type="text " class="form-control form-control-lg login_input-s br_left_0px-s mcqs_opt-d" name="mcqs_opt[] " id="mcqs_opt " aria-hidden="true" placeholder="Web Desiging ">
                                                </div>
                                            </div>
                                            <div class="col-2 align-self-center ">
                                                <a href=" "><img src="assets/preview/cancel.svg " alt=" "></a>
                                            </div>
                                        </div>

                                        <!-- multiple choice options - END -->
                                    </div>
                                </div>
                                <div class="row mt-4 mb-5 justify-content-xl-end justify-content-lg-center justify-content-md-end justify-content-sm-center">
                                    <div class="col-12 col-lg-12 col-md-6 col-xl-6 text-right pr-xl-5 pr-md-5 ">
                                        <a href="# " class="btn bg_light_dark-s br_19px-s px-4 px-md- px-lg-4 px-xl-4 ">Add more</a>
                                    </div>

                                </div>
                                <!-- multiple choice button - START -->
                                <div class="row mt-4 mb-5 justify-content-center ">
                                    <div class="col-6 text-center ">
                                        <a href="# " class="btn bg_success-s text-white br_19px-s px-4 px-md-5 px-lg-4 px-xl-5 ">Save</a>
                                    </div>
                                    <div class="col-6 text-center ">
                                        <a href="# " class="btn bg_info-s text-white br_19px-s px-4 px-md-5 px-lg-4 px-xl-5 ">Add</a>
                                    </div>
                                </div>
                                <!-- multiple choice button - END -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- multiple choice form - END -->
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
    </script>
@endpush
