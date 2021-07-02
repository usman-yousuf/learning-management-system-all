  <!--assignment modal-->
  <div class="modal fade" id="assignment-d" tabindex="-1"  aria-labelledby="view-head" aria-hidden="true">
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
                    <div class="modal-body ">
                        <div class="row pb-4">
                            <div class="col-12 text-center">
                                <h2 class="fg-success-s pb-5">Assignment</h2>
                            </div>
                        </div>
                        <div class="row pb-3 px-xl-5">
                            <div class="col-xl-4 col-lg-6 col-md-12  col-12">
                                <a class='no_link-s link-d'href="javascript:void(0)">
                                    <h4 class=" title-d assignment_title-d">
                                        Graphic Designing
                                    </h4>
                                </a>
                            </div>
                            <div class="col-xl-8 col-lg-6 col-md-12 col-12 mt-1 text-xl-right text-lg-right">
                                <span >
                                    <img class="img_25_x_25-s" src="{{ asset('assets/images/student_quiz_calender.svg') }}" alt="calendar">
                                </span>
                                <span class="pl-2 assignment_due_date-d">
                                    01 Feb 2021
                                </span>
                            </div>
                        </div>
                        <div class="row pt-3 px-xl-5 justify-content-center">
                            <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 col-12 ">
                                <div class="card shadow mt-4 customs_card-s">
                                    <div class="img_max_x_200-s">
                                        <img class="card-img-top w-100 h-100 assignmet_file-d" src="{{ getFileUrl('assignmet_file-d', '','assignment') }}" alt="Card image cap">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title fg-success-s assignment_title-d">
                                            Website designing
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row pt-5 px-xl-5">
                            <div class="col-12 text-center">
                                <h5>Download your Assignment File</h5>
                            </div> 
                        </div>      
                    </div>
                    <!-- modal body end-->  
                    <!-- Modal footer -->
                    <div class="modal-footer border-0 mb-5 mt-3 d-flex justify-content-around">
                            <a href="" class=" btn bg-primary br_24-s py-2 text-white w_315px-s border border-white download_assignmet_file-d" download>
                              Download
                            </a>  
                        <button type="button" class="btn bg_success-s br_24-s py-2  text-white w_315px-s border border-white assignment_submit-d" >
                            Submit Assignment
                        </button>
                    </div>
                    <!-- Modal footer End -->      
                </div>
            </div>
        </div>
    </div>          
</div>
<!--assignment modal end-->