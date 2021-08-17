@extends('student::layouts.student_course_view_layout')

@section('student_course_content')

    <!--Course Outline-->
    <div class="main_work_container-d" id="outline_main_container-d">
        <div class="cloneables_container-d">
            <!--course outline 1-->
            <div class="row single_outline_container-d align-items-center pb-4 pt-5 fs_19px-s" id='cloneable_outline-d'>
                <div class="col-xl-11 col-lg-11 col-md-12 col-sm-12 col-12 offset-xl-1 offset-lg-1">
                    <div class="row align-items-center">
                        <div class="col-1 outline_serial-d">01</div>
                        <div class="col-xl-8 col-lg-8 col-md-8 col-sm-7 col-7 text-wrap text-break outline_title-d">Make to gif file in Photoshop………………………………………………………………………………………………………………………………………………………………………………………………………</div>
                        <div class="col-3 outline_duration-d">04:49 Hrs</div>
                    </div>
                </div>
            </div>
            <!--course outline 1 end-->
        </div>
    </div>
    <!--End Course Outline-->

    <!--Course Video -->
    <div class="main_work_container-d d-none" id="video_content_main_container-d">
        <div class="row">
            <!--card 1-->
            <div class="col-xl-3 col-md-6 col-12 mb-4">
                <div class="card custom_card-s mt-4 br_19px-s">
                    <img class="img-fluid mx-auto br_top_19px-s" alt="course-image" src="{{ asset('assets/images/card2.png') }}">
                    <!-- ------card content---- -->
                    <div class="container card_design_text-s">
                        <div class="row pt-3">
                            <div class="col-12">
                                <h6><a href="javascript:void(0)" class='no_link-s'>Game UI Designing</a></h6>
                            </div>
                        </div>
                        <div class="row pt-2 pb-2">
                            <div class="col-12">
                                <span>landing Page Design</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="d-flex justify-content-center mt-3 mb-3">
                                    <a href="javascript:void(0)" class="btn btn-primary br_21px-s w-100 mx-3" data-toggle="modal" data-target="#video_modal-d">View</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ------card content End---- -->
                </div>
            </div>
            <!--card 1 end-->
        </div>
        @include('student::modals.video_course_modal');
    </div>
    <!--End Course Video -->

    <!--handout content card-->
    <div class="main_work_container-d d-none" id="handout_main_container-d">
        <div class="row">
            <div class="col-xl-3 col-md-6 col-12 mb-4">
                <div class="card custom_card-s mt-4 br_19px-s">
                    <img class="img-fluid mx-auto br_top_19px-s" alt="course-image" src="{{ asset('assets/images/card2.png') }}">
                    <!-- ------card content---- -->
                    <!-- <div class="d-flex mt-3 card_design_text-s"> -->
                        <div class="container card_design_text-s">
                            <div class="row pt-3">
                                <div class="col-12">
                                    <h6><a href="javascript:void(0)" class='no_link-s'>Game UI Designing</a></h6>
                                </div>
                            </div>
                            <div class="row pt-2 pb-2">
                                <div class="col-12">
                                    <span>landing Page Design</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="d-flex justify-content-between text-align-center mt-3 mb-3">
                                        <a href="javascript:void(0)" class="btn btn-primary br_21px-s mr-1 px-xl-4 px-lg-4 px-md-3 px-4" data-toggle="modal" data-target="#viewdocoment">View</a>
                                        {{-- <a href="javascript:void(0)" class="btn  courses_delete_btn-s br_21px-s">Download</a> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!-- </div> -->
                    <!-- ------card content End---- -->
                </div>
            </div>
        </div>
        @include('student::modals.handout_modal')
    </div>
    <!--handout content card end-->


    <!--Course Quiz -->
    <div class="main_work_container-d d-none" id="student_main_container-d">
        <!--quiz list-->
        <div class="row pt-5 pb-4 quiz_main_container-d course_details_container-d">
            @forelse ($data->quizzes as $item)
                <div class="col-12 my-2 bg_white-s br_10px-s single_quiz_container-d shadow">
                    <div class="row py-3 px-xl-5">
                        <div class="col-xl-4 col-lg-6 col-md-12 col-12">
                            <a class='no_link-s link-d'href="javascript:void(0)">
                                <h4 class=" title-d">
                                    {{ $item->title }}
                                </h4>
                                <h5 class="fg-success-s">
                                    {{ $item->course->title }}
                                </h5>
                            </a>
                        </div>
                        <div class="col-xl-8 col-lg-6 col-md-12 col-12 mt-1 text-xl-right text-lg-right ">
                            <span class="text_muted-s">
                                Quiz Type
                            </span>
                            <span class="ml-3 font-weight-bold  ">
                                {{ $item->type }}
                            </span>
                        </div>
                    </div>
                        <div class="row px-xl-5">
                            <div class="col-12 fg_dark-s">
                                <p>{{ $item->description }}</p>
                            </div>
                        </div>
                    <div class="row py-3 px-xl-5 flex-column-reverse flex-lg-row">
                        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 fg_dark-s mt-xl-0 mt-lg-0 mt-3 mb-xl-0 mb-lg-0 mb-3">
                            <a href="" class="btn bg_success-s text-white w-50 br_21px-s" data-toggle="modal" data-target="#conformation_modal-d-{{ $item->uuid }}">
                                Start
                            </a>
                        </div>
                        <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12 fg_dark-s mt-2 mb-2 d-flex justify-content-lg-end ">
                            <div>
                                <span>
                                    <img src="{{ asset('assets/images/student_quiz_clock.svg') }}" alt="clock">
                                </span>
                                <span class="pl-2 ">
                                    <strong>{{ $item->duration_mins }}</strong> Minutes
                                </span>
                            </div>
                            <div class="ml-5">
                                <span >
                                    <img  src="{{ asset('assets/images/student_quiz_calender.svg') }}" alt="clock">
                                </span>
                                <span class="pl-2">
                                    {{ $item->modal_due_date }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            @include('student::modals.quiz_confirmation_modal')
            @empty

            @endforelse
        </div>
        <!--quiz list end-->
    </div>
    <!--End Course Quiz -->


    <!--Course Reviews  -->
    <div class="main_work_container-d d-none" id="reviews_main_container-d">
        <!--scroll buttons-->
        <div class="row pt-5">
            <div class="col-12 text-right">
                <img src="{{ asset('assets/images/left_scroll.svg') }}" alt="left scroll button">
                <img src="{{ asset('assets/images/right_scroll.svg') }}" alt="left scroll button">
            </div>
        </div>
        <!--scroll buttons end-->

        <!--Reviews Card-->
        <section class="pt-4 pb-5">
            <div class="row ">
                <!-- For LARGE SCREEN - START -->
                <div class="col-12 d-none d-lg-block">
                    <div id="carouselExampleIndicators2" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active ">
                                <div class="row">
                                    <!-- card-1 -->
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 pt-3 pb-3 mt-5 student_review_single_container-d">
                                        <div class="container ">
                                            <div class="row ">
                                                <div class="col-12 px-0 pr-xl-3">
                                                    <div class="card body shadow align-items-center br_10px-s ">
                                                        <img class="card-img-top rounded-circle review_img-s img_120x120-s "src="{{ asset('assets/images/student1.png') }}" alt="student img" />
                                                        <div class="card-block text-center mt-3 ml-3 mr-3  mb-3">
                                                            <h5 class="text-success student_name-d">James</h5>
                                                            <span class="">Reviewed At: <strong class='review_date-d'>01 Feb 2020</strong></span>
                                                            <div class="mt-3">
                                                                <img class="star_img-s" src="{{ asset('assets/images/yellow_star.svg') }}" alt="star img">
                                                                <img class="star_img-s"  src="{{ asset('assets/images/yellow_star.svg') }}" alt="star img">
                                                                <img class="star_img-s"  src="{{ asset('assets/images/yellow_star.svg') }}" alt="star img">
                                                                <img class="star_img-s"  src="{{ asset('assets/images/yellow_star.svg') }}" alt="star img">
                                                                <img class="star_img-s"  src="{{ asset('assets/images/yellow_star.svg') }}" alt="star img">
                                                                <span class="rating_star-d">3.5</span>
                                                            </div>
                                                            <div class="mt-3">
                                                                <p >Lorem Ipsum is simply dummy text of the printing and type setting industry. Lorem Ipsum has been the industry's</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- card 1 End -->
                                    <!--card 2 -->
                                    <!-- <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 pt-3 pb-3 mt-5 student_review_single_container-d">
                                        <div class="container ">
                                            <div class="row ">
                                                <div class="col-12 px-0 pr-xl-3">
                                                    <div class="card body shadow align-items-center br_10px-s ">
                                                        <img class="card-img-top rounded-circle review_img-s img_120x120-s "src="../assets/preview/student1.png" alt="student img" />
                                                        <div class="card-block text-center mt-3 ml-3 mr-3  mb-3">
                                                            <h5 class="text-success student_name-d">James</h5>
                                                            <span class="">Reviewed At: <strong class='review_date-d'>01 Feb 2020</strong></span>
                                                            <div class="mt-3">
                                                                <img class="star_img-s" src="../assets/preview/yellow_star.svg" alt="star img">
                                                                <img class="star_img-s"  src="../assets/preview/yellow_star.svg" alt="star img">
                                                                <img class="star_img-s"  src="../assets/preview/yellow_star.svg" alt="star img">
                                                                <img class="star_img-s"  src="../assets/preview/half_star.svg" alt="star img">
                                                                <img class="star_img-s"  src="../assets/preview/grey_star.svg" alt="star img">
                                                                <span class="rating_star-d">3.5</span>
                                                            </div>
                                                            <div class="mt-3">
                                                                <p >Lorem Ipsum is simply dummy text of the printing and type setting industry. Lorem Ipsum has been the industry's</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
                                    <!--card 2 end-->
                                    <!--card 3-->
                                    <!-- <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 pt-3 pb-3 mt-5 student_review_single_container-d">
                                        <div class="container ">
                                            <div class="row ">
                                                <div class="col-12 px-0 pr-xl-3">
                                                    <div class="card body shadow align-items-center br_10px-s ">
                                                        <img class="card-img-top rounded-circle review_img-s img_120x120-s "src="../assets/preview/student1.png" alt="student img" />
                                                        <div class="card-block text-center mt-3 ml-3 mr-3  mb-3">
                                                            <h5 class="text-success student_name-d">James</h5>
                                                            <span class="">Reviewed At: <strong class='review_date-d'>01 Feb 2020</strong></span>
                                                            <div class="mt-3">
                                                                <img class="star_img-s" src="../assets/preview/yellow_star.svg" alt="star img">
                                                                <img class="star_img-s"  src="../assets/preview/yellow_star.svg" alt="star img">
                                                                <img class="star_img-s"  src="../assets/preview/yellow_star.svg" alt="star img">
                                                                <img class="star_img-s"  src="../assets/preview/half_star.svg" alt="star img">
                                                                <img class="star_img-s"  src="../assets/preview/grey_star.svg" alt="star img">
                                                                <span class="rating_star-d">3.5</span>
                                                            </div>
                                                            <div class="mt-3">
                                                                <p >Lorem Ipsum is simply dummy text of the printing and type setting industry. Lorem Ipsum has been the industry's</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
                                    <!--card 3 end-->
                                </div>
                            </div>

                            <div class="carousel-item  ">
                                <div class="row">
                                    <!-- card-1 -->
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 pt-3 pb-3 mt-5 student_review_single_container-d">
                                        <div class="container ">
                                            <div class="row ">
                                                <div class="col-12 px-0 pr-xl-3">
                                                    <div class="card body shadow align-items-center br_10px-s ">
                                                        <img class="card-img-top rounded-circle review_img-s img_120x120-s "src="../assets/preview/student1.png" alt="student img" />
                                                        <div class="card-block text-center mt-3 ml-3 mr-3  mb-3">
                                                            <h5 class="text-success student_name-d">James</h5>
                                                            <span class="">Reviewed At: <strong class='review_date-d'>01 Feb 2020</strong></span>
                                                            <div class="mt-3">
                                                                <img class="star_img-s" src="../assets/preview/yellow_star.svg" alt="star img">
                                                                <img class="star_img-s"  src="../assets/preview/yellow_star.svg" alt="star img">
                                                                <img class="star_img-s"  src="../assets/preview/yellow_star.svg" alt="star img">
                                                                <img class="star_img-s"  src="../assets/preview/half_star.svg" alt="star img">
                                                                <img class="star_img-s"  src="../assets/preview/grey_star.svg" alt="star img">
                                                                <span class="rating_star-d">3.5</span>
                                                            </div>
                                                            <div class="mt-3">
                                                                <p >Lorem Ipsum is simply dummy text of the printing and type setting industry. Lorem Ipsum has been the industry's</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- card 1 End -->
                                    <!--card 2 -->
                                    {{-- <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 pt-3 pb-3 mt-5 student_review_single_container-d">
                                        <div class="container ">
                                            <div class="row ">
                                                <div class="col-12 px-0 pr-xl-3">
                                                    <div class="card body shadow align-items-center br_10px-s ">
                                                        <img class="card-img-top rounded-circle review_img-s img_120x120-s "src="../assets/preview/student1.png" alt="student img" />
                                                        <div class="card-block text-center mt-3 ml-3 mr-3  mb-3">
                                                            <h5 class="text-success student_name-d">James</h5>
                                                            <span class="">Reviewed At: <strong class='review_date-d'>01 Feb 2020</strong></span>
                                                            <div class="mt-3">
                                                                <img class="star_img-s" src="../assets/preview/yellow_star.svg" alt="star img">
                                                                <img class="star_img-s"  src="../assets/preview/yellow_star.svg" alt="star img">
                                                                <img class="star_img-s"  src="../assets/preview/yellow_star.svg" alt="star img">
                                                                <img class="star_img-s"  src="../assets/preview/half_star.svg" alt="star img">
                                                                <img class="star_img-s"  src="../assets/preview/grey_star.svg" alt="star img">
                                                                <span class="rating_star-d">3.5</span>
                                                            </div>
                                                            <div class="mt-3">
                                                                <p >Lorem Ipsum is simply dummy text of the printing and type setting industry. Lorem Ipsum has been the industry's</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                    <!--card 2 end-->
                                    <!--card 3-->
                                    {{-- <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 pt-3 pb-3 mt-5 student_review_single_container-d">
                                        <div class="container ">
                                            <div class="row ">
                                                <div class="col-12 px-0 pr-xl-3">
                                                    <div class="card body shadow align-items-center br_10px-s ">
                                                        <img class="card-img-top rounded-circle review_img-s img_120x120-s "src="../assets/preview/student1.png" alt="student img" />
                                                        <div class="card-block text-center mt-3 ml-3 mr-3  mb-3">
                                                            <h5 class="text-success student_name-d">James</h5>
                                                            <span class="">Reviewed At: <strong class='review_date-d'>01 Feb 2020</strong></span>
                                                            <div class="mt-3">
                                                                <img class="star_img-s" src="../assets/preview/yellow_star.svg" alt="star img">
                                                                <img class="star_img-s"  src="../assets/preview/yellow_star.svg" alt="star img">
                                                                <img class="star_img-s"  src="../assets/preview/yellow_star.svg" alt="star img">
                                                                <img class="star_img-s"  src="../assets/preview/half_star.svg" alt="star img">
                                                                <img class="star_img-s"  src="../assets/preview/grey_star.svg" alt="star img">
                                                                <span class="rating_star-d">3.5</span>
                                                            </div>
                                                            <div class="mt-3">
                                                                <p >Lorem Ipsum is simply dummy text of the printing and type setting industry. Lorem Ipsum has been the industry's</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                    <!--card 3 end-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- For LARGE SCREEN - END -->

                <!-- FOR MEDIUM SCREEN - START -->
                <div class="col-12 d-none d-sm-block d-lg-none">
                    <div id="carouselExampleIndicators2" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <div class="row">
                                    <!-- carousal item - show 3 at a time -->
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 pt-3 pb-3 mt-5 student_review_single_container-d">
                                        <div class="container ">
                                            <div class="row ">
                                                <div class="col-12 px-0 pr-xl-3">
                                                    <div class="card body shadow align-items-center br_10px-s ">
                                                        <img class="card-img-top rounded-circle review_img-s img_120x120-s "src="../assets/preview/student1.png" alt="student img" />
                                                        <div class="card-block text-center mt-3 ml-3 mr-3  mb-3">
                                                            <h5 class="text-success student_name-d">James</h5>
                                                            <span class="">Reviewed At: <strong class='review_date-d'>01 Feb 2020</strong></span>
                                                            <div class="mt-3">
                                                                <img class="star_img-s" src="../assets/preview/yellow_star.svg" alt="star img">
                                                                <img class="star_img-s"  src="../assets/preview/yellow_star.svg" alt="star img">
                                                                <img class="star_img-s"  src="../assets/preview/yellow_star.svg" alt="star img">
                                                                <img class="star_img-s"  src="../assets/preview/half_star.svg" alt="star img">
                                                                <img class="star_img-s"  src="../assets/preview/grey_star.svg" alt="star img">
                                                                <span class="rating_star-d">3.5</span>
                                                            </div>
                                                            <div class="mt-3">
                                                                <p >Lorem Ipsum is simply dummy text of the printing and type setting industry. Lorem Ipsum has been the industry's</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- carousal item End -->
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 pt-3 pb-3 mt-5 student_review_single_container-d">
                                        <div class="container ">
                                            <div class="row ">
                                                <div class="col-12 px-0 pr-xl-3">
                                                    <div class="card body shadow align-items-center br_10px-s ">
                                                        <img class="card-img-top rounded-circle review_img-s img_120x120-s "src="../assets/preview/student1.png" alt="student img" />
                                                        <div class="card-block text-center mt-3 ml-3 mr-3  mb-3">
                                                            <h5 class="text-success student_name-d">James</h5>
                                                            <span class="">Reviewed At: <strong class='review_date-d'>01 Feb 2020</strong></span>
                                                            <div class="mt-3">
                                                                <img class="star_img-s" src="../assets/preview/yellow_star.svg" alt="star img">
                                                                <img class="star_img-s"  src="../assets/preview/yellow_star.svg" alt="star img">
                                                                <img class="star_img-s"  src="../assets/preview/yellow_star.svg" alt="star img">
                                                                <img class="star_img-s"  src="../assets/preview/half_star.svg" alt="star img">
                                                                <img class="star_img-s"  src="../assets/preview/grey_star.svg" alt="star img">
                                                                <span class="rating_star-d">3.5</span>
                                                            </div>
                                                            <div class="mt-3">
                                                                <p >Lorem Ipsum is simply dummy text of the printing and type setting industry. Lorem Ipsum has been the industry's</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="row">
                                <!-- carousal item - show 3 at a time -->
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 pt-3 pb-3 mt-5 student_review_single_container-d">
                                    <div class="container ">
                                        <div class="row ">
                                            <div class="col-12 px-0 pr-xl-3">
                                                <div class="card body shadow align-items-center br_10px-s ">
                                                    <img class="card-img-top rounded-circle review_img-s img_120x120-s "src="../assets/preview/student1.png" alt="student img" />
                                                    <div class="card-block text-center mt-3 ml-3 mr-3  mb-3">
                                                        <h5 class="text-success student_name-d">James</h5>
                                                        <span class="">Reviewed At: <strong class='review_date-d'>01 Feb 2020</strong></span>
                                                        <div class="mt-3">
                                                            <img class="star_img-s" src="../assets/preview/yellow_star.svg" alt="star img">
                                                            <img class="star_img-s"  src="../assets/preview/yellow_star.svg" alt="star img">
                                                            <img class="star_img-s"  src="../assets/preview/yellow_star.svg" alt="star img">
                                                            <img class="star_img-s"  src="../assets/preview/half_star.svg" alt="star img">
                                                            <img class="star_img-s"  src="../assets/preview/grey_star.svg" alt="star img">
                                                            <span class="rating_star-d">3.5</span>
                                                        </div>
                                                        <div class="mt-3">
                                                            <p >Lorem Ipsum is simply dummy text of the printing and type setting industry. Lorem Ipsum has been the industry's</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- carousal item End -->
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 pt-3 pb-3 mt-5 student_review_single_container-d">
                                    <div class="container ">
                                        <div class="row ">
                                            <div class="col-12 px-0 pr-xl-3">
                                                <div class="card body shadow align-items-center br_10px-s ">
                                                    <img class="card-img-top rounded-circle review_img-s img_120x120-s "src="../assets/preview/student1.png" alt="student img" />
                                                    <div class="card-block text-center mt-3 ml-3 mr-3  mb-3">
                                                        <h5 class="text-success student_name-d">James</h5>
                                                        <span class="">Reviewed At: <strong class='review_date-d'>01 Feb 2020</strong></span>
                                                        <div class="mt-3">
                                                            <img class="star_img-s" src="../assets/preview/yellow_star.svg" alt="star img">
                                                            <img class="star_img-s"  src="../assets/preview/yellow_star.svg" alt="star img">
                                                            <img class="star_img-s"  src="../assets/preview/yellow_star.svg" alt="star img">
                                                            <img class="star_img-s"  src="../assets/preview/half_star.svg" alt="star img">
                                                            <img class="star_img-s"  src="../assets/preview/grey_star.svg" alt="star img">
                                                            <span class="rating_star-d">3.5</span>
                                                        </div>
                                                        <div class="mt-3">
                                                            <p >Lorem Ipsum is simply dummy text of the printing and type setting industry. Lorem Ipsum has been the industry's</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- FOR MEDIUM SCREEN - END -->

                <!--FOR SMALL SCREEN- START-->
                <div class="col-12 d-block d-sm-none">
                    <div id="carouselExampleIndicators2" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <div class="row">
                                    <!-- carousal item - show 3 at a time -->
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 pt-3 pb-3 mt-5 student_review_single_container-d">
                                        <div class="container ">
                                            <div class="row ">
                                                <div class="col-12 px-0 pr-xl-3">
                                                    <div class="card body shadow align-items-center br_10px-s ">
                                                        <img class="card-img-top rounded-circle review_img-s img_120x120-s "src="../assets/preview/student1.png" alt="student img" />
                                                        <div class="card-block text-center mt-3 ml-3 mr-3  mb-3">
                                                            <h5 class="text-success student_name-d">James</h5>
                                                            <span class="">Reviewed At: <strong class='review_date-d'>01 Feb 2020</strong></span>
                                                            <div class="mt-3">
                                                                <img class="star_img-s" src="../assets/preview/yellow_star.svg" alt="star img">
                                                                <img class="star_img-s"  src="../assets/preview/yellow_star.svg" alt="star img">
                                                                <img class="star_img-s"  src="../assets/preview/yellow_star.svg" alt="star img">
                                                                <img class="star_img-s"  src="../assets/preview/half_star.svg" alt="star img">
                                                                <img class="star_img-s"  src="../assets/preview/grey_star.svg" alt="star img">
                                                                <span class="rating_star-d">3.5</span>
                                                            </div>
                                                            <div class="mt-3">
                                                                <p >Lorem Ipsum is simply dummy text of the printing and type setting industry. Lorem Ipsum has been the industry's</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- carousal item End -->
                                </div>
                            </div>
                            <div class="carousel-item ">
                                <div class="row">
                                    <!-- carousal item - show 3 at a time -->
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 pt-3 pb-3 mt-5 student_review_single_container-d">
                                        <div class="container ">
                                            <div class="row ">
                                                <div class="col-12 px-0 pr-xl-3">
                                                    <div class="card body shadow align-items-center br_10px-s ">
                                                        <img class="card-img-top rounded-circle review_img-s img_120x120-s "src="../assets/preview/student1.png" alt="student img" />
                                                        <div class="card-block text-center mt-3 ml-3 mr-3  mb-3">
                                                            <h5 class="text-success student_name-d">James</h5>
                                                            <span class="">Reviewed At: <strong class='review_date-d'>01 Feb 2020</strong></span>
                                                            <div class="mt-3">
                                                                <img class="star_img-s" src="../assets/preview/yellow_star.svg" alt="star img">
                                                                <img class="star_img-s"  src="../assets/preview/yellow_star.svg" alt="star img">
                                                                <img class="star_img-s"  src="../assets/preview/yellow_star.svg" alt="star img">
                                                                <img class="star_img-s"  src="../assets/preview/half_star.svg" alt="star img">
                                                                <img class="star_img-s"  src="../assets/preview/grey_star.svg" alt="star img">
                                                                <span class="rating_star-d">3.5</span>
                                                            </div>
                                                            <div class="mt-3">
                                                                <p >Lorem Ipsum is simply dummy text of the printing and type setting industry. Lorem Ipsum has been the industry's</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- carousal item End -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--FOR SMALL SCREEN - END-->

            </div>
        </section>
        <!--review card end-->
        <!--add comment button-->
        <div class="row pb-5 pt-4">
            <div class="col-12 text-center">
                <a href="" class="btn btn text-white px-5 py-2 add_course_btn-s" data-toggle="modal" data-target="#add_comment-d">
                Add Your Comment
                </a>
            </div>
        </div>
        <!--add comment button  end-->
        @include('student::modals.add_comment_modal');
    </div>
    <!--End Course Reviews -->



    @include('student::modals.add_question_modal');
@endsection

@section('footer-scripts')
        <script>
            // student_course_detail_page
            let Start_Quiz_Page = "{{ route('student.courseDetail') }}";
            let Student_Course_Detail_Page = "{{ route('student.courseDetail') }}";
        </script>
    <script src="{{ asset('assets/js/student.js') }}"></script>
    <script src="{{ asset('assets/js/manage_student_courses.js') }}"></script>
@endsection
