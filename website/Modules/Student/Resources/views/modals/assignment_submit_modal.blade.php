   <!--assignment submit modal-->
   <div class="modal fade" id="assignment_submit-d" tabindex="-1"  aria-labelledby="view-head" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" >
        <div class="modal-content ">
            <div class="modal-header d-block">    
                <div class="container pb-5">
                    <!--modal header-->
                    <div class="row">
                        <div class="col-12 text-right">
                            <a class="close pt-3 pr-0" data-dismiss="modal" aria-label="Close">
                                <img class="float-right" src="{{ asset('assets/cancel_circle.svg') }}" alt="">
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
                                    <h4 class=" title-d">
                                        Graphic Designing
                                    </h4>
                                </a>
                            </div>
                            <div class="col-xl-8 col-lg-6 col-md-12 col-12 mt-1 text-xl-right text-lg-right">
                                <span >
                                    <img class="img_25_x_25-s" src="{{ asset('assets/images/student_quiz_calender.svg') }}" alt="calendar">
                                </span>
                                <span class="pl-2">
                                    01 Feb 2021
                                </span>
                            </div>
                        </div>
                        <div class="row pt-3 px-xl-5">
                            <div class="col-12 d-md-flex">
                                <div class="text-center">
                                    <label class='doc_upload-d' for="">
                                        <img src="{{ asset('assets/images/upload_icon.svg') }} "class="img_200x175-s " alt="upload" />
                                    </label>
                                    <input id="upload_doc_preview-d" type="file" style="display: none;"/>
                                </div>
                                
                                <div class="ml-md-3 mt-md-0 mt-3 text-center">
                                    <img id="education_certificate-d" src="../assets/preview/card1.png" class="img_200x175-s obj_fit_cover-s"  alt="">
                                </div>
                            </div>
                            
                        </div>     
                    </div>
                    <!-- modal body end-->  
                    <!-- Modal footer -->
                    <div class="modal-footer border-0 mb-4 mt-3  justify-content-center">
                        <button type="button" class="btn bg_success-s br_24-s py-2  text-white w_315px-s border border-white" >
                            Submit
                        </button>
                    </div>
                    <!-- Modal footer End -->      
                </div>
            </div>
        </div>
    </div>          
</div>
<!--assignment submit modal end-->