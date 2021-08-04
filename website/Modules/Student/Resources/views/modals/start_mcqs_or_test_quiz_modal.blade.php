
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

                            <!--MODAL BODY-->
                            <div class="modal-body ">
                                <div class="row pb-4">
                                    <div class="col-12 text-center">
                                        <h2 class="fg-success-s pb-5 quiz_type-d">
                                            {{-- MCQs --}}
                                        </h2>
                                    </div>
                                </div>
                                <div class="row pb-3 px-xl-5">
                                    <div class="col-xl-4 col-lg-6 col-md-12  col-12">
                                        <a class='no_link-s link-d'href="javascript:void(0)">
                                            <h4 class=" title-d quiz_course_title-d">
                                                {{-- Graphic Designing --}}
                                            </h4>
                                            <h5 class="fg-success-s quiz_title-d">
                                                {{-- Logo Designing --}}
                                            </h5>
                                        </a>
                                    </div>
                                    <div class="col-xl-8 col-lg-6 col-md-12 col-12 mt-1 text-xl-right text-lg-right">
                                        <span class="text_muted-s">
                                            Quiz Type
                                        </span>
                                        <span class="ml-3 font-weight-bold quiz_type-d ">
                                            {{-- MCQs   --}}
                                        </span>
                                    </div>
                                </div>
                                <div class="row px-xl-5">
                                    <div class="col-12 fg_dark-s">
                                        <p class="quiz_description-d">
                                            {{-- Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s? --}}
                                        </p>
                                    </div>
                                </div>
                                <div class="row py-3 px-xl-5">
                                    <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12 fg_dark-s mt-2 mb-2 d-flex justify-content-lg-end offset-xl-4">
                                        <div class="">
                                            <span>
                                                <img src="{{ asset('assets/images/student_quiz_clock.svg') }}" alt="clock">
                                            </span>
                                            <span class="pl-2 ">
                                                <strong class="quiz_duration-d">
                                                    {{-- 30 --}}
                                                </strong> Minutes
                                            </span>
                                        </div>
                                        <div class=" ml-5">
                                            <span >
                                                <img class="img_25_x_25-s" src="{{ asset('assets/images/student_quiz_calender.svg') }}" alt="calendar">
                                            </span>
                                            <span class="pl-2 quiz_due_date-d">
                                                {{-- 01 Feb 2021 --}}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--view modal body end-->
                            <!-- Modal footer -->
                            <div class="modal-footer border-0 mb-5 mt-xl-5 mt-lg-5 mt-sm-5 mt-3 justify-content-center">
                                <strong class="text-center w-100 mb-3 expired_quiz_text-d d-none">Quiz Date has Passed. You cannot attempt this quiz anymore.</strong>
                                <button type="button" class="btn bg_success-s br_24-s py-2 text-white w_315px-s border border-white">
                                  <a class="w-100 btn_view_quiz_link-d fg_white-s no_link-s">START</a>

                                </button>
                            </div>
                            <!-- Modal footer End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--STart MCQs Test End-->
