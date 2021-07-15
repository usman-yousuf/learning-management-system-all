<div class="container-fluid px-5">
    <div class="row pt-4">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 align-self-center">
            <h4 class="font_w_700-s">Activity Calendar</h4>
        </div>
        @if (\Auth::user()->profile_type == 'teacher')
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                <div class="float-md-right">
                    <a href="javascript:void(0)" class="btn btn py-3 px-4 add_course_btn-s open_add_calendar_activity-d">
                        <img src="{{ asset('assets/images/add_btn_icon.svg') }}" width="20" id="add_course-d" class="mx-2" alt="+">
                        <span class="mx-2 text-white">Add Activity</span>
                        @php
                            // dd($data);
                        @endphp
                    </a>
                </div>
            </div>
        @endif
    </div>
    <div class="row pt-5 pl-2">
        <div class="col-12 mt-3 mb-5 notification_listing_container-d">
            @php
                // dd($data->notifications);
            @endphp
            <div class="full-calendar"></div>
        </div>
    </div>
</div>

@include('common::modals.add_calendar_activity', [])
@include('assignment::modals.add_assignment', [])
@include('course::modals.start_lecture', [])
@include('quiz::modals.add_quiz_activity', [])
@include('common::modals.check_test', [])
@include('common::modals.mark_test_answers', [])
@include('student::modals.start_mcqs_or_test_quiz_modal',[])
@include('student::modals.download_assignment_modal',[])
@include('student::modals.assignment_submit_modal',[])
@include('student::modals.class_schedule_modal',[])
@include('student::modals.quiz_result_modal',[])
@include('student::modals.assignment_result_modal',[])

{{-- student assignment uploaded  and in teacher side , open modal to download student uploaded assignment and mark them --}}
@include('common::modals.student_uploaded_assignment', [])
{{-- teacher marked student assignment  --}}
@include('common::modals.mark_student_assignment', [])

