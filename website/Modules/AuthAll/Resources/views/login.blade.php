@extends('authall::layouts.auth')

@section('page-title')
    Teacher Login
@endsection

@php
    $profile_type = 'teacher';
@endphp

@section('auth-content')
    @include('authall::_partials/login_content', ['profile_type'=>'teacher'])
@endsection

@section('footer-scripts')
    <script type="text/javascript" src='{{ asset('modules/authall/assets/js/authall.js') }}'></script>
@endsection
