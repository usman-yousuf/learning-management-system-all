@extends('teacher::layouts.teacher')

@section('page-title')
    General Report
@endsection

@section('content')
    <div class="container-fluid px-xl-5 px-4">
                <div class="row pt-5">
                    <div class="col text-left">
                        <h2 class="">Report</h2>
                    </div>
                </div>
                <div class="row d-flex flex-lg-row  flex-column-reverse ">
                    <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12 mt-3 report_single_container-d">
                        @forelse ($data->courses as $item)
                            <!-- Report Single Container - START -->
                            <div class="row report_single_container-d">
                                <div class="col-12  mb-2 mt-1">
                                    <div class="card shadow ">
                                        <div class="card body">
                                            <div class="container-fluid  mb-4 mt-4">
                                                <div class="row ml-1 ">
                                                    <div class="col-xl-6 col-lg-6 col-md-6  ">
                                                        <h6 class="text-muted">Course Nature</h6>
                                                        <h6 class="course_status-d font_w_700-s mt-3">{{ $item->is_course_free ? 'Free' : 'Paid' }}</h6>
                                                    </div>
                                                    <div class="col-xl-6 col-lg-6 col-md-6 pt-xl-0 pt-lg-0 pt-md-0 pt-3">
                                                        <h6 class="text-muted">Course Title</h6>
                                                        <h6 class="course_title-d font_w_700-s mt-3">{{ ucwords($item->title) }}</h6>
                                                    </div>
                                                </div>
                                                <div class="row ml-1 mt-4">
                                                    <div class="col-xl-6 col-lg-6 col-md-6 ">
                                                        <h6 class="text-muted">Student Enrolled</h6>
                                                        <h6 class="font_w_700-s student_enrolled-d mt-3">{{ $item->students_count }}</h6>
                                                    </div>
                                                    <div class="col-xl-6 col-lg-6 col-md-6 pt-xl-0 pt-lg-0 pt-md-0 pt-3">
                                                        <h6 class="text-muted">Course Status</h6>
                                                        <h6 class="font_w_700-s student_completed-d mt-3">{{ ucwords($item->status) }}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Report Single Container - END -->
                        @empty
                            <div class="row report_single_container-d noRecordContainer-d">
                                <div class="col-12  mb-2 mt-1">
                                    <div class="card shadow ">
                                        <div class="card body">
                                            <div class="container-fluid  mb-4 mt-4">
                                                <div class="row">
                                                    <div class="col-12 text-center mt-5 mb-5">
                                                        No Record Found
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-12 ">
                        <div class="card shadow border-0  mt-3">
                            <div class="card-body">
                                <!-- Form Start -->
                                <form action="{{ route('report.general') }}" method="post">
                                    @csrf
                                    <div class="row pt-1">
                                        <div class="col">
                                            <h4 class="">Filter</h4>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <label class="custom-label-s" for="course_title">Title </label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control form-control-lg custom-input-s" name="course_title" id="course_title" placeholder="Logo Designing" value="{{ isset($data->requestFilters['course_title'])? $data->requestFilters['course_title'] : '' }}" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3 ">
                                        <div class="col-12">
                                            <label class="custom-label-s" for="is_course_free">Course Nature </label>
                                            <div class="input-group mb-3">
                                                <select class="form-control form-control-lg custom-input-s" name="is_course_free" id="is_course_free">
                                                    <option value="0" @if( isset($data->requestFilters) && !empty($data->requestFilters) && $data->requestFilters['is_course_free'] == 0 ) selected="selected" @endif>Paid</option>
                                                    <option value="1" @if( isset($data->requestFilters) && !empty($data->requestFilters) && $data->requestFilters['is_course_free'] == 1 ) selected="selected" @endif>Free</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3 ">
                                        <div class="col-12">
                                            <label class="custom-label-s" for="students_count"># of Students </label>
                                            <div class="input-group mb-3">
                                                @php
                                                    // dd($data->requestFilters['students_count']);
                                                @endphp
                                                <input type="number" class="form-control form-control-lg custom-input-s" name="students_count" placeholder="e,g > 25" value="{{ isset($data->requestFilters['students_count'])? $data->requestFilters['students_count'] : '' }}" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row justify-content-center mt-5 mb-3">
                                        <div class="col-xl-9 col-lg-10 col-md-9 col-9 ">
                                            <button class='btn br_24-s text-white bg-success-s btn-lg w-100' type="submit">Search</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endsection


@section('footer-scripts')
    <script src="{{ asset('assets/js/manage_courses.js') }}"></script>
@endsection

@section('header-css')
    <link rel="stylesheet" href="{{ asset('assets/css/course.css') }}" />
@endsection


@push('header-scripts')
    <script>
        let modal_delete_outline_url = "{{ route('course.delete-outline') }}";
        let modal_delete_slot_url = "{{ route('course.delete-slot') }}";
        let modal_delete_video_content_url = "{{ route('course.delete-video-content') }}";
    </script>
@endpush
