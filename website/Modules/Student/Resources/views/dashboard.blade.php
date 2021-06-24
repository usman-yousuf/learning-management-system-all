@extends('teacher::layouts.teacher')
@section('content')

<div class="online_courses_container">

     {{--  Title of section and + btn - START  --}}
     <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 align-self-center">
            <h3 class="top_courses_text-s">My Enrolled Courses</h3>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="float-md-right">
                <a href="{{ route('course.listCoursesByNature', ['nature' =>'online']) }}" class="btn btn py-3 px-4 add_course_btn-s">
                    <img src="{{ asset('assets/images/add_btn_icon.svg') }}" width="20" id="add_course-d" class="mx-2" alt="+">
                    <span class="mx-2 text-white">View All</span>
                </a>
            </div>
        </div>
    </div>
    {{--  Title of section and + btn - END  --}}

    <section class="pt-5 pb-5">
        {{-- @foreach ($top_enrolled_courses as $item)
        {{ dd($item->count()) }}
            
        @endforeach --}}
        {{-- {{ dd($top_enrolled_courses) }} --}}

        @if(count($top_enrolled_courses))
            <div class="row">
                <!-- For LARGE SCREEN - START -->
                <div class="col-12 d-none d-lg-block">
                    <div id="carouselExampleIndicators2" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            @foreach (array_chunk($top_enrolled_courses, 3) as $three)
                                <div class="carousel-item @if ($loop->first) active @endif">
                                    <div class="row">
                                        @foreach ($three as $item)
                                           {{-- {{ dd($item) }} --}}
                                            @php
                                                $item = (object)$item;
                                                // dd($item);
                                            @endphp
                                            <!-- carousal item - show 3 at a time -->
                                            <div class="col-md-4 mb-3">
                                                <div class="card">
                                                        <img class="img-fluid mx-auto img_max_x_200-s" alt="course-image" src="{{ getFileUrl($item->course->course_image, null, 'course') }}">
                                                    <!-- ------card content---- -->
                                                    <div class="">
                                                        <div class="d-flex mt-3 card_design_text-s">
                                                            <div class="container">
                                                                {{--  title and category - START  --}}
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <h6><a href="{{ route('course.view', ['uuid' => $item->course->uuid]) }}" class='no_link-s'>{{ $item->course->title ?? '(not set)' }}</a></h6>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <span>{{ ucwords($item->course->category->name ?? '(category not set)') }}</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col text-right">
                                                                        <a href="javascript:void(0)" class="btn btn px-lg-1 px-xl-3 course_pay_btn-s" disbaled="disbaled">{{ $item->course->is_course_free ? 'Free' : 'Paid' }}</a>
                                                                    </div>
                                                                </div>
                                                                {{--  title and category - END  --}}

                                                                <div class="row pt-3 pb-3">
                                                                    <div class="col-6 mb-3x">
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <img src="{{ asset('assets/images/youtube_icon.svg') }}" class="" alt="">
                                                                                <span class="mx-2">{{ ucwords($item->course->nature) }}</span>

                                                                                <br />
                                                                                <img src="{{ asset('assets/images/enrollment_icon.svg') }}" class="" alt="">
                                                                                <span class="mx-2"><strong>{{ getPeopleCount($item->course->students_count ?? 0) }}</strong> Students</span>

                                                                                <br />
                                                                                <img src="{{ asset('assets/images/dollar-icon.svg') }}" class="" alt="">
                                                                                <span class="mx-2">{{ ucwords($item->course->is_course_free? 'Free' : 'Paid') }}</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-6 text-center text-lg-center text-xl-right">
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <img src="{{ asset('assets/images/calendar_course_icon.svg') }}" class="" alt="">
                                                                                <span class="ml-2">{{ date('d M Y', strtotime($item->course->start_date)) }}</span>
                                                                            </div>

                                                                            <div class="col-12 pr-xl-3">
                                                                                <h6 class=" mb-0">to</h6>
                                                                            </div>

                                                                            <div class="col-12">
                                                                                <img src="{{ asset('assets/images/calendar_course_icon.svg') }}" class="" alt="">
                                                                                <span class="ml-2">{{ date('d M Y', strtotime($item->end_date ?? 'now')) }}</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- ------card content End---- -->
                                                </div>
                                            </div>
                                            <!-- carousal item End -->
                                        @endforeach
                                    </div>
                                </div>
                                {{--  show 3 item for large screen - END  --}}
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- For LARGE SCREEN - END -->

                <!-- FOR MEDIUM SCREEN - START -->
                <div class="col-12 d-none d-sm-block d-lg-none">
                    <div id="carouselExampleIndicators2" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            @foreach (array_chunk($top_enrolled_courses, 2) as $two)
                                <div class="carousel-item @if ($loop->first) active @endif">
                                    <div class="row">
                                        @foreach ($two as $item)
                                            <!-- carousal item - show 3 at a time -->
                                            <div class="col-md-6 mb-3">
                                                <div class="card">
                                                        <img class="img-fluid mx-auto img_max_x_200-s" alt="course-image" src="{{ getFileUrl($item->course->course_image, null, 'course') }}">
                                                    <!-- ------card content---- -->
                                                    <div class="">
                                                        <div class="d-flex mt-3 card_design_text-s">
                                                            <div class="container">
                                                                {{--  title and category - START  --}}
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <h6><a href="{{ route('course.view', ['uuid' => $item->course->uuid]) }}" class='no_link-s'>{{ $item->course->title ?? '(not set)' }}</a></h6>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <span>{{ ucwords($item->course->category->name ?? '(category not set)') }}</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col text-right">
                                                                        <a href="javascript:void(0)" class="btn btn px-lg-1 px-xl-3 course_pay_btn-s" disbaled="disbaled">Paid</a>
                                                                    </div>
                                                                </div>
                                                                {{--  title and category - END  --}}

                                                                <div class="row pt-3 pb-3">
                                                                    <div class="col-6 mb-3x">
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <img src="{{ asset('assets/images/youtube_icon.svg') }}" class="" alt="">
                                                                                <span class="mx-2">{{ ucwords($item->course->nature) }}</span>

                                                                                <br />
                                                                                <img src="{{ asset('assets/images/enrollment_icon.svg') }}" class="" alt="">
                                                                                <span class="mx-2"><strong>{{ getPeopleCount($item->course->students_count ?? 0) }}</strong> Students</span>

                                                                                <br />
                                                                                <img src="{{ asset('assets/images/dollar-icon.svg') }}" class="" alt="">
                                                                                <span class="mx-2">{{ ucwords($item->course->is_course_free? 'Free' : 'Paid') }}</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-6 text-center text-lg-center text-xl-right">
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <img src="{{ asset('assets/images/calendar_course_icon.svg') }}" class="" alt="">
                                                                                <span class="ml-2">{{ date('d M Y', strtotime($item->course->start_date)) }}</span>
                                                                            </div>

                                                                            <div class="col-12 pr-xl-3">
                                                                                <h6 class=" mb-0">to</h6>
                                                                            </div>

                                                                            <div class="col-12">
                                                                                <img src="{{ asset('assets/images/calendar_course_icon.svg') }}" class="" alt="">
                                                                                <span class="ml-2">{{ date('d M Y', strtotime($item->course->end_date ?? 'now')) }}</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- ------card content End---- -->
                                                </div>
                                            </div>
                                            <!-- carousal item End -->
                                        @endforeach
                                    </div>
                                </div>
                                {{--  show 3 item for large screen - END  --}}
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- FOR MEDIUM SCREEN - END -->


                <!-- FOR SMALL SCREEN - START -->
                <div class="col-12 d-block d-sm-none">
                    <div id="mobile_screenCarousal-d" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            @foreach ($top_enrolled_courses as $item)
                                <div class="carousel-item @if ($loop->first) active @endif">
                                    <div class="row">
                                        <!-- carousal item - show 3 at a time -->
                                        <div class="col-md-12 mb-3">
                                            <div class="card">
                                                <img class="img-fluid mx-auto img_max_x_200-s" alt="course-image" src="{{ getFileUrl($item->course->course_image, null, 'course') }}">                                            <!-- ------card content---- -->
                                                <div class="">
                                                    <div class="d-flex mt-3 card_design_text-s">
                                                        <div class="container">
                                                            {{--  title and category - START  --}}
                                                            <div class="row">
                                                                <div class="col">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <h6>{{ $item->course->title ?? '(not set)' }}</h6>
                                                                            <h6><a href="{{ route('course.view', ['uuid' => $item->course->uuid]) }}" class='no_link-s'>{{ $item->course->title ?? '(not set)' }}</a></h6>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <span>{{ ucwords($item->course->category->name ?? '(category not set)') }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col text-right">
                                                                    <a href="javascript:void(0)" class="btn btn px-lg-1 px-xl-3 course_pay_btn-s" disbaled="disbaled">Paid</a>
                                                                </div>
                                                            </div>
                                                            {{--  title and category - END  --}}

                                                            <div class="row pt-3 pb-3">
                                                                <div class="col-6 mb-3x">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <img src="{{ asset('assets/images/youtube_icon.svg') }}" class="" alt="">
                                                                            <span class="mx-2">{{ ucwords($item->course->nature) }}</span>

                                                                            <br />
                                                                            <img src="{{ asset('assets/images/enrollment_icon.svg') }}" class="" alt="">
                                                                            <span class="mx-2"><strong>{{ getPeopleCount($item->course->students_count ?? 0) }}</strong> Students</span>

                                                                            <br />
                                                                            <img src="{{ asset('assets/images/dollar-icon.svg') }}" class="" alt="">
                                                                            <span class="mx-2">{{ ucwords($item->course->is_course_free? 'Free' : 'Paid') }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6 text-center text-lg-center text-xl-right">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <img src="{{ asset('assets/images/calendar_course_icon.svg') }}" class="" alt="">
                                                                            <span class="ml-2">{{ date('d M Y', strtotime($item->course->start_date)) }}</span>
                                                                        </div>

                                                                        <div class="col-12 pr-xl-3">
                                                                            <h6 class=" mb-0">to</h6>
                                                                        </div>

                                                                        <div class="col-12">
                                                                            <img src="{{ asset('assets/images/calendar_course_icon.svg') }}" class="" alt="">
                                                                            <span class="ml-2">{{ date('d M Y', strtotime($item->course->end_date ?? 'now')) }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- ------card content End---- -->
                                            </div>
                                        </div>
                                        <!-- carousal item End -->
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- FOR SMALL SCREEN  - END -->
            </div>
        @else
            <div class="row">
                <div class="col-12 text-center mt-4 mb-4">
                    <p class="w-100 text-center">
                        <strong>
                            No Course(s) Found
                        </strong>
                    </p>
                </div>
            </div>
        @endif
    </section>

</div>
   

    {{--  modals - START  --}}

   

  

    {{--  modals - END  --}}

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
