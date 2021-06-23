@extends('common::layouts.error')

@php
    $errorCode = 403;
    $errorMessage = (isset($message) && ('' != $message))? $message : "You are not Authorized to access this page. please contact your site admin";
@endphp
@section('error-code')
    {{ $errorCode }}
@endsection

@section('content')
    <div class="container-fluid text-center position-fixed py-5 h-100">
        <div class="h-100 pt-5">
            <strong class="display-1 font_size_250px-s">{{ $errorCode }}</strong>
            <p class="pt-4 font_size_larger-s">{{ $errorMessage }}</p>
            <a href="{{ route('home') }}" class="btn btn pt-2 pb-2 pl-3 pr-3 add_course_btn-s mt-5">
                <img src="{{ asset('assets/images/error_page_back_icons.svg') }}" width="20" class="ml-2 mr-2" alt="">
                <span class="ml-2 mr-2 text-white">Go Back</span>
            </a>
        </div>
    </div>
@endsection
