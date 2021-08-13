@extends('teacher::layouts.teacher')
@section('page-title')
    Student Dashboard
@endsection

@section('header-css')
    <link rel="stylesheet" href="{{ asset('assets/css/course.css') }}" />
@endsection

@section('content')
    <div class="w-100 px-4">
        <div class="row pt-4 ">
            <!--header start-->
            <div class="col-xl-7 col-lg-7 col-md-5 col-sm-12 col-12 ">
                <h3 class="country_text-s mt-4 font_family_sans-serif-s">Dashboard</h3>
            </div>
            <div class="col-xl-5 col-lg-5 col-md-7 col-sm-12 col-12">
                <div class="form-group">
                    <!--drop down menu of search bar start-->
                    <div class="search_dropdown-s mt-3">
                        <div class="input-group input_group_add_on-s">
                            <div class="input-group-prepend">
                                <span class="input-group-text addon_span-s">
                                    <img src="{{ getIconUrl('search', 'dashboard_search') }}" style='height: 24px;' alt="search icon">
                                </span>
                            </div>
                            <input type="text" class="search_bar-s py-2 br_40px-s form-control input-lg dashboard_search-d" placeholder="Search...">
                        </div>
                        {{-- show result  --}}
                        {{--  <div class="show-result">
                            <ul class="list-group list-group-flush getResult">  --}}
                                {{-- <li class="list-group-item getResult"></li> --}}

                              {{--  </ul>
                        </div>  --}}
                        <!--search bar end-->
                        <div id="search_ref_option-d" class="search_dropdown_content-s fg_dark_grey-s border ml-xl-3 ml-lg-2 ml-md-1 ml-2 bg-white text-left" style='display:none;'>
                            {{--  display_none-s  --}}
                            <div class="">
                                <div class="course_view_more_container-d bg_grey_on_hover-s">
                                    <div class="py-3 pl-4">
                                        <span class="font_family_sans-serif-s">Views All Result with <a class="font-weight-bold fg_black-s see_all_link-d" href="{{ route('student.searchDashboard') }}" data-keywords=''>keywords</a></span>
                                    </div>
                                </div>
                                <div class="course_search_results_container-d bg_grey_on_hover-s">
                                    <div class="py-2 pl-4 single_search_result-d">
                                        <span class="font_family_sans-serif-s font-weight-bold fg_black-s search_Result_cat-d">
                                            Course Category
                                        </span>
                                        <a class="font_family_sans-serif-s search_result_title_link-d" href="javascript:void(0)">
                                            Course Title
                                        </a>
                                    </div>
                                </div>
                            </div>
                            {{--  <div class="dropdown-divider"></div>

                            <div class="bg_grey_on_hover-s pb-2">
                                <div class="py-2 pl-4 ">
                                    <h6>People</h6>
                                </div>
                                <div class=" py-2 pl-4 pr-3 d-flex justify-content-between text-align-center">
                                    <div class="d-flex justify-content-between text-align-center">
                                        <div>
                                            <img class="img_35_x_35-s " src="../assets/preview/student4.png" alt="student img">
                                        </div>
                                        <div class="ml-3 mt-xl-2 ">
                                            <h6 class=" text-wrap">Noah Robert</h6>
                                        </div>
                                    </div>
                                    <div class="ml-3 mt-xl-2 ">
                                        <h6><span class="font-weight-bold fg_black-s ">Mobile</span> UI Designing</h6>
                                    </div>
                                </div>
                                <div class=" py-2 pl-4 pr-3 d-flex justify-content-between text-align-center">
                                    <div class="d-flex justify-content-between text-align-center">
                                        <div>
                                            <img class="img_35_x_35-s " src="{{ getFileUrl(null, null, 'profile') }}" alt="student img">
                                        </div>
                                        <div class="ml-3 mt-xl-2 ">
                                            <h6 class=" text-wrap">Noah Robert</h6>
                                        </div>
                                    </div>
                                    <div class="ml-3 mt-xl-2 ">
                                        <h6><span class="font-weight-bold fg_black-s ">Mobile</span> UI Designing</h6>
                                    </div>
                                </div>
                            </div>  --}}

                        </div>
                    </div>
                    <!--drop down menu of search bar end-->
                </div>
            </div>
            <!--header end-->
        </div>

        @if($enrolled_courses->total_count)
            <section class="py-3">
                {{--  section heading - START --}}
                <div class="row pt-3  pb-3">
                    <div class="col-xl-8 col-lg-7 col-md-6 col-sm-12 col-12 mt-2">
                        <h3 class="font_family_sans-serif-s">My Enrolled Courses</h3>
                    </div>
                    <!--view all courses button and carousel slide button-->
                    <div class="col-xl-4  col-lg-5 col-md-6 col-sm-12 col-12 text-right pr-1">
                        @if($enrolled_courses->total_count)
                            {{-- {{ dd($enrolled_courses) }} --}}
                            {{-- <a href="javascript:void(0)" class="btn bg_success-s text-white br_21px-s mr-2 px-4">View All</a> --}}
                            <a href="{{ route('listStudentEnrollSuggestNature', ['call' =>'enrolled']) }}" class="btn bg_success-s text-white br_21px-s mr-2 px-4">View All</a>
                            {{--  <img src="assets/preview/left_scroll.svg" alt="left scroll button">
                            <img src="assets/preview/right_scroll.svg" alt="left scroll button">  --}}
                        @endif
                    </div>
                </div>
                {{--  section heading - END --}}

                @include('course::partials/_course_listing', [
                    'courses' => $enrolled_courses
                    , 'section' => 'student-enrollments-listing'
                ])
            </section>
        @endif
    </div>

    <div class="w-100 px-4">
        <section class="py-3">

            <div class="row pt-3 pb-3">
                <div class="col-xl-8 col-lg-7 col-md-6 col-sm-12 col-12 mt-2">
                    <h3 class="font_family_sans-serif-s">Suggested Courses</h3>
                </div>
                <!--view all courses button and carousel slide button-->
                <div class="col-xl-4  col-lg-5 col-md-6 col-sm-12 col-12 text-right pr-1">
                    @if($suggested_courses->total_count)
                        {{-- <a href="javascript:void(0)" class="btn bg_success-s text-white br_21px-s mr-2 px-4">View All</a> --}}
                        <a href="{{ route('listStudentEnrollSuggestNature', ['call' =>'suggested']) }}" class="btn bg_success-s text-white br_21px-s mr-2 px-4">View All</a>
                        {{--  <img src="assets/preview/left_scroll.svg" alt="left scroll button">
                        <img src="assets/preview/right_scroll.svg" alt="left scroll button">  --}}
                    @endif
                </div>
            </div>

            @include('course::partials/_course_listing', [
                'courses' => $suggested_courses
                , 'section' => 'student-side-course-listing'
            ])
        </section>
    </div>

    @include('student::modals.enroll_student_modal')

    <div class="clonables_container-d" style='display:none;'>
        <div class="py-2 pl-4 single_search_result-d" id='cloneable_single_search_result-d'>
            <span class="font_family_sans-serif-s font-weight-bold fg_black-s search_Result_cat-d">
                Course Category
            </span>
            <a class="font_family_sans-serif-s search_result_title_link-d" href="javascript:void(0)">
                Course Title
            </a>
        </div>
    </div>
@endsection

@push('header-scripts')
    <script>
        let search_Result_url = "{{ route('student.searchDashboard') }}";
        let preview_course_url = "{{ route('course.preview', ['uuid' => '______']) }}";
    </script>
@endpush

@section('footer-scripts')
    <script src="{{ asset('assets/js/manage_courses.js') }}"></script>
@endsection
