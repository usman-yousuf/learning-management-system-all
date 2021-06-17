@extends('teacher::layouts.teacher')

@section('page-title')
   Test Question
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row mx-xl-3 mx-lg-3 flex-column-reverse flex-lg-row">
            <div class="col-12 col-md-12 col-lg-8 col-xl-8">
                <!-- test question top heading - START -->
                <div class="row mt-3 ">
                    <div class="col-xl-4 col-lg-6 col-md-12 col-12 mt-5">
                        <h5>{{ $data->title }}</h5>
                    </div>
                    <div class="col-12 mt-4">
                        <p>
                            {{ $data->description }}
                        </p>
                    </div>
                    <hr class="col-10 w-100 ml-3 dotted_border_for_hr-s">
                </div>
                <!-- test question top heading - END -->

                <!-- Test Questions - START -->
                <!-- question -1 - start -->
                <div class="test_questions_main-d">
                    @forelse ($data_questions as $key => $item)
                        <div class="row mt-3 single_test_question-d uuid_{{ $item->uuid ?? '' }}" >
                            <div class="col-xl-2 col-lg-2 col-md-2 col-3">
                                <span>Q: {{ $loop->iteration }}</span><br>
                                <input type="hidden" class="question_uuid-d" value='{{ $item->uuid ?? '' }}' />
                                    <a href="javascript:void(0)" class='delete_test_question-d'>
                                        <img src="{{ asset('assets/images/delete_icon.svg') }}" alt="delete-test_question" />
                                    </a>
                                    <a href="javascript:void(0)" class='edit_test_question-d'>
                                        <img src="{{ asset('assets/images/edit_icon.svg') }}" alt="edit-test_question" />
                                    </a>
                            </div>
                            <div class="col-9">
                                <p class="body-d" >{{ $item->body }}</p>
                            </div>
                            <div class="col-12">
                                <textarea class="w_100-s textarea_h_70px-s br_10px-s correct_answer-d " id="test_quiz_ans-d" name="test_quiz_ans"> {{ $item->correct_answer }}
                                </textarea>
                            </div>
                        </div>
                    @empty

                    @endforelse
                </div>

                <!-- question -1 - end -->



                <!-- Test Questions - END -->
            </div>
            <div class="mt-5 mt-lg-5 mt-xl-5 col-12 col-md-12 col-lg-4 col-xl-4">
                <div class="row mt-5 mt-md-5 mt-lg-5 mt-xl-5 pt-xl-4  pt-lg-4 ">
                    <div class="col-12 px-xl-0 px-lg-0">
                        <form action="{{ route('quiz.addTestQuestion', $data->uuid) }}" id="frm_test_question-d" method="POST" class="frm_test_question-d">
                            @csrf
                            <input type="hidden" name="assignee_id" value="{{ $data->assignee->uuid }}">
                            <input type="hidden" name="quiz_test_uuid" value="{{ $data->uuid }}">
                            <input type="hidden" name="answer_test_question" id="test_question_uuid-d" value="">
                            <div class="card w-auto shadow border-0">
                                <div class="card-body">
                                    <h5 class="card-title text_muted-s">Add Question</h5>
                                    <textarea class="w-100 min_h_132px-s max_h_132px-s br_color_grey-s" name="add_question_textarea" id="test_title-d" placeholder="Question Body"></textarea>

                                    <h5 class="card-title text_muted-s">Answer</h5>
                                    <textarea class="w-100 min_h_132px-s max_h_132px-s br_color_grey-s" name="add_answer_textarea" id="test_question_answer-d" placeholder="Answer Body"></textarea>

                                    <div class="row mt-4 mb-5 justify-content-center">

                                        <div class="col-6 text-center">
                                            <button type="submit" class="btn bg_success-s text-white br_19px-s px-4 px-md-5 px-lg-4 px-xl-5">Save</button>
                                        </div>
                                        <div class="col-6 text-center">
                                            {{-- <a href="#" class="btn bg_info-s text-white br_19px-s px-4 px-md-5 px-lg-4 px-xl-5">Add</a> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div id="test_question_container-d" style="display: none">
        <div class="row mt-3 single_test_question-d"  id="single_clonable_question_test-d">
            <div class="col-xl-2 col-lg-2 col-md-2 col-3">
                <span>Q: {{ $loop->iteration ?? '' }}</span><br>
                <input type="hidden" class="question_uuid-d" value='{{ $item->uuid ?? '' }}' />

                    <a href="javascript:void(0)" class='edit_test_question-d'>
                        <img src="{{ asset('assets/images/edit_icon.svg') }}" alt="edit-test_question" />
                    </a>
                    <a href="javascript:void(0)" class='delete_test_question-d'>
                        <img src="{{ asset('assets/images/delete_icon.svg') }}" alt="delete-test_question" />
                    </a>
            </div>
            <div class="col-9">
                <p class="body-d" >{{ $item->body ?? '' }}</p>
            </div>
            <div class="col-12">
                <textarea class="w_100-s textarea_h_70px-s br_10px-s correct_answer-d " id="test_quiz_ans-d" name="test_quiz_ans"> {{ $item->correct_answer ?? '' }}
                </textarea>
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
        let modal_delete_test_question_url = "{{ route('quiz.delete-test-question') }}";
    </script>
@endpush
