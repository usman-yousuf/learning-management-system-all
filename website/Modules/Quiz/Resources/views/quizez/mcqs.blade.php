@extends('teacher::layouts.teacher')

@section('page-title')
   MCQs Quiz
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row mx-xl-3 mx-lg-3 flex-column-reverse flex-lg-row">
            <div class="col-12 col-md-12 col-lg-8 col-xl-8 mt-5">
                <!-- true false top heading - START -->
                <div class="row mt-3">
                    <div class="angle_left-s col-xl-1 col-lg-2 col-md-12 col-sm-12 col-12 text-left pr-0 ">
                        @php
                            $backRoute = route('quiz.index');
                        @endphp
                        <a href="{{ $backRoute }}">
                            <img src="{{ asset('assets/images/angle_left_icon.svg') }}" class="shadow p-3 bg-white rounded" alt="back">
                        </a>
                    </div>
                    <div class="col-xl-11 col-lg-10 col-md-12 col-sm-12 col-12 align-self-center">
                        <h4 class='quiz-heading-d'><strong>{{ ucwords($data->title ?? '') }}</strong></h4>
                        <h5 class="text-success">
                            <strong>{{ ucwords($data->type ?? '') }} Quiz</strong> - <span class="quiz_course-d">{{ ucwords($data->course->title ?? '') }}</span>
                        </h5>
                    </div>

                    <div class="col-12 mt-4">
                        <p class='quiz-description-d'>
                            {{ $data->description ?? '' }}
                        </p>
                    </div>
                    <hr class="col-lg-10 col-md-10 col-sm-10 col-10 w-100 ml-3 dotted_border_for_hr-s">
                </div>

                <div class="quiz_questions_main_container-d">
                    @forelse ($data_questions as $item)
                        <div class="row mt-3 single_question_container-d q_uuid_{{ $item->uuid ?? '' }}">
                            <div class="col-xl-2 col-lg-3 col-md-3 col-3 ">
                                <span>
                                    Question:
                                    <strong class='question_serial-d'>
                                        {{ get_padded_number($loop->iteration) }}
                                    </strong>
                                </span><br>

                                <input type="hidden" class="question_uuid-d" value='{{ $item->uuid ?? '' }}' />
                                <a href="javascript:void(0)" class='delete_boolean_question-d'>
                                    <img src="{{ asset('assets/images/delete_icon.svg') }}" alt="delete-boolean_question" />
                                </a>
                                <a href="javascript:void(0)" class='edit_boolean_question-d'>
                                    <img src="{{ asset('assets/images/edit_icon.svg') }}" alt="edit-boolean_question" />
                                </a>
                            </div>
                            <div class="col-9">
                                <p class='question_body-d text-wrap text-break fs_19px-s'>
                                    {{ $item->body }}
                                </p>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-xl-4 multiple_choice_radio-s offset-xl-2 offset-lg-3 offset-md-3 offset-3 question_choices_container-d">
                                        @forelse ($item->choices as $choice)
                                            <div class="form-check mt-3 single_choice_container-d ans_uuid_{{ $choice->uuid ?? '' }}">
                                                <label class="form-check-label">
                                                    <input
                                                        type="radio"
                                                        disabled
                                                        class="form-check-input rb_choice-d {{ $choice->uuid ?? '' }}"
                                                        @if($item->correct_answer_id == $choice->id) checked="checked" @endif
                                                        name="{{ 'q_'.$item->uuid ?? '' .'_ans' }}"
                                                        value="{{ $choice->uuid ?? '' }}"
                                                    />
                                                    <span class='choice_body-d pl-3 fs_19px-s'>{{ $choice->body ?? '' }}</span>
                                                </label>
                                            </div>
                                        @empty
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                    @endforelse
                </div>
            </div>

            <!-- True false form - START -->
            <div class="mt-5 mt-lg-5 mt-xl-5 col-12 col-md-12 col-lg-4 col-xl-4">
                <div class="row mt-5 mt-md-5 mt-lg-5 mt-xl-5 pt-xl-4  pt-lg-4 ">
                    <div class="col-12 px-xl-0 px-lg-0">
                        <div class="card w-auto shadow border-0">
                            <form id='frm_boolean_question-d' action="{{ route('quiz.boolean-question', ['uuid' => $data->uuid]) }}">
                                <div class="card-body">
                                    <h5 class="card-title text_muted-s">ADD Question</h5>
                                    <textarea class="w-100 min_h_132px-s max_h_132px-s rounded pl-2 br_color_grey-s txtarea_q_body-d" name="question_body" placeholder="Question Body"></textarea>
                                    <div class="row mt-4 ">
                                        <div class="col">
                                            <div class="col-12 ml-1 text_muted-s pl-0 mb-3">
                                                <span>Options</span>
                                            </div>
                                            <div class="frm_choices_container-d">
                                                <div class="row pb-2 frm_single_choice_container-d">
                                                    <div class="col-9">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text br_top_bottom_40px_left-s  bg_white-s br_right_0px-s">
                                                                    <input type="radio" aria-label="Radio button for following text input" name='frm_cb_option' class='mt-0 img_20_x_20-s cb_is_correct_option-d' />
                                                                </div>
                                                            </div>
                                                            <input type="text" class="form-control form-control-lg login_input-s br_left_0px-s txt_option_body-d" aria-hidden="true" placeholder="Option Choice" />
                                                        </div>
                                                    </div>
                                                    <div class="col-2 align-self-center ">
                                                        <a href="javascript:void(0)" class='remove-option-d'>
                                                            <img src="{{ asset('assets/images/cancel.svg') }}" alt="Remove" />
                                                        </a>
                                                    </div>
                                                </div>

                                                <div class="row pb-2 frm_single_choice_container-d">
                                                    <div class="col-9">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text br_top_bottom_40px_left-s bg_white-s br_right_0px-s">
                                                                    <input type="radio" aria-label="Radio button for following text input" name='frm_cb_option' class='img_20_x_20-s cb_is_correct_option-d' />
                                                                </div>
                                                            </div>
                                                            <input type="text" class="form-control form-control-lg login_input-s br_left_0px-s txt_option_body-d" aria-hidden="true" placeholder="Option Choice" />
                                                        </div>
                                                    </div>
                                                    <div class="col-2 align-self-center ">
                                                        <a href="javascript:void(0)" class='remove-option-d'>
                                                            <img src="{{ asset('assets/images/cancel.svg') }}" alt="Remove" />
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-4 mb-5 justify-content-xl-end justify-content-lg-center justify-content-md-end justify-content-sm-center">
                                        <div class="col-12 col-lg-12 col-md-6 col-xl-12 text-right pr-xl-5 pr-md-5 ">
                                            <a href="javascript:void(0)" class="btn bg_light_dark-s br_19px-s px-4 px-md- px-lg-4 px-xl-4 btn_add_more_option-d">Add more</a>
                                        </div>
                                    </div>
                                    <!-- form save buttons - START -->
                                    <div class="row mt-4 mb-4 justify-content-center ">
                                        <div class="col-6 text-center ">
                                            <input type="hidden" name="question_uuid" id="question_uuid-d" value="" />
                                            <input type="hidden" name="quiz_uuid" id="quiz_uuid-d" value="{{ $data->uuid ?? '' }}" />
                                            <input type="hidden" name="answers" id="answers_json-d" />
                                            <button type="submit" class='btn bg_success-s text-white br_19px-s px-4 px-md-5 px-lg-4 px-xl-5'>Save</button>
                                        </div>
                                        <div class="col-6 text-center invisible">
                                            <a href="javascript:void(0)" class="btn bg_info-s text-white br_19px-s px-4 px-md-5 px-lg-4 px-xl-5 btn_add_option-d">Add</a>
                                        </div>
                                    </div>
                                    <!-- form save buttons - END -->
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- True flase form - END -->
        </div>
    </div>

    <div class="cloneables_container-d" style="display: none">
        {{-- form choice container - START --}}
        <div class="row pb-2 frm_single_choice_container-d" id='cloneable_frm_single_choice_container-d'>
            <div class="col-9">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text br_top_bottom_40px_left-s bg_white-s br_right_0px-s">
                            <input type="radio" aria-label="Radio button for following text input" name='frm_cb_option' class='img_20_x_20-s cb_is_correct_option-d' disabled/>
                        </div>
                    </div>
                    <input type="text" class="form-control form-control-lg login_input-s br_left_0px-s txt_option_body-d pl-3" aria-hidden="true" placeholder="Option Choice" />
                </div>
            </div>
            <div class="col-2 align-self-center ">
                <a href="javascript:void(0)" class='remove-option-d'>
                    <img src="{{ asset('assets/images/cancel.svg') }}" alt="Remove" />
                </a>
            </div>
        </div>
        {{-- form choice container - END --}}

        {{-- single question container - START --}}
        <div class="row mt-3 single_question_container-d" id='cloneable_single_question_container-d'>
            {{-- question and its body - START --}}
            <div class="col-xl-2 col-lg-3 col-md-3 col-3">
                <span>
                    Question: <strong class='question_serial-d'>
                        {{ get_padded_number(1) }}
                    </strong>
                </span><br>

                <input type="hidden" class="question_uuid-d" value='' />
                <a href="javascript:void(0)" class='delete_boolean_question-d'>
                    <img src="{{ asset('assets/images/delete_icon.svg') }}" alt="delete-boolean_question" />
                </a>
                <a href="javascript:void(0)" class='edit_boolean_question-d'>
                    <img src="{{ asset('assets/images/edit_icon.svg') }}" alt="edit-boolean_question" />
                </a>
            </div>
            <div class="col-9">
                <p class='question_body-d text-wrap text-break'>{{ $item->question_body ?? '' }}</p>
            </div>
            {{-- question and its body - END --}}

            <div class="col-12">
                <div class="row">
                    <div class="col-xl-4 multiple_choice_radio-s offset-xl-2 offset-lg-3 offset-md-3 offset-3 question_choices_container-d">
                    </div>
                </div>
            </div>
        </div>
        {{-- single question container - END --}}

        {{-- single choice container - START --}}
        <div class="form-check mt-3 single_choice_container-d" id='cloneable_single_choice_container-d'>
            <label class="form-check-label">
                <input
                    type="radio"
                    class="form-check-input rb_choice-d"
                    name="{{ 'q_'.'_ans' }}"
                />
                <span class='choice_body-d pl-3'></span>
            </label>
        </div>
        {{-- single choice container - END --}}
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
        // let modal_delete_outline_url = "{{ route('course.delete-outline') }}";
        // let modal_delete_slot_url = "{{ route('course.delete-slot') }}";
        // let modal_delete_video_content_url = "{{ route('course.delete-video-content') }}";
        let modal_quiz_question_url = "{{ route('quiz.delete-question') }}";
    </script>
@endpush
