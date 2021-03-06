@php
    $section_name = (\Auth::check())? 'course_content' : 'content';
    $target_layout = (\Auth::check()) ? 'course::layouts.course_view_layout' : 'layouts.landing_page';
@endphp

@extends($target_layout)

@section('page-title')
    View Course
@endsection

@section($section_name)
    @php
        // dd($course->queries);
    @endphp

    <div class="row">
        <div class="col-10 offset-1">
            <div class="course_details_main_container-d @if(!\Auth::check()) mt-5 @endif">
                <div class="course_conver_img_container-s">
                    <img src="{{ getFileUrl($course->course_image ?? null, null, 'course_preview') }}" alt="course-image" class="course_preview_image-s" />
                </div>

                <div class="course_info_container-s pt-4">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th scope="row" class='text-muted'>Teacher:</th>
                                <td>{{ ucwords($course->teacher->full_name ?? 'Teacher Name') }}</td>
                            </tr
                            <tr>
                                <th scope="row" class='text-muted'>Course Type:</th>
                                <td>{{ ucwords($course->nature ?? '(not set)') }}</td>
                            </tr>
                            <tr>
                                <th scope="row" class='text-muted'>Students:</th>
                                <td>{{ $course->enrolled_students_count ?? 0 }}</td>
                            </tr>
                            <tr>
                                <th scope="row" class='text-muted'>Handouts:</th>
                                <td>{{ $course->handouts_count }}</td>
                            </tr>
                            <tr>
                                <th scope="row" class='text-muted'>Duration:</th>
                                <td>
                                    <strong class='end_date-d'>{{ date('M d, Y', strtotime($course->start_date)) }}</strong>
                                    &nbsp;&nbsp; to &nbsp;
                                    <strong class='end_date-d'>{{ date('M d, Y', strtotime($course->end_date)) }}</strong></td>
                            </tr>
                            <tr>
                                <th scope="row" class='text-muted'>Course Fee:</th>
                                <td>{{ getCoursePriceWithUnit($course) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="course_details_container-d" id='outline_main_container-d'>
                    <div class="row pb-4">
                        <!--Total outline-->
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 mt-4 align-self-center">
                            <h3 class="total_videos_text-s">Course Outline</h3>
                            <h5 class="text-success">Total {{ get_padded_number($course->total_outlines_count ?? 0) }}</h5>
                        </div>
                        <!--Total outline end-->

                        <!--Add outline-->
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 mt-4 ">
                            @if(\Auth::check())
                                @if((\Auth::user()->profile_type != 'student') && (\Auth::user()->profile_type != 'parent') )
                                    <div class="float-md-right">
                                        <a href="javascript:void(0)" class="btn btn py-3 px-4 add_course_btn-s open_add_outline_modal-d">
                                            <img src="{{ asset('assets/images/add_btn_icon.svg') }}" width="20" class="mx-2" alt="+">
                                            <span class="mx-2 text-white">Add Outline</span>
                                        </a>
                                    </div>
                                @endif
                            @endif
                        </div>
                        <!--add outline end-->
                    </div>

                    <div class="outlines_container-d main_page-d">
                        @include('course::partials.course_outline', ['page' => 'details', 'outlines' => $course->outlines])
                    </div>

                    @if(isset($course) && ($course->is_handout_free))
                        <div class="outlines_container-d main_page-d">
                            <h3>Handouts</h3>
                            <h5 class="text-success">Total {{ get_padded_number($course->handouts_count) }}</h5>
                            @include('course::partials.course_handout_content', ['page' => 'preview', 'handouts' => $course->handouts])
                        </div>
                    @endif

                    <div class="row pb-4">
                        <!-- Enroll Course - START -->
                        @if(\Auth::check())
                            <div class="col-8 offset-2 my-4 text-center">
                                <a href="javascript:void(0)" class='fs_19px-s btn btn_orange-s w_200px-s br_21px-s ' data-toggle="modal" data-target="#enroll_student_modal-d">
                                    Enroll
                                </a>
                            </div>
                        @endif
                        <!-- Enroll Course - END -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('student::modals.enroll_student_modal', ['course' => $course])

@endsection


@section('footer-scripts')
    <script src="{{ asset('assets/js/manage_courses.js') }}"></script>
    @if(\Auth::check())
        @if((\Auth::user()->profile_type == 'student') || (\Auth::user()->profile_type == 'parent') )
            <script src="{{ asset('assets/js/student.js') }}"></script>
        @endif
    @endif
@endsection

@section('header-css')
    <link rel="stylesheet" href="{{ asset('assets/css/course.css') }}" />
@endsection


@push('header-scripts')
    <script>
        let modal_delete_query_response_url = "{{ route('query.delete-response') }}";
    </script>
@endpush
