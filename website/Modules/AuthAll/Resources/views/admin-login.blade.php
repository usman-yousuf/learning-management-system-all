@extends('authall::layouts.auth')

@section('page-title')
    Admin Login
@endsection

@php
    $profile_type = 'admin';
@endphp

@section('auth-content')
    @include('authall::_partials/login_content', ['profile_type'=> 'admin'])
@endsection

@section('footer-scripts')
    <script type="text/javascript" src='{{ asset('modules/authall/assets/js/authall.js') }}'></script>
@endsection
