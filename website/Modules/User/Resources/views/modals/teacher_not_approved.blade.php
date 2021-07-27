 <!--not approved modal-->
 <div class="modal fade" id="not_approved_teacher_modal" tabindex="-1" role="comment" aria-labelledby="view-head" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header d-block">
                <div class="container">
                    <!--modal header-->
                    <div class="row">
                        <div class="col-12 text-right">
                            <a class="close pt-3 pr-0" data-dismiss="modal" aria-label="Close">
                                <img class="float-right" src="{{ asset('assets/images/group.svg') }}" alt="">
                            </a>
                        </div>
                    </div>
                    <!--modal header end-->

                    <!--MODAL BODY-->
                    <div class="modal-body">
                        <div class="row pt-5">
                            <div class="col-12 ">
                                <h4><strong>Please explain reason for rejection</strong></h4>
                                <textarea class="form-control bg-light rounded-4 pt-2 mt-5" placeholder="Type your question......" id="" rows="6"></textarea>
                            </div>
                        </div>
                    </div>
                    <!--modal body end-->
                    <!-- Modal footer -->
                    <form action="" class="frm_rejection-d" method="post">
                        <div class="modal-footer border-0 mb-5 mt-xl-5 mt-lg-5 mt-sm-5 mt-3 justify-content-center">
                            <button type="button" class="bg_success-s br_24-s py-2  w_315px-s text-white  border border-white ">
                                Submit
                            </button>
                        </div>
                    </form> 
                    <!-- Modal footer End -->
                </div>
            </div>
        </div>
    </div>
</div>
<!--not approved modal end-->