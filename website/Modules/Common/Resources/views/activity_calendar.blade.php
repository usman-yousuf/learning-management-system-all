@extends('teacher::layouts.teacher')

@section('page-title')
    Activity Calendar
@endsection

@push('header-css-stack')
    <style>
        .fc-event{
            /* color: #f00; */
        }
    </style>

@endpush

@section('content')
    @include('common::partials.calender_activity')
    {{-- <div class="container-fluid px-5">
        <div class="row pt-4">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 align-self-center">
                <h4 class="font_w_700-s">Activity Calendar</h4>
            </div>
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
    @include('common::modals.mark_test_answers', []) --}}
@endsection


@section('footer-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>

    <script src="{{ asset('assets/js/activity_calendar.js') }}"></script>
    <script src='{{ asset('modules/user/assets/js/user.js') }}'></script>
    <script src="{{ asset('assets/js/student.js') }}"></script>


@endsection

@section('header-css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
    <link rel="stylesheet" href="{{ asset('assets/css/course.css') }}" />
@endsection


@push('header-scripts')

    <script>
        let modal_get_slots_by_course = "{{ route('course.get-slots-by-course') }}";
        let calendar_events_data = '{!! $data->events !!}';
    </script>
    <script>
        // let modal_delete_outline_url = "{{ route('course.delete-outline') }}";
    </script>
@endpush
