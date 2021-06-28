@extends('teacher::layouts.teacher')
@section('content')
    student->views->dashboard
@endsection

@section('footer-scripts')
@php
    // dd( json_decode($online_courses_graph_data) );
@endphp

    <script>
    </script>
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    <script src="{{ asset('assets/js/manage_courses.js') }}"></script>
@endsection

@section('header-css')
    <link rel="stylesheet" href="{{ asset('assets/css/course.css') }}" />
@endsection
