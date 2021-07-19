@extends('teacher::layouts.teacher')

@section('page-title')
  Test Questions
@endsection


@section('content')
    <div class="container-fluid px-xl-5 px-lg-5 px-md-4 px-3 font_family_sans-serif-s">
        <!-- course basics - START -->
        <div class="row pt-5">
            <!--back button-->
            <div class="angle_left-s col-xl-1 col-lg-2 col-md-12 col-sm-12 col-12 text-left pr-0 ">
                <a href="{{ \Auth::user()->profile_type == 'student' ? route('course.view',$data->course->uuid ) : '' }}">
                    <img src="{{ asset('assets/images/angle_left.svg') }}" class="shadow p-3 bg-white rounded" width="60" height="60" alt="back" />
                </a>
            </div>
            <!--back button end-->

            <!--main head-->
            <div class=" col-xl-4 col-lg-6 col-md-7 col-sm-12 col-12 ">
                <div class="">
                    <h2 class='course_detail_title_heading-d'>{{ $data->title ?? '' }}</h2>
                </div>
            </div>
            @php
                $data->duration_mins = (int)$data->duration_mins;
                // echo "+{$data->duration_mins} minutes";
                // $duration = date('M d, Y H:i:s', strtotime("+{$item->duration_mins} minutes"));
                $duration = date('M d, Y H:i:s', strtotime("+5 hour +{$data->duration_mins} minute"));
                $now_date = date('M d, Y H:i:s');
                $now = date('M d, Y H:i:s', strtotime("+5 hour", strtotime($now_date)));
            @endphp
            <div class="col-xl-7 col-lg-4 col-md-5 col-12 d-flex justify-content-end mt-2">
                <input type="hidden" name="" id="duration-d" value="{{ $duration }}">
                <input type="hidden" name="" id="date_now-d" value="{{ $now }}">
                <div class="border text-white size_55_x_50-s fs_30px-s bg_success-s text-center pt-xl-1 pt-lg-1 pt-md-2 pt-2 px-2 ">
                    <p id="hrs"></p>
                </div>
                <span class="pt-xl-1 pt-lg-1 pt-md-2 pt-2 px-2 fs_30px-s fg-success-s">
                    <strong>:</strong>
                </span>
                <div class="border text-white size_55_x_50-s fs_30px-s bg_success-s text-center pt-xl-1 pt-lg-1 pt-md-2 pt-2  px-2 ">
                    <p id="mins"></p>
                </div>
                <span class="pt-xl-1 pt-lg-1 pt-md-2 pt-2 px-2 fs_30px-s fg-success-s">
                    <strong>:</strong>
                </span>
                <div class="border text-white size_55_x_50-s fs_30px-s bg_success-s text-center pt-xl-1 pt-lg-1 pt-md-2 pt-2  px-2 ">
                    <p id="seconds"></p>
                </div>
            </div>
            <div class="col-xl-10 col-lg-10 col-md-12 col-sm-12 col-12 fs_19px-s text-large offset-xl-1 offset-lg-2 mt-xl-0 mt-lg-0 mt-md-1 mt-3">
                <p>{{ $data->description ?? '' }}</p>
            </div>
            <!--main head end-->
        </div>
        <!--course basics - END -->

        <!-- test top heading - START -->
        <div class="row">
            <div class="col-xl-11 col-lg-10 col-md-12 col-sm-12 col-12 offset-xl-1 offset-lg-2">
                <!-- multiple choice top heading - START -->
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-12 mt-5">
                        <h4><strong>Total Question : {{ (count($data_questions) ?? '0') }}</strong></h4>
                    </div>
                </div>
            </div>
        </div>
        <!-- test top heading - END -->
        <form action="{{ route('quiz.submitQuiz', [$data->uuid]) }}" id="frm_student_test-d" method="POST">
            <!-- test Questions - START -->
            @forelse ($data_questions as $item)
                <div class="row mt-3">
                    <div class="col-xl-1 col-lg-2 col-md-3 col-4 ">
                        <span>Question:{{ $loop->iteration }}</span><br>
                    </div>
                    <div class="col-xl-11 col-lg-10 col-md-9 col-8  ">
                        <p>{{ $item->body ?? '' }}</p>
                    </div>

                    <div class="col-xl-11 col-lg-10 col-md-9 col-8  mt-3 offset-xl-1 offset-lg-2 offset-4">
                        <textarea class="w_100-s textarea_h_70px-s br_10px-s br_color_grey-s pt-1 pl-2 ans_body-d" name="question_{{ $item->uuid ?? '' }}" placeholder="Your Answer goes in here"></textarea>
                    </div>
                </div>
            @empty
            @endforelse
            <!-- test Questions - END -->

            <div class="row ">
                <div class="col-12 text-center pb-4 pt-3">
                    <input type="hidden" name="quiz_uuid"  value="{{ $data->uuid ?? '' }}" />
                    <input type="hidden" name="course_uuid" value="{{ $data->course->uuid }}" />
                    <input type="hidden" name="is_time_out" class='is_time_out-d' value="0" />
                    {{-- <a href="javascript:void(0)" class="btn bg_success-s text-white br_21px-s py-2 w_30-s" id="test_quiz_submit-d">Submit</a> --}}
                    <button type="button" class="btn bg_success-s text-white br_21px-s py-2 w_30-s submit_student_test_form-d">Submit</a>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('footer-scripts')
    <script>

    </script>
    <script src="{{ asset('assets/js/start_time_quiz.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/student.js') }}"></script> --}}
@endsection
