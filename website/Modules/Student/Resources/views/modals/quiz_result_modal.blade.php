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

                    <!--MODAL BODY-->
                    <div class="modal-body mb-5">
                        <div class="row pb-4">
                            <div class="col-12 text-center">
                                <h2 class="fg-success-s pb-5 modal_title-d">MCQs</h2>
                            </div>
                        </div>
                        <div class="row pb-3 px-xl-5">
                            <div class="col-xl-4 col-lg-6 col-md-12 col-12">
                                <a class='no_link-s link-d'href="javascript:void(0)">
                                    <h4 class=" title-d quiz_result_course_tilte-d">

                                    </h4>
                                    <h5 class="fg-success-s quiz_result_title-d">

                                    </h5>
                                </a>
                            </div>
                            <div class="col-xl-8 col-lg-6 col-md-12 col-12 mt-xl-0 mt-lg-0 mt-md-3 mt-3  text-xl-right text-lg-right">
                                <span class="text_muted-s quiz_type-d">
                                    Quiz Type
                                </span>
                                <span class="ml-3 font-weight-bold quiz_result_type-d">

                                </span>
                            </div>
                        </div>
                        <div class="row px-xl-5">
                            <div class="col-12 fg_dark-s">
                                <p class="text-wrap text-break quiz_result_description-d"></p>
                            </div>
                        </div>
                        <div class="row py-3 px-xl-5 pb-5">
                            <div class="col-xl-3 col-lg-3 col-md-6 col-12 mt-xl-0 mt-lg-0 mt-md-0 mt-3 fg-success-s">
                                <span>
                                    Total Mark: <strong class='students_count-d quiz_result_total_marks-d'></strong>
                                </span>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-12 mt-xl-0 mt-lg-0 mt-md-0 mt-3 fg-success-s">
                                <span>
                                    Obtained Mark:  <strong class='attempts_count-d quiz_result_obtained_marks-d'></strong>
                                </span>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-12 mt-xl-0 mt-lg-0 mt-md-3 mt-3 fg-success-s text-lg-center">
                                <span class="text-d quiz_result_status-d">
                                    Complete
                                </span>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-12 mt-xl-0 mt-lg-0 mt-md-3 mt-3 text-xl-right text-lg-right fg_dark-s">
                                <span >
                                    <img class="img_25_x_25-s" src="{{ asset('assets/images/student_quiz_calender.svg') }}" alt="calender">
                                </span>
                                <span class="pl-2 quiz_result_test_date-d">
                                    01 Feb 2021
                                </span>
                            </div>
                        </div>
                    </div>
                    <!--modal body end-->
                </div>
            </div>
        </div>
    </div>
</div>
<!--MCQs Result modal end-->
