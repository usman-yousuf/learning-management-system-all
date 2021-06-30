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
            <a href="">
                <img src="{{ asset('assets/images/angle_left.svg') }}" class="shadow p-3 mb-5 bg-white rounded" width="60" height="60" alt="back" />
            </a>
        </div>
        <!--back button end-->

        <!--main head-->
        <div class=" col-xl-4 col-lg-6 col-md-7 col-sm-12 col-12 ">
            <div class="">
                <h2 class='course_detail_title_heading-d'>{{ $data->title ?? '' }}</h2>
            </div>
        </div>
        <div class="col-xl-7 col-lg-4 col-md-5 col-12 d-flex justify-content-end mt-2">
            <div class="border text-white size_55_x_50-s fs_30px-s bg_success-s text-center pt-xl-1 pt-lg-1 pt-md-2 pt-2 px-2 ">
                00
            </div>
            <span class="pt-xl-1 pt-lg-1 pt-md-2 pt-2 px-2 fs_30px-s fg-success-s">
                <strong>:</strong>
            </span>
            <div class="border text-white size_55_x_50-s fs_30px-s bg_success-s text-center pt-xl-1 pt-lg-1 pt-md-2 pt-2  px-2 ">
                00
            </div>
            <span class="pt-xl-1 pt-lg-1 pt-md-2 pt-2 px-2 fs_30px-s fg-success-s">
                <strong>:</strong>
            </span>
            <div class="border text-white size_55_x_50-s fs_30px-s bg_success-s text-center pt-xl-1 pt-lg-1 pt-md-2 pt-2  px-2 ">
                00
            </div>
        </div>    
        <div class="col-xl-10 col-lg-10 col-md-12 col-sm-12 col-12 fs_19px-s text-large offset-xl-1 offset-lg-2 mt-xl-0 mt-lg-0 mt-md-1 mt-3">
            <p>{{ $data->description ?? '' }}</p>
        </div>
        <!--main head end-->
    </div>
    <!--course basics - end -->

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
    <!-- multiple choice top heading - END -->

    <!-- Multiple Choice Questions - START -->
    <!-- question -1 - start -->
    <div class="row mt-3">
        <div class="col-xl-1 col-lg-2 col-md-3 col-3 ">
            <span>Question:1</span><br>
        </div>
        <div class="col-xl-11 col-lg-10 col-md-9 col-9   ">
            <p>Lorem Ipsum is simply dummy text of the printing and has been the industry's standard dummy text ever since the 1500s?</p>
        </div>

        <div class="col-xl-11 col-lg-10 col-md-9 col-9  mt-3 offset-xl-1 offset-lg-2 offset-3">
            <textarea class="w_100-s textarea_h_70px-s br_10px-s br_color_grey-s bg_grey-s fg_grey-s pt-1 pl-2" id="test_quiz_ans-d" name="test_quiz_ans">They offer free tutorials in all web development technologies.
            </textarea>
        </div>
    </div>
    <!-- question -1 - end -->

    <!-- question -2 - start -->
    <div class="row mt-3">
        <div class="col-xl-1 col-lg-2 col-md-3 col-3 ">
            <span>Question:2</span><br>
        </div>
        <div class="col-xl-11 col-lg-10 col-md-9 col-9  text-left ">
            <p>Lorem Ipsum is simply dummy text of the printing and has been the industry's standard dummy text ever since the 1500s?</p>
        </div>
        <div class="col-xl-11 col-lg-10 col-md-9 col-9  mt-3 offset-xl-1 offset-lg-2 offset-3">
            <textarea class="w_100-s textarea_h_70px-s br_10px-s br_color_grey-s bg_grey-s fg_grey-s pt-1 pl-2" id="test_quiz_ans-d" name="test_quiz_ans">They offer free tutorials in all web development technologies.
            </textarea>
        </div>
    </div>
    <!-- question -2 - end -->

    <!-- question -3 - start -->
    <div class="row mt-3">
        <div class="col-xl-1 col-lg-2 col-md-3 col-3 ">
            <span>Question:3</span><br>
        </div>
        <div class="col-xl-11 col-lg-10 col-md-9 col-9  text-left ">
            <p>Lorem Ipsum is simply dummy text of the printing and has been the industry's standard dummy text ever since the 1500s?</p>
        </div>
        <div class="col-xl-11 col-lg-10 col-md-9 col-9 mt-3  offset-xl-1 offset-lg-2 offset-3">
            <textarea class="w_100-s textarea_h_70px-s br_10px-s br_color_grey-s bg_grey-s fg_grey-s pt-1 pl-2" id="test_quiz_ans-d" name="test_quiz_ans">They offer free tutorials in all web development technologies.
            </textarea>
        </div>
    </div>
    <!-- question -3 - end -->
    <!-- Multiple Choice Questions - END -->
  </div>
@endsection