@extends('teacher::layouts.teacher')

@section('page-title')
    {{ $listing_nature ?? 'Stats' }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 pt-5 ">
                <h3>{{ $listing_nature ?? 'Stats' }}</h3>
            </div>
        </div>
        <!-- STUDENTS - START -->
        <div class="row pb-5 px-3 px-md-4 px-lg-0 flex-column-reverse flex-lg-row">
            @include('student::partials/_student_listing', ['students' => $students])
        </div>
        <!-- STUDENTS - END -->
    </div>
@endsection


@section('footer-scripts')
    <script src="{{ asset('assets/js/manage_courses.js') }}"></script>
@endsection

@section('header-css')
    <link rel="stylesheet" href="{{ asset('assets/css/course.css') }}" />
@endsection


@push('header-scripts')
    <script>
        let modal_delete_outline_url = "{{ route('course.delete-outline') }}";
        let modal_delete_slot_url = "{{ route('course.delete-slot') }}";
        let modal_delete_video_content_url = "{{ route('course.delete-video-content') }}";
    </script>
@endpush
