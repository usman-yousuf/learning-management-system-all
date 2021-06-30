@php

@endphp


<div class="modal fade" id="enroll_student_modal-d" tabindex="-1" role="tabpanel" aria-labelledby="modal-head" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="diaglog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header custom-header-s align-self-center mt-3 w-100">
                    <h4 class="ml-xl-2 custom-title-s font-weight-bold w-100 text-left">
                        Enroll a Student
                    </h4>
                    <a data-dismiss="modal">
                        <img class="float-right" src="{{ asset('assets/images/modal_close_icon.svg') }}" alt="X">
                    </a>
                </div>

                <!-- Modal body -->
                <div class="modal-body mb-5 pt-0">
                    <div class="container w-100 px-0">
                        <form class='frm_confirm_enrollment-d' method="POST" action="{{ route('student.enroll') }}">
                            <div class="row py-4 bg-light-s">
                                <div class="col-12">
                                    <h4 class="ml-xl-3 course_title-d">Website Desiging</h4>
                                    <span class="text-success ml-xl-3 course_status-d">Active</span>
                                </div>
                            </div>
                            <div class="container mt-5">

                                <div class="row mt-2">
                                    <div class="col-6">
                                        <label for="joining_date" class='form-label'>Joining Date</label>
                                        <input type='date' name='joining_date' class='modal_course_joining_date-d form-control' />
                                    </div>
                                    <div class="col-6">
                                        <div class="fee_amount_container-d">
                                            <label for="amount" class='form-label'>Amount Payable</label>
                                            <input type='number' name='amount' class='modal_amount_payable-d form-control' />
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-6">
                                        <div class="payment_method_conatiner-d">
                                            <label for="payment_method" class='form-label'>Payment Method</label>
                                            <select name="payment_method" class="form-control ddl_pay_method-d">
                                                <option value="">Select an Option</option>
                                                <option value="stripe">Stripe</option>
                                                <option value="easypaisa">Easy Paisa</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="stripe_cards_container-d" style="display: none;">
                                            <label for="card_uuid" class='form-label'>Select Card</label>
                                            <select name="card_uuid" class="form-control">
                                                <option value="">Select an Option</option>
                                                <option value="card_uuid_1">Card A</option>
                                                <option value="card_uuid_2">Card B</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="course_slots_main_container-d w-100"></div>
                                    <input type="hidden" name='slot_uuid' class='hdn_modal_slot_uuid-d' />
                                </div>

                                <div class="row py-4">
                                    <div class="col-12 text-right pr-5">
                                        <input type="hidden" name='course_nature' class='hdn_modal_course_nature-d' />
                                        <input type="hidden" name='course_uuid' class='hdn_modal_course_uuid-d' />
                                        <input type="hidden" name='is_course_free' class='hdn_modal_is_course_free-d' />

                                        <button class='btn btn-success btn_success' role="button" type="submit">Confirm</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
