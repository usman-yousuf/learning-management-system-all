<!--not approved modal-->
<div class="modal fade" id="not_approved_teacher_course_modal" tabindex="-1" role="comment" aria-labelledby="view-head" aria-hidden="true">
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
                        <form class='frm_reject_teacher_course-d' action="{{ route('rejectTeacherCourse') }}" method="POST">
                            <div class="row pt-5">
                                <div class="col-12 ">
                                    <h4><strong>Please explain reason for rejection</strong></h4>
                                    <textarea class="form-control bg-light rounded-4 pt-2 mt-5" name='rejection_description' placeholder="Type your Reason......" rows="6"></textarea>
                                </div>
                            </div>
                            <div class="text-center border-0 mb-5 mt-xl-5 mt-lg-5 mt-sm-5 mt-3 justify-content-center">
                                <input type="hidden" class='hdn_course_id-d' name='course_uuid' />
                                <button type="submit" class="bg_success-s br_24-s py-2  w_315px-s text-white  border border-white ">
                                    Submit
                                </button>
                            </div>
                        </form>
                    </div>
                    <!--modal body end-->
                </div>
            </div>
        </div>
    </div>
</div>
<!--not approved modal end-->
