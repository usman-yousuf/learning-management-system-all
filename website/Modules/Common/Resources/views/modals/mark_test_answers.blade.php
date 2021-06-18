@php

@endphp


<div class="modal" id="mark_test_quiz_answers_modal-d">
    <div class="modal-dialog modal-xl">
        <div class="modal-content d-flex">

            <!-- Modal Header Start -->
            <div class="container-fluid">
                <div class="row">
                    <div class=" col-12 modal-header border-0  mt-3 w-100">
                        <h5 class="modal-title font-weight-bold w-100 text-center fg_green-s">&nbsp;</h5>
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
                <div class="container-fluid student_answers_main_container-d">
                    {{--  Cloned Container  --}}
                </div>

            </div>
            <!-- Modal Body End -->
        </div>
    </div>
</div>

<div class="cloneables_main_container-d" style='display:none;'>
    <div class="row ml-4 justify-content-around pb-4 pt-1 single_answer_container-d" id='cloneable_answer_container-d'>
        <div class="col-12">
            <div class="row">
                <div class="col-9 ml-3">
                    <span>
                        <a href="javascript:void(0)">
                            <img class="w_25px-s student_profile_image-d" src="{{ getFileUrl(null, null, 'profile') }}" alt="student-image">
                        </a>
                    </span>
                    <span class="fg-success-s student_name-d">Student Name</span>
                </div>
                <div class="col-9 ml-3">
                    <p class='asked_question-d'>
                        Lorem Ipsum is simply dummy text of the printing and has been the industry's standard dummy text ever since the 1500s?
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-9">
                    <div class="ml-3">
                        <strong>Ans:</strong>
                        <span class='student_answer-d'>Lorem Ipsum is simply dummy text of the printing and has been the industry's standard</span>
                    </div>
                </div>
                <div class="col-3 text-center">
                    <input type="hidden" name='student_uuid' class='student_uuid-d' />
                    <input type="hidden" name='course_uuid' class='course_uuid-d' />
                    <input type="hidden" name='quiz_uuid' class='quiz_uuid-d' />
                    <input type="hidden" name='question_uuid' class='question_uuid-d' />
                    <input type="hidden" name='student_answer_uuid' class='student_answer_uuid-d' />
                    <span>
                        <a href="javascript:void(0)">
                            <img src="{{ asset('assets/images/cancel.svg') }}" class='mark_ans_wrong-d' alt="wrong-answer">
                        </a>
                        <a href="javascript:void(0)">
                            <img src="{{ asset('assets/images/tick_mark.svg') }}" class='mark_ans_right-d' alt="right-answer">
                        </a>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('header-scripts')
    <script>
        let mark_test_answer_url = "{{ route('quiz.mark-student-answers') }}"
        let get_test_quiz_result_data = "{{ route('quiz.get-student-result') }}"
    </script>
@endpush
