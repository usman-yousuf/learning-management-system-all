@php

@endphp


<div class="modal" id="check_test_modal-d">
    <div class="modal-dialog modal-lg">
        <div class="modal-content d-flex">

            <!-- Modal Header Start -->
            <div class="container-fluid">
                <div class="row">
                    <div class=" col-12 modal-header border-0  mt-3 w-100">
                        <h5 class="modal-title font-weight-bold w-100 text-center fg_green-s modal_heading-d">Check Test</h5>
                        <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                        <span>
                            <a data-dismiss="modal">
                                <img class="float-right" src="{{ asset('assets/images/modal_close_icon.svg') }}" alt="X">
                            </a>
                        </span>
                    </div>
                </div>
            </div>
            <!-- Modal Header End -->


            <!-- Modal Body Start -->
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row justify-content-around mb-4">
                        <div class="col-12">
                            <img class="rounded-circle mx-auto d-block img_size-s modal_profile_image-d" src="{{ getFileUrl(null, null, 'profile') }}" alt="Student Name">
                        </div>
                        <div class="col-12 mt-2 text-center fg-success-s">
                            <h5 class="modal_profile_name-d">Student Name</h5>
                        </div>
                        {{--  <div class="col-12 text-center">
                            <p>
                                Enrolled Date: <strong class='modal_enrollment_date-d'>3 Feb 2021</strong>
                            </p>
                        </div>  --}}
                        <div class="col-12 text-center fg_light-s">
                            <h5 class='modal_course_title-d'>Course Name</h5>
                        </div>
                        <div class="col-12 text-center">
                            <h5 class='modal_course_category-d'>Graphic Designing</h5>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Body End -->

            <!-- Modal footer -->
            <div class="row">
                <div class="col-12">
                    <div class=" col-3 w-100 mx-auto align-self-center modal-footer border-0 mb-4">
                        <input type="hidden" name='course_uuid' class='course_uuid-d' />
                        <input type="hidden" name='quiz_uuid' class='quiz_uuid-d' />
                        <input type="hidden" name='student_uuid' class='student_uuid-d' />
                        <button type="button" class="py-xl-3 py-lg-2 py-md-2 py-2 w-100 text-white bg_success-s br_27px-s custom-button border border-white btn_see_test-d" disabled="disabled">Next</button>
                        <button type="button" class="py-xl-3 py-lg-2 py-md-2 py-2 w-100 text-white bg_success-s br_27px-s custom-button border border-white">
                            <a class="w-100 btn_view_quiz_link-d fg_white-s" style="display: none;">View</a>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal Footer End -->
        </div>
    </div>
</div>


@push('header-scripts')
    <script>
        let load_test_quiz_data = "{{ route('quiz.load-student-answers') }}"
    </script>
@endpush
