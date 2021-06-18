
    <div class="modal fade" id="modal_send_zoom_meeting_link-d" tabindex="-1" role="tabpanel" aria-labelledby="modal-head" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="diaglog">
            <div class="modal-content pb-5 ">
                <div class="modal-header border-0 ">
                    <div class="container ml-lg-4">
                        <div class="row pb-5 pt-3 ">
                            <div class="col ">
                                <h4 class="modal-title text-success" id="modal-head">Send Zoom Meet Invite</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-body justify-content-center justify-content-around">
                    <div class="container w-75 mb-3">
                        <div class="row">
                            <div class="col-12">
                                <form action="" method="POST">
                                    @csrf
                                    <input type='hidden' name='slot_uuid' class='hdn_course_slot_uuid-d' value="" />
                                    <input type='text' name='zoom_meeting_url' class='form-control txt_zoom_meeting_url-d' value="" />
                                    <button type="submit" class="custom-button-s border border-white btn_activity_modal_next-d">
                                        Start
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="lecture_modal-d" tabindex="-1" role="tabpanel" aria-labelledby="modal-head" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="diaglog">
            <div class="modal-content">
                {{--  <div class="modal-header">
                    <div class="container mb-3 pb-3 ">
                        <!--Modal Header-->
                        <div class="row ml-3 ">
                            <div class="mt-3 mb-3 ">
                                <h4 class="modal-title text-success" id="modal-head">Start Lecture</h4>
                            </div>
                        </div>
                        <!--Modal Header End-->
                    </div>
                </div>  --}}



                <!-- Modal Header -->
                <div class="modal-header custom-header-s align-self-center mt-3 w-100">
                    <h6 class="custom-title-s font-weight-bold w-100 text-left">
                        <input type='hidden' name='slot_uuid' class='hdn_course_slot_uuid-d' value="" />
                        <button type="button" class="custom-button-s border border-white btn_show_zoom_meeting_modal-d">
                            Start
                        </button>
                    </h6>
                    <a data-dismiss="modal">
                        <img class="float-right" src="{{ asset('assets/images/modal_close_icon.svg') }}" alt="X">
                    </a>
                </div>

                <!-- Modal body -->
                <div class="modal-body justify-content-center justify-content-around">
                    <div class="container w-75 mb-3">
                        <div class="row">
                            <div class="col-12">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
