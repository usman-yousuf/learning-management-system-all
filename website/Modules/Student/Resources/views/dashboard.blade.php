@extends('teacher::layouts.teacher')
@section('content')


    {{--  Title of section and + btn - START  --}}
    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 align-self-center">
            <h3 class="top_courses_text-s">Top 10 Courses</h3>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="float-md-right">
                <a href="javascript:void(0)" class="btn btn py-3 px-4 add_course_btn-s" data-toggle="modal" data-target="#activity_type_modal-d">
                    <img src="{{ asset('assets/images/add_btn_icon.svg') }}" width="20" id="add_course-d" class="mx-2" alt="+">
                    <span class="mx-2 text-white">Add Course</span>
                </a>
            </div>
        </div>
    </div>
    {{--  Title of section and + btn - END  --}}

    <section class="pt-5 pb-5">
        @if($top_courses['courses']->count())
            <div class="row">
                <!-- For LARGE SCREEN - START -->
                <div class="col-12 d-none d-lg-block">
                    <div id="carouselExampleIndicators2" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            @foreach ($top_courses['courses']->chunk(3) as $three)
                                <div class="carousel-item @if ($loop->first) active @endif">
                                    <div class="row">
                                        @foreach ($three as $item)
                                            <!-- carousal item - show 3 at a time -->
                                            <div class="col-md-4 mb-3">
                                                <div class="card">
                                                        <img class="img-fluid mx-auto img_max_x_200-s" alt="course-image" src="{{ getFileUrl($item->course_image, null, 'course') }}">
                                                    <!-- ------card content---- -->
                                                    <div class="">
                                                        <div class="d-flex mt-3 card_design_text-s">
                                                            <div class="container">
                                                                {{--  title and category - START  --}}
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <h6><a href="{{ route('course.view', ['uuid' => $item->uuid]) }}" class='no_link-s'>{{ $item->title ?? '(not set)' }}</a></h6>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <span>{{ ucwords($item->category->name ?? '(category not set)') }}</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col text-right">
                                                                        <a href="javascript:void(0)" class="btn btn px-lg-1 px-xl-3 course_pay_btn-s" disbaled="disbaled">{{ $item->is_course_free ? 'Free' : 'Paid' }}</a>
                                                                    </div>
                                                                </div>
                                                                {{--  title and category - END  --}}

                                                                <div class="row pt-3 pb-3">
                                                                    <div class="col-6 mb-3x">
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <img src="{{ asset('assets/images/youtube_icon.svg') }}" class="" alt="">
                                                                                <span class="mx-2">{{ ucwords($item->nature) }}</span>

                                                                                <br />
                                                                                <img src="{{ asset('assets/images/enrollment_icon.svg') }}" class="" alt="">
                                                                                <span class="mx-2"><strong>{{ getPeopleCount($item->students_count ?? 10) }}</strong> Students</span>

                                                                                <br />
                                                                                <img src="{{ asset('assets/images/dollar-icon.svg') }}" class="" alt="">
                                                                                <span class="mx-2">{{ ucwords($item->is_course_free? 'Free' : 'Paid') }}</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-6 text-center text-lg-center text-xl-right">
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <img src="{{ asset('assets/images/calendar_course_icon.svg') }}" class="" alt="">
                                                                                <span class="ml-2">{{ date('d M Y', strtotime($item->start_date)) }}</span>
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

    <section class='pt-5 pb-5'>
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading mb-2">
                        <h3>Video Course</h3>
                    </div>
                    <div class="panel-body shadow mb-5">
                        <div class="">
                              <canvas id="video_course_chart-d"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3>Online Courses</h3>
                    </div>
                    <div class="panel-body shadow">
                        <div class="">
                              <canvas id="online_course_chart-d"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{--  modals - START  --}}

    @include('course::modals.course_activity_type', [])
    @include('course::modals.online_course', [])
    @include('common::modals.waiting_popup', ['model_type' => 'Course'])

    {{-- // const data_month_names = '{!! $month_names_graph_data !!}';
    // const videoCoursesData = '{!! $video_courses_graph_data !!}'; --}}
    {{-- // const onlineCoursesData = '{!! $online_courses_graph_data !!}'; --}}

    {{--  modals - END  --}}

@endsection

@section('footer-scripts')
@php
    // dd( json_decode($online_courses_graph_data) );
@endphp
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.3.2/chart.min.js" integrity="sha512-VCHVc5miKoln972iJPvkQrUYYq7XpxXzvqNfiul1H4aZDwGBGC0lq373KNleaB2LpnC2a/iNfE5zoRYmB4TRDQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>

        const month_names = JSON.parse('{!! $month_names_graph_data !!}');
        const videoCoursesData = JSON.parse('{!! $video_courses_graph_data !!}');
        const onlineCoursesData = JSON.parse('{!! $online_courses_graph_data !!}') // [3, 10, 70, 2, 50, 30, 80];

        // const month_names = [
        //     'January',
        //     'February',
        //     'March',
        //     'April',
        //     'May',
        //     'June',
        // ];
        // const videoCoursesData = [0, 10, 5, 2, 20, 30, 45];
        // const onlineCoursesData = [3, 10, 70, 2, 50, 30, 80];

        console.log(month_names, onlineCoursesData, videoCoursesData);

    </script>
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    <script src="{{ asset('assets/js/manage_courses.js') }}"></script>
@endsection

@section('header-css')
    <link rel="stylesheet" href="{{ asset('assets/css/course.css') }}" />
@endsection
