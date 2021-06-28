@extends('teacher::layouts.teacher')
@section('page-title')
    Student Dashboard
@endsection

@section('content')
    <section class="pt-5 pb-5">
       @include('course::partials/_course_listing', ['enrolled_courses' => $enrolled_courses])
    </section>
@endsection

@push('header-scripts')

@endpush

@push('header-css-stack')

@endpush

@section('header-css')

@endsection

@push('footer-head-scripts')

@endpush

@section('footer-scripts')
    @php
        // dd( json_decode($online_courses_graph_data) );
    @endphp

    <script>
    </script>
    <script src="{{ asset('assets/js/manage_courses.js') }}"></script>
@endsection

@section('header-css')
    <link rel="stylesheet" href="{{ asset('assets/css/course.css') }}" />
@endsection
