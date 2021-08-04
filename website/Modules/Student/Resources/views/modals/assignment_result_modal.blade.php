<!--assignment result modal-->
<div class="modal fade" id="assignment_result-d" tabindex="-1"  aria-labelledby="view-head" aria-hidden="true">
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

                    <!-- MODAL BODY-->
                    <div class="modal-body">
                        <div class="row pb-4 ">
                            <div class="col-12 text-center">
                                <h2 class="fg-success-s pb-5">Assignment</h2>
                            </div>
                        </div>
                        <div class="row pb-3 px-xl-5">
                            <div class="col-12 offset-xl-3 offset-lg-2">
                                <h4 class=" title-d assignment_title-d assignment_title-t">

                                </h4>
                                <div class="mt-3">
                                    <span >
                                        <img class="img_25_x_25-s" src="{{ asset('assets/images/student_quiz_calender.svg') }}" alt="calendar">
                                    </span>
                                    <span class="pl-2 due_date_assignment-d due_date_assignment-t">

                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row pt-3 pb-5 px-xl-5 align-items-end">
                            <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 col-12 offset-xl-3 offset-lg-2">
                                <div class="card shadow mt-4 customs_card-s">
                                    <div class="img_max_x_200-s">
                                        <img class="card-img-top w-100 h-100" src="{{ asset('assets/images/pdf.svg') }}" alt="Card image cap">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title fg-success-s">

                                        </h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mt-4 ">
                                <div>
                                    <span class="fg-success-s">
                                        Total Marks: <strong class='students_count-d fg_black-s total_marks-d total_marks-t'>50</strong>
                                    </span>
                                </div>
                                <div class="mt-3 ">
                                    <span class="fg-success-s">
                                        Obtained Mark:  <strong class='attempts_count-d fg_black-s obtain_marks-d obtain_marks-t'></strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- modal body end-->
                </div>
            </div>
        </div>
    </div>
</div>
<!--assignment result modal end-->
