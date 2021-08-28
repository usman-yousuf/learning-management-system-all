@extends('layouts.landing_page')

@section('page-title')
    Home
@endsection

@section('content')
        <!-- Show course Section - STRAT -->
        <section class="py-5">
            <div class="row">
                <!--show courses carousal - START -->
                @php
                    $courses = getAllApprovedCourses();
                @endphp
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
