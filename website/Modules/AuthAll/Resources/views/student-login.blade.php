@extends('authall::layouts.auth')

@section('page-title')
    Student Login
@endsection

@php
    $profile_type = 'student';
@endphp

@section('auth-content')
    @include('authall::_partials/login_content', [])
@endsection

@section('footer-scripts')
    <script type="text/javascript" src='{{ asset('modules/authall/assets/js/authall.js') }}'></script>
@endsection
