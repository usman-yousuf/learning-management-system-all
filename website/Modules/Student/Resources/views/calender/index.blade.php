@extends('teacher::layouts.teacher')

@section('page-title')
   Multiple Choice Questions
@endsection


@section('content')

    {{-- //MCQS Result --}}
    <button type="button" class="btn btn-primary mt-3" data-toggle="modal" data-target="#mcqs_result-d">
        MCQs result
    </button>

    {{-- // Start MCQs --}}
    <button type="button" class="btn btn-primary mt-3" data-toggle="modal" data-target="#start_mcqs-d">
        Start MCQs
    </button>

    {{-- {{ dd($attempted_quiz) }} --}}

    <!--MCQs Result modal-->
    <div class="modal fade" id="mcqs_result-d" tabindex="-1"  aria-labelledby="view-head" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" >
            <div class="modal-content mb-5">
                <div class="modal-header d-block">
                    <div class="container pb-5">
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
                        <div class="modal-body mb-5">
                            <div class="row pb-4">
                                <div class="col-12 text-center">
                                    <h2 class="fg-success-s pb-5">{{ strtoupper($attempted_quiz->quiz->type) }}</h2>
                                </div>
                            </div>
                            <div class="row pb-3 pl-xl-5">
                                <div class="col-xl-7 col-lg-6 col-md-12  col-12">
                                    <a class='no_link-s link-d'href="javascript:void(0)">
                                        <h4 class=" title-d">
                                            {{ $attempted_quiz->course->title }}
                                        </h4>
                                        <h5 class="fg-success-s">
                                            {{ $attempted_quiz->quiz->title }}
                                        </h5>
                                    </a>
                                </div>
                                <div class="col-xl-4 col-lg-5 col-md-12 col-12 mt-xl-0 mt-lg-0 mt-md-3 mt-3 ml-lg-4 ml-xl-0 ml-0 text-xl-right text-lg-right">
                                    <span class="text_muted-s">
                                        Quiz Type
                                    </span>
                                    <span class="ml-3 font-weight-bold  ">
                                        {{ strtoupper($attempted_quiz->quiz->type) }}
                                    </span>
                                </div>
                            </div>
                            <div class="row pl-xl-5">
                                <div class="col-xl-11 col-lg-12 col-md-12 col-12 fg_dark-s">
                                    <p>{{ $attempted_quiz->quiz->description }}</p>
                                </div>
                            </div>
                            <div class="row py-3 pl-xl-5 pb-5">
                                <div class="col-xl-3 col-lg-3 col-md-6 col-12 mt-xl-0 mt-lg-0 mt-md-0 mt-3 fg-success-s">
                                    <span>
                                        Total Mark: <strong class='students_count-d'>{{ $attempted_quiz->total_marks }}</strong>
                                    </span>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-6 col-12 mt-xl-0 mt-lg-0 mt-md-0 mt-3 fg-success-s">
                                    <span>
                                        Obtained Mark:  <strong class='attempts_count-d'>{{ $attempted_quiz->total_correct_answers * $attempted_quiz->marks_per_question }}</strong>
                                    </span>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-6 col-12 mt-xl-0 mt-lg-0 mt-md-3 mt-3 fg-success-s text-lg-center">
                                    <span>
                                        Complete
                                    </span>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-6 col-12 mt-xl-0 mt-lg-0 mt-md-3  mt-3 fg_dark-s">
                                    <span >
                                        <img class="img_25_x_25-s" src="{{ asset('assets/images/student_quiz_calender.svg') }}" alt="calender">
                                    </span>
                                    <span class="pl-2">
                                       {{-- {{  date('Y-m-d', strtotime($attempted_quiz->created_at))}}  <br> --}}
                                       {{ \Carbon\Carbon::parse($attempted_quiz->created_at)->isoFormat('Do MMM YYYY')}}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!--view modal body end-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--MCQs Result modal end-->
    @foreach ($new_quiz_attempt->quizzes as $data)
                   <!--Start MCQs test-->
                        <div class="modal fade" id="start_mcqs-d" tabindex="-1"  aria-labelledby="view-head" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-xl" >
                                <div class="modal-content ">
                                    <div class="modal-header d-block">
                                        <div class="container pb-5">
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
                                            <div class="modal-body ">
                                                <div class="row pb-4">
                                                    <div class="col-12 text-center">
                                                        <h2 class="fg-success-s pb-5">{{ strtoupper($data->type) }}</h2>
                                                    </div>
                                                </div>
                                                <div class="row pb-3 pl-xl-5 p-lg-4">
                                                    <div class="col-xl-7 col-lg-6 col-md-12  col-12">
                                                        <a class='no_link-s link-d'href="javascript:void(0)">
                                                            <h4 class=" title-d">
                                                                {{ $data->course->title }}
                                                            </h4>
                                                            <h5 class="fg-success-s">
                                                                {{ $data->title }}
                                                            </h5>
                                                        </a>
                                                    </div>
                                                    <div class="col-xl-4 col-lg-5 col-md-12 col-12 mt-xl-0 mt-lg-0 mt-md-3 mt-3 text-xl-right text-lg-right">
                                                        <span class="text_muted-s">
                                                            Quiz Type
                                                        </span>
                                                        <span class="ml-3 font-weight-bold  ">
                                                            {{ strtoupper($data->type) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="row pl-xl-5 pl-lg-4">
                                                    <div class="col-xl-11 col-lg-11 col-md-12 col-12 fg_dark-s">
                                                        <p>{{ $data->description }}</p>
                                                    </div>
                                                </div>
                                                <div class="row py-3">
                                                    <div class="col-xl-7 col-lg-7 col-md-12 col-sm-12 col-12 fg_dark-s mt-2 mb-2 d-flex justify-content-end  offset-lg-4">
                                                        <div class="ml-xl-5 pl-xl-5 ml-lg-5 pl-lg-5 ml-0 pl-0">
                                                            <span>
                                                                <img src="{{ asset('assets/images/student_quiz_clock.svg') }}" alt="clock">
                                                            </span>
                                                            <span class="pl-2 ">
                                                                <strong>{{ $data->duration_mins }}</strong> Minutes
                                                            </span>
                                                        </div>
                                                        <div class="ml-xl-5 ml-lg-5 ml-md-5 ml-5">
                                                            <span >
                                                                <img class="img_25_x_25-s" src="{{ asset('assets/images/student_quiz_calender.svg') }}" alt="calendar">
                                                            </span>
                                                            <span class="pl-2">
                                                                {{ \Carbon\Carbon::parse($data->created_at)->isoFormat('Do MMM YYYY')}}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--view modal body end-->
                                            <!-- Modal footer -->
                                            <div class="modal-footer border-0 mb-5 mt-xl-5 mt-lg-5 mt-sm-5 mt-3 justify-content-center">
                                                <button type="button" class="btn bg_success-s br_24-s py-2  text-white w_315px-s border border-white" >
                                                    START
                                                </button>
                                            </div>
                                            <!-- Modal footer End -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!--STart MCQs Test End-->
    @endforeach
    {{-- {{ dd($new_quiz_attempt) }} --}}


@endsection
