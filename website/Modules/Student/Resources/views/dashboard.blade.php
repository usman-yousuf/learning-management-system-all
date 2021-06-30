@extends('teacher::layouts.teacher')
@section('page-title')
    Student Dashboard
@endsection

@section('header-css')
    <link rel="stylesheet" href="{{ asset('assets/css/course.css') }}" />
@endsection

@section('content')
    <div class="w-100 px-4">
        <div class="row pt-5 ">
            <!--header start-->
            <div class="col-xl-7 col-lg-7 col-md-5 col-sm-12 col-12 ">
                <h3 class="country_text-s mt-4 font_family_sans-serif-s">Dashboard</h3>
            </div>
            <div class="col-xl-5 col-lg-5 col-md-7 col-sm-12 col-12">
                <div class="form-group">
                    <!--drop down menu of search bar start-->
                    <div class="search_dropdown-s mt-3">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">
                                    <img src="{{ getIconUrl('search', 'dashboard_search') }}" style='height: 24px;' alt="search icon">
                                </span>
                            </div>
                            <input type="text" class="search_bar-s  pl-5 py-2 br_40px-s form-control input-lg search_dropdown-d" id="" placeholder="Search...">
                        </div>
                        <!--search bar end-->
                        <div id="search_ref_option-d" class=" search_dropdown_content-s fg_dark_grey-s display_none-s border ml-xl-3 ml-lg-2 ml-md-1 ml-2 bg-white text-left">
                            <div class="bg_grey_on_hover-s">
                                <div class="py-3 pl-4">
                                    <span class="font_family_sans-serif-s ">Views All Result with <span class="font-weight-bold fg_black-s">Mobile</span></span>
                                </div>
                                <div class="py-2 pl-4 ">
                                    <span class="font_family_sans-serif-s "><span class="font-weight-bold fg_black-s">Mobile</span> APP Desajd  kd k kdsd  dksd ks dsigning </span>
                                </div>
                                <div class="py-2 pl-4 ">
                                    <span class="font_family_sans-serif-s  "><span class="font-weight-bold fg_black-s">Mobile</span> Game Designing</span>
                                </div>
                                <div class="py-2 pl-4">
                                    <span class="font_family_sans-serif-s "><span class="font-weight-bold fg_black-s">Mobile</span> UI Designing</span>
                                </div>
                                <div class="py-2 pl-4">
                                    <span class=" font_family_sans-serif-s"><span class="font-weight-bold fg_black-s">Mobile</span> UI/UX Designing</span>
                                </div>
                            </div>
                            <div class="dropdown-divider"></div>

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
                            </div>

                        </div>
                    </div>
                    <!--drop down menu of search bar end-->
                </div>
            </div>
            <!--header end-->
        </div>

        <section class="py-3">
            {{--  section heading - START --}}
            <div class="row pt-3  pb-3">
                <div class="col-xl-8 col-lg-7 col-md-6 col-sm-12 col-12 mt-2">
                    <h3 class="font_family_sans-serif-s">My Enrolled  Courses</h3>
                </div>
                <!--view all courses button and carousel slide button-->
                <div class="col-xl-4  col-lg-5 col-md-6 col-sm-12 col-12 text-right pr-1">
                    @if($enrolled_courses->total_count)
                        <a href="javascript:void(0)" class="btn bg_success-s text-white br_21px-s mr-2 px-4">View All</a>
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
                        <a href="javascript:void(0)" class="btn bg_success-s text-white br_21px-s mr-2 px-4">View All</a>
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
@endsection

@push('header-scripts')
    <script>
        let get_course_slots_by_course_uuid_url = "{{ route('course.get-slots-by-course') }}";
    </script>
@endpush

@section('footer-scripts')
    <script src="{{ asset('assets/js/manage_courses.js') }}"></script>
@endsection
