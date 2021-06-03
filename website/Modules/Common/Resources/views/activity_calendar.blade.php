@extends('teacher::layouts.teacher')

@section('page-title')
    Activity Calendar
@endsection

@section('content')
    <div class="container-fluid px-5">
        <div class="row pt-4">
            <div class="col">
                <h4 class="font_w_700-s">Activity Calendar</h4>
            </div>
        </div>
        <div class="row pt-5 pl-2">
            <div class="col-12 mt-3 notification_listing_container-d">
                <div class="full-calendar"></div>
            </div>
        </div>
    </div>
@endsection


@section('footer-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>

    <script src="{{ asset('assets/js/manage_notifications.js') }}"></script>

    <script>
        $('.full-calendar').fullCalendar({
            // put your options and callbacks here
            // defaultView: 'agendaWeek',
            events : [
                @foreach($data->notifications as $item)
                {
                    title : '{{ $item->sender->first_name . ' ' . $item->sender->last_name }}',
                    @if($item->noti_type == 'assignments')
                        start : '{{ $item->start_time }}',
                        @if ($item->due_date)
                            end: '{{ $item->due_date }}',
                        @endif
                    @else
                        start : '{{ $item->created_at }}',
                    @endif
                },
                @endforeach
            ],
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
