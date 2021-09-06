@php
    $target_layout = (\Auth::check()) ? 'teacher::layouts.teacher' : 'layouts.landing_page';
@endphp

@extends($target_layout)

@section('page-title')
    {{ $listing_nature ?? 'Our Courses' }}
@endsection

@php

@endphp

@section('content')
        <!-- Show course Section - STRAT -->
        <section class="py-5">
            @if(\Auth::check())
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="h3 @if(\Auth::check()) ml-3 @else ml-5 @endif">{{ $listing_nature ?? 'Our Courses' }}</div>
                    </div>
                </div>
            @endif

            <div class="row px-3">
                <!--show courses carousal - START -->
                @include('partials/courses_listing', ['courses' => $courses])
                <!--show courses carousal - END -->
            </div>

            <!--Course button-->
            {{-- <div class="row justify-content-center mt-4">
                <div class="col-md-3 col-5 ">
                    <button type="button" class="btn text-white bg_green_gradient-s rounded-pill border-0 w-100 py-2">Courses</button>
                </div>
            </div> --}}
        </section>
        <!-- Show course Section - END -->
@endsection
