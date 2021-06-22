@extends('authall::layouts.auth')

@section('page-title')
    Parent Login
@endsection

@php
    $profile_type = 'parent';
@endphp

@section('auth-content')
    @include('authall::_partials/login_content', ['profile_type'=> 'parent'])
@endsection

@section('footer-scripts')
    <script type="text/javascript" src='{{ asset('modules/authall/assets/js/authall.js') }}'></script>
@endsection
