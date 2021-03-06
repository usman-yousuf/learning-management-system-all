@extends('common::layouts.error')

@section('error-code')
    202 - Waiting for Approval
@endsection

@php
    $backUrl = (isset($backUrl) && ('' != $backUrl))? $backUrl : route('home');
@endphp

@section('content')
    <div class="container-fluid text-center position-fixed h-100">
        <div class="h-100 pt-5">
            <div class="modal-header border-0 align-self-center">
                <a href="javascript:void(0)" class='w-100'>
                    <img class="img_w_200px-s" src="{{ asset('assets/images/sand_clock_icon.svg') }}" alt="review-image" />
                </a>
            </div>
            {{-- <strong class="display-1 font_size_250px-s">202</strong> --}}
            <p class="pt-4 font_size_larger-s">Waiting for Approval</p>
            <a href="{{ $backUrl }}" class="btn btn pt-2 pb-2 pl-3 pr-3 add_course_btn-s mt-5">
                <img src="{{ asset('assets/images/error_page_back_icons.svg') }}" width="20" class="ml-2 mr-2" alt="">
                <span class="ml-2 mr-2 text-white">Go Back</span>
            </a>
        </div>
    </div>
@endsection
