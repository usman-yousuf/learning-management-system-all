@php

@endphp


<div class="modal" id="add_calendar_activity_modal-d">
    <div class="modal-dialog modal-lg">
        <div class="modal-content custom-model-content d-flex" style="margin-top: 100px;">

            <!-- Modal Header -->
            <div class="modal-header custom-header-s align-self-center mt-3 w-100">
                <h5 class="modal-title font-weight-bold w-100 text-center fg_green-s">Activity Type</h5>
                <a data-dismiss="modal">
                    <img class="float-right" src="{{ asset('assets/images/modal_close_icon.svg') }}" alt="X">
                </a>
            </div>

            <!-- Modal body -->
            <div class="modal-body d-flex justify-content-center justify-content-around" style="width:100%; padding: 60px;">
                <div id="activity_quiz-d" class="card activity_card-s calendar_activity_card-d mr-md-3" style=" border: none; width: 40%;" data-activity_type="quiz">
                    <div class="card-body custom-card-body-s text-center shadow bg-body rounded pt-5 p-5">
                        <img id="online_course_img-d" src="{{ asset('assets/images/quiz_image.svg') }}" alt="quiz" class="filter-green">
                        <h6 class="custom-text-s mt-4">Quiz</h6>
                    </div>
                </div>

                <div id="activity_assignment-d" class="card activity_card-s calendar_activity_card-d ml-md-3" style=" border: none; width: 40%;" data-activity_type="assignment">
                    <div class="card-body custom-card-body-s text-center shadow bg-body rounded pt-5 p-5">
                        <img id="video_course_img-d" src="{{ asset('assets/images/assignment_icon.svg') }}" alt="Assignment">
                        <h6 class="custom-text-s mt-4">Assignment</h6>
                    </div>
                </div>
            </div>


            <!-- Modal footer -->
            <div class="modal-footer align-self-center custom-footer-s mb-5">
                <input type="hidden" name="activity_type" class="hdn_activity_type_selection-d" value="" />
                <button type="button " class="custom-button-s border border-white btn_activity_modal_next-d">
                    Next
                </button>
            </div>

        </div>
    </div>
</div>
