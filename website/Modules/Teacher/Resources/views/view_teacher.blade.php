@extends('layouts.landing_page')

@section('page-title')
    View Teacher
@endsection

@section('content')

        {{--  About Teacher - START  --}}
        <section class="py-5">
            <div class="row">
                <!--show courses carousal - START -->
                <div class="col-12">
                    @php
                        $raters = $teacher->total_rater_count;
                        if($raters < 1){
                            $raters = 1;
                        }
                    @endphp
                    <div class="row">
                        <div class="col-10 offset-1 col-sm-4 offset-sm-0">
                            <div class="text-center">
                                <img src="{{ getFileUrl($teacher->profile_image, null, 'profile') }}" alt="Profile Image" class="img rounded-circle img_256_x_256-s" />
                            </div>
                        </div>
                        <div class="col-12 offset-0 col-sm">
                            <h3 class='fg_green-s text-break text-wrap'>{{ $teacher->full_name ?? 'Teacher Name' }}</h3>
                            <h6 class="">
                                <span class='fg_stone_color-s'>{!! getStarRatingHTML($teacher->total_rating_count / $raters) !!}</span>
                            </h6>

                            @if($teacher->about != '')
                                <p class="w-100 text-break text-wrap">
                                    Interests: <strong>{!! $teacher->interests ?? '' !!}</strong>
                                </p>
                            @endif

                            @if($teacher->user->email != '')
                                <p class="w-100 text-break text-wrap">
                                    <strong>Email</strong>: <a class='no_link-s' href="mailto:{{ $teacher->user->email ?? '' }}">{{ $teacher->user->email ?? '' }}</a>
                                </p>
                            @endif

                            @if($teacher->phone_code != '' && $teacher->phone_number != '')
                                <p class="w-100 text-break text-wrap">
                                    <strong>Email</strong>: <a class='no_link-s' href="tel:+{{ $teacher->phone_code ?? '' }}{{ $teacher->phone_number ?? '' }}">+{{ $teacher->phone_code ?? '' }}{{ $teacher->phone_number ?? '' }}</a>
                                </p>
                            @endif

                            @if($teacher->about != '')
                                <p class="w-100 text-break text-wrap">
                                    <strong>About</strong><br />{!! $teacher->about ?? '' !!}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
                <!--show courses carousal - END -->
            </div>
        </section>
        {{--  About Teacher - END  --}}

        {{--  Show Teacher Courses - START  --}}
        <section class="pb-5">
            <div class="row">
                <div class="col-12">
                    <h3><small>Courses</small></h3>
                </div>
            </div>
            <div class="row">
                <!--show courses carousal - START -->
                @include('partials/courses_listing', ['courses' => $teacher->courses])
                <!--show courses carousal - END -->
            </div>
        </section>
        {{--  Show Teacher Courses - END  --}}
@endsection
