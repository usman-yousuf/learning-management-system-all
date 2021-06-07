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
    <div class="container-fluid px-5">
        <div class="row pt-4">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 align-self-center">
                <h4 class="font_w_700-s">Activity Calendar</h4>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                <div class="float-md-right">
                    <a href="javascript:void(0)" class="btn btn py-3 px-4 add_course_btn-s open_add_calendar_activity-d">
                        <img src="{{ asset('assets/images/add_btn_icon.svg') }}" width="20" id="add_course-d" class="mx-2" alt="+">
                        <span class="mx-2 text-white">Add Activity</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="row pt-5 pl-2">
            <div class="col-12 mt-3 notification_listing_container-d">
                @php
                    // dd($data->notifications);
                @endphp
                <div class="full-calendar"></div>
            </div>
        </div>
    </div>

    @include('common::modals.add_calendar_activity', [])
    @include('assignment::modals.add_assignment', [])
@endsection


@section('footer-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>

    <script src="{{ asset('assets/js/manage_notifications.js') }}"></script>

    <script>
        $('.full-calendar').fullCalendar({
            // put your options and callbacks here
            // defaultView: 'agendaWeek',
            allDaySlot: false,
            events : [
                @foreach($data->notifications as $item)
                {
                    title : '{{ $item->sender->first_name . ' ' . $item->sender->last_name }}',
                    start : '{{ (null != $item->start_date)? $item->start_date : $item->created_at }}',
                    @if ($item->end_date)
                        end: '{{ $item->end_date }}',
                    @endif
                },
                @endforeach
            ],
            // eventClick: function(calEvent, jsEvent, view) {
            //     $('#event_id').val(calEvent._id);
            //     $('#appointment_id').val(calEvent.id);
            //     $('#start_time').val(moment(calEvent.start).format('YYYY-MM-DD HH:mm:ss'));
            //     $('#finish_time').val(moment(calEvent.end).format('YYYY-MM-DD HH:mm:ss'));
            //     $('#editModal').modal();
            // }
        });
    </script>
@endsection

@section('header-css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
@endsection


@push('header-scripts')
    <script>
        // let modal_delete_outline_url = "{{ route('course.delete-outline') }}";
    </script>
@endpush
