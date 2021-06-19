
    <div class="modal fade" id="modal_send_zoom_meeting_link-d" tabindex="-1" role="tabpanel" aria-labelledby="modal-head" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="diaglog">
            <div class="modal-content pb-5 ">
                <div class="modal-header custom-header-s align-self-center mt-3 w-100">
                    <h6 class="custom-title-s font-weight-bold w-100 text-center">
                        Send Zoom Meeting Invitation Link
                    </h6>
                    <a data-dismiss="modal">
                        <img class="float-right" src="{{ asset('assets/images/modal_close_icon.svg') }}" alt="X">
                    </a>
                </div>

                <div class="modal-body justify-content-center justify-content-around">
                    <div class="container w-75 mb-3">
                        <div class="row">
                            <div class="col-12">
                                <form id='frm_send_invite_link-d' action="" method="POST">
                                    @csrf
                                    <div class="row">
                                       <div class="col mt-5">
                                            <label class="text-muted font-weight-normal" for="zoom_meeting_url">Zoom Meeting Invite Link</label>
                                            <input type='text' name='zoom_meeting_url' class='form-control txt_zoom_meeting_url-d' value="" placeholder="Zoom Invite Link" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col d-flex justify-content-center mt-5">
                                            <input type='hidden' name='slot_uuid' class='hdn_course_slot_uuid-d' value="" />
                                            <button type="submit" class="custom-button-s border border-white btn_activity_modal_next-d">
                                                Send
                                            </button>
                                        </div>
                                    </div>
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
                    <h6 class="ml-xl-2 custom-title-s font-weight-bold w-100 text-left">
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
                <div class="modal-body mb-5">
                    <div class="container w-100 px-0">
                        <div class="row py-4 bg-light-s">
                            <div class="col-12">
                                <h4 class="ml-xl-3 slot_course_title-d">Website Desiging</h4>
                                <span class="text-success ml-xl-3">Active</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mt-3">
                                <span class="ml-xl-3">This course will start in <strong class='time_left-d'>30 minutes</strong></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-xl-8 ml-xl-1">
                                <div class="form">
                                    <table class="table table-borderless">
                                       <tbody>
                                            <tr>
                                                <td>Sr.No</td>
                                                <th class='slot_sr-d'>01234</th>
                                            </tr>
                                            <tr>
                                                <td>Student</td>
                                                <th class='slot_student_name-d'>jacob</th>
                                            </tr>
                                             <tr>
                                                <td>Time</td>
                                                <th><strong class='slot_start-d'>11 AM</strong> To <strong class='slot_end-d'>12 PM</strong></th>
                                            </tr>
                                             <tr>
                                                <td>Course Type</td>
                                                <th class='slot_course_type-d'>Paid</th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
