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
                        <h5>{{ $data->title }}</h5>
                    </div>
                    <div class="col-12 mt-4">
                        <p>
                            {{ $data->description }}
                        </p>
                    </div>
                    <hr class="col-10 w-100 ml-3 dotted_border_for_hr-s">
                </div>
                <!-- true false top heading - END -->

                <!-- true false Questions - START -->

                <!-- question -1 - start -->
                <div class="boolean_container_main-d">
                    @if (isset($data_questions) && ('' !=$data->questions))
                    @forelse ($data_questions as $item)
                            <div class="row mt-3 single_boolean_question-d uuid_{{ $item->uuid ?? '' }}">
                                <div class="col-xl-2 col-lg-2 col-md-2 col-3">
                                    <span>Q:  {{ $loop->iteration }}</span><br>
                                    <input type="hidden" class="quiz_question_uuid-d" value='{{ $item->uuid ?? '' }}' />
                                        <a href="javascript:void(0)" class='delete_boolean_question-d'>
                                            <img src="{{ asset('assets/images/delete_icon.svg') }}" alt="delete-boolean_question" />
                                        </a>
                                        <a href="javascript:void(0)" class='edit_boolean_question-d'>
                                            <img src="{{ asset('assets/images/edit_icon.svg') }}" alt="edit-boolean_question" />
                                        </a>
                                </div>
                                <div class="col-9">
                                    <p class="boolean_question_body-d">{{ $item->body }}</p>
                                </div>
                                <div class="container multiple_boolean_cloned-d">
                                    @foreach($item->choices as $key=> $choice)
                                        {{-- {{ dd($choice) }} --}}
                                        <div class="col-12 option-d single_cloneable_option-d">
                                            <div class="row">
                                                <div class="col-xl-4 multiple_choice_radio-s  offset-xl-2 offset-lg-2 offset-md-2 offset-3">
                                                    <div class="form-check mt-3 options-d">
                                                        {{-- <label class="form-check-label"> --}}
                                                        <input type="radio" class="form-check-input correct_answer_id-d rb_choice-d" name='{{ 'q_'.$item->uuid.'_ans' }}' @if($item->correct_answer_id == $choice->id) checked="checked" @endif id="correct-d" value="{{ $choice->uuid }}" /> 
                                                        <label class="correct_answer-d">{{ $choice->body ?? ''}}</label>
                                                        {{-- </label> --}}
                                                    </div>
                                                    {{-- <div class="form-check mt-3"> --}}
                                                        {{-- <label class="form-check-label"> --}}
                                                            {{-- <input type="radio" class="form-check-input correct_answer_id-d" name="optradio">{{ $choice->body }} --}}
                                                        {{-- </label> --}}
                                                    {{-- </div> --}}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach    
                                </div>
                            </div>
                        @empty
                        @endforelse
                    @endif
                </div>
                <!-- question -1 - end -->
                <!-- True False Questions - END -->
            </div>

            <!-- True false form - START -->
            <div class="mt-5 mt-lg-5 mt-xl-5 col-12 col-md-12 col-lg-4 col-xl-4">
                <div class="row mt-5 mt-md-5 mt-lg-5 mt-xl-5 pt-xl-4  pt-lg-4 ">
                    <div class="col-12 px-xl-0 px-lg-0">
                        <div class="card w-auto shadow border-0">
                            <form action="{{ route('quiz.boolean-question', $data->uuid) }}" id="frm_boolean_question-d" method="POST" class="frm_boolean_question-d">
                                @csrf
                                <input type="hidden" name="quiz_boolean_uuid" value="{{ $data->uuid }}">
                                <input type="hidden" name="assignee_id" value="{{ $data->assignee->uuid }}">
                                <input type="hidden" name="answer_boolean_question" id="boolean_question_uuid-d" value="">


                                <div class="card-body">
                                    <h5 class="card-title">Add Question</h5>
                                    <textarea class="w-100 min_h_132px-s max_h_132px-s" name="add_boolean_question_textarea" id="boolean_question_title-d"></textarea>
                                    <div class="row">
                                        <div class="col">
                                            <div class="col-12 ml-1">
                                                <span>Add Option</span>
                                            </div>
                                            <div class="ans_main_container-d">
                                                <!-- form true false options - START -->
                                                <div class="row singe_ans_container-d">
                                                    <div class="col-9 ">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text br_top_bottom_40px_left-s bg_white-s br_right_0px-s">
                                                                    <input type="checkbox" aria-label="Radio button for following text input" class='option_1-d chkbx_choice-d' name="boolean_answer_12"  id="uuid_option_1-d">
                                                                </div>
                                                            </div>
                                                            <input type="text " class="form-control form-control-lg login_input-s br_left_0px-s choice_option-d" name="boolean_option_1" id="boolean_option_1-d" aria-hidden="true" placeholder="Web Desiging ">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mt-2 singe_ans_container-d">
                                                    <div class="col-9 ">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text br_top_bottom_40px_left-s bg_white-s br_right_0px-s">
                                                                    <input type="checkbox" aria-label="Radio button for following text input" class='option_2-d chkbx_choice-d' name="boolean_answer_22" id="uuid_option_2-d">
                                                                </div>
                                                            </div>
                                                            <input type="text " class="form-control form-control-lg login_input-s br_left_0px-s choice_option-d" name="boolean_option_2" id="boolean_option_2-d" aria-hidden="true" placeholder="Web Desiging ">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- form true false options - END -->
                                        </div>
                                    </div>
                                    <!-- form save buttons - START -->
                                    <div class="row mt-4 mb-5 justify-content-center ">
                                        <div class="col-6 text-center ">
                                            <input type='hidden' name='answers' class='all_answers-d' />
                                            <button type="submit" class="btn bg_success-s text-white br_19px-s px-4 px-md-5 px-lg-4 px-xl-5 ">Save</a>
                                        </div>
                                        {{-- <div class="col-6 text-center ">
                                            <a href="# " class="btn bg_info-s text-white br_19px-s px-4 px-md-5 px-lg-4 px-xl-5 ">Add</a>
                                        </div> --}}
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



    <div class="boolean_question_container-d" style="display: none">
        <div class="row mt-3 single_boolean_question-d" id="single_clonable_boolean_question-d">
            <div class="col-xl-2 col-lg-2 col-md-2 col-3">
                <span>Q:  {{ $loop->iteration ?? '' }}</span><br />
                <input type="hidden" class="quiz_question_uuid-d" value='{{ $item->uuid ?? '' }}' />
                <a href="javascript:void(0)" class='delete_boolean_question-d'>
                    <img src="{{ asset('assets/images/delete_icon.svg') }}" alt="delete-boolean_question" />
                </a>
                <a href="javascript:void(0)" class='edit_boolean_question-d'>
                    <img src="{{ asset('assets/images/edit_icon.svg') }}" alt="edit-boolean_question" />
                </a>
            </div>
            <div class="col-9">
                <p class="boolean_question_body-d">{{ $item->body ?? '' }}</p>
            </div>
            {{-- {{-- @if (isset($item->choices)) --}}
            <div class="container multiple_boolean_cloned-d">
                
            </div>        
            {{-- @endif --}}
        </div>

        <div class="col-12 option-d single_cloneable_option-d" id='single_cloneable_option-d'>
            <div class="row">
                <div class="col-xl-4 multiple_choice_radio-s offset-xl-2 offset-lg-2 offset-md-2 offset-3">
                    <div class="form-check mt-3 options-d">
                        {{-- <label class="form-check "> --}}
                            {{-- <input type="hidden" name="correct_answer-d" class='correct_answer-d'> --}}
                            <input type="radio" class="form-check-input correct_answer_id-d rb_choice-d" id="correct-d" value="{{ $choice->uuid }}">
                            <label class="correct_answer-d"></label>
                        {{-- </label> --}}
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
        let modal_delete_test_quiz_url = "{{ route('quiz.delete-test-quiz') }}";
    </script>
@endpush
