@extends('teacher::layouts.teacher')

@section('page-title')
   True False
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row mx-xl-3 mx-lg-3 flex-column-reverse flex-lg-row">
            <div class="col-12 col-md-12 col-lg-8 col-xl-8">
                <!-- true false top heading - START -->
                <div class="row mt-3">
                    <div class="col-xl-6 col-lg-6 col-md-12 col-12 mt-5">
                        <h4 class='quiz-heading-d'><strong>{{ $data->title ?? '' }}</strong></h4>
                    </div>
                    <div class="col-12 mt-4">
                        <p class='quiz-description-d'>
                            {{ $data->description ?? '' }}
                        </p>
                    </div>
                    <hr class="col-xl-11 col-lg-10 col-md-10 col-sm-10 col-10 w-100 ml-3 dotted_border_for_hr-s">
                </div>

                <div class="quiz_questions_main_container-d">
                    @forelse ($data_questions as $item)
                        <div class="row mt-3 single_question_container-d q_uuid_{{ $item->uuid ?? '' }}">
                            <div class="col-xl-2 col-lg-3 col-md-3 col-3">
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
                                <p class='question_body-d'>
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
                                                        class="form-check-input rb_choice-d ans_uuid_{{ $choice->uuid ?? '' }}"
                                                        @if($item->correct_answer_id == $choice->id) checked="checked" @endif
                                                        name="{{ 'q_'.$item->uuid ?? '' .'_ans' }}"
                                                    >{{ $choice->body ?? '' }}
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
                            <form action="">
                                <div class="card-body">
                                    <h5 class="card-title text_muted-s">ADD Question</h5>
                                    <textarea class="w-100 min_h_132px-s max_h_132px-s rounded pl-2 br_color_grey-s" name="question_body"></textarea>
                                    <div class="row mt-4 ">
                                        <div class="col">
                                            <div class="col-12 ml-1 text_muted-s pl-0 mb-3">
                                                <span>Options</span>
                                            </div>
                                            <!-- form true false options - START -->
                                            <div class="row pb-2 frm_choices_container-d">
                                                <div class="col-9">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text br_top_bottom_40px_left-s bg_white-s br_right_0px-s">
                                                                <input type="radio" aria-label="Radio button for following text input" class='img_20_x_20-s' name='choice_cb[]' />
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control form-control-lg login_input-s br_left_0px-s" name="choice_body[]" aria-hidden="true" placeholder="Option Choice" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row pb-2 frm_choices_container-d">
                                                <div class="col-9">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text br_top_bottom_40px_left-s bg_white-s br_right_0px-s">
                                                                <input type="radio" aria-label="Radio button for following text input" class='img_20_x_20-s' name='choice_cb[]' />
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control form-control-lg login_input-s br_left_0px-s" name="choice_body[]" aria-hidden="true" placeholder="Option Choice" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- form save buttons - START -->
                                    <div class="row mt-5 mb-5 justify-content-center ">
                                        <div class="col-6 text-center ">
                                            <a href="# " class="btn bg_success-s text-white br_19px-s px-4 px-md-5 px-lg-4 px-xl-5 ">Save</a>
                                        </div>
                                        <div class="col-6 text-center ">
                                            <a href="# " class="btn bg_info-s text-white br_19px-s px-4 px-md-5 px-lg-4 px-xl-5 ">Add</a>
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
        let modal_delete_test_quiz_url = "{{ route('quiz.delete-test-quiz') }}";
    </script>
@endpush
