@php

@endphp

<div class="modal" id="modal_add_quiz-d">
    <div class="modal-dialog modal-xl">
        <div class="modal-content d-flex">
            <!-- Modal Header -->
            <div class="modal-header custom-header-s align-self-center mt-3 w-100">
                <h5 class="modal-title font-weight-bold w-100 text-center fg_green-s">Add Quiz</h5>
                <a data-dismiss="modal">
                    <img class="float-right" src="{{ asset('assets/images/modal_close_icon.svg') }}" alt="X">
                </a>
            </div>

            <!-- Modal Body Start -->
            <div class="modal-body">
                <div class="container-fluid">
                    <form id='frm_add_quiz-d' action="{{ route('quiz.update') }}" method="POST">
                        <!-- assignments inputs - START -->

                        <div class="row justify-content-xl-around justify-content-lg-around">
                            <div class="w-100 col-xl-4 col-lg-4">
                                <label class="font-weight-normal course_textarea-s ml-3" for="quiz_title">Quiz Title</label>
                                <input type="text" class="form-control form-control-lg login_input-s" name="quiz_title" class="quiz_title-d" placeholder="Self Assessment">
                            </div>
                            <div class="w-100 col-xl-4 col-lg-4 mt-3 mt-md-3 mt-lg-0 mt-xl-0">
                                <label class="font-weight-normal course_textarea-s ml-3" for="quiz_type">Quiz Type</label>
                                <select class="form-control input_radius-s" class="ddl_quiz_type-d" name="quiz_type">
                                    <option value=''>Select an Option</option>
                                    <option value="test">Test</option>
                                    <option value="mcqs">Multiple Choice Questions</option>
                                    <option value="boolean">True | False</option>
                                </select>
                            </div>
                        </div>

                        <div class="row justify-content-xl-around justify-content-lg-around mt-3 mt-md-3 mt-lg-5 mt-xl-5">
                            <div class="w-100 col-xl-4 col-lg-4">
                                <label class="font-weight-normal course_textarea-s ml-3" for="quiz_duration">Quiz Duration</label>
                                <input type="number" class="form-control form-control-lg login_input-s" name="quiz_duration" class="quiz_duration-d" placeholder="30 Minutes">
                            </div>
                            <div class="w-100 col-xl-4 col-lg-4 mt-3 mt-md-3 mt-lg-0 mt-xl-0">
                                <label class="font-weight-normal course_textarea-s ml-3" for="due_date">Due Date</label>
                                <input type="date" class="form-control form-control-lg login_input-s" name="due_date" id="quiz_due_date-d" />
                            </div>
                        </div>

                        <div class="row justify-content-xl-around justify-content-lg-around mt-4 mt-md-4 mt-lg-4 mt-xl-4">
                            <div class="w-100 col-xl-4 col-lg-4">
                                <label class="font-weight-normal course_textarea-s ml-3" for="course_uuid">Course Name</label>
                                <select class="form-control input_radius-s ddl_course_uuid-d" name="course_uuid">
                                    <option value=''>Select an Option</option>
                                    @foreach (getTeacherCoursesList() as $uuid => $title)
                                        <option value="{{ $uuid }}">{{ $title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-100 col-xl-4 col-lg-4 mt-3 mt-md-3 mt-lg-0 mt-xl-0">
                                &nbsp;
                            </div>
                        </div>

                        <div class="row">
                            <div class="container">
                                <div class="col-lg-10 col-12 offset-lg-1 px-0 pt-3 course_slots_activity_container-d">

                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-xl-around justify-content-lg-around mt-4 mt-md-4 mt-lg-4 mt-xl-4">
                            <div class="container">
                                <div class="col-10 offset-1 px-0">
                                    <label for="description" class="course_textarea-s">Quiz Description</label>
                                    <textarea class="form-control textarea_h-s quiz_description-d" rows="5" class="quiz_description-d" name="description" placeholder="Description of Quiz"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class=" col-3 w-100 mx-auto align-self-center modal-footer border-0 mb-4">
                                    <input type='hidden' name='slot_uuid' class='hdn_slot_uuid-d' value='{{ '' }}' />
                                    <input type='hidden' name='quiz_uuid' class='hdn_quiz_uuid-d' value='{{ $quiz->uuid ?? '' }}' />
                                    <button type="submit " class="py-xl-3 py-lg-2 py-md-2 py-2 w-100 text-white bg_success-s br_27px-s custom-button border border-white btn_quiz_save-d">Add</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- assignments inputs - END -->
            </div>
            <!-- Modal Body End -->


            <!-- Modal footer -->


            <!-- Modal Footer End -->
        </div>
    </div>
</div>



@push('header-scripts')

@endpush
