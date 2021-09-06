@extends('teacher::layouts.teacher')

@section('page-title')
    Students
@endsection

@section('content')
    <div class="container-fluid">
        <!-- STUDENTS - START -->
        <div class="row py-5 px-3 px-md-4 px-lg-0 flex-column-reverse flex-lg-row">
            <!-- STUDENTS PROFILES - START -->
            <div class="col-12 col-md-12 col-lg-7 col-xl-8">
                <div class="row">
                    @forelse ($data->enrollment as $item)
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 py-3">
                            <div class="card shadow ">
                                <div class="card-body py-3">
                                    <div class="mb-4">
                                        <img class="rounded-circle mx-auto d-block img_size-s" src="{{ getFileUrl($item->student->profile_image ?? null, null, 'profile') }}" alt="{{ $item->student->first_name ?? '' }}" />
                                    </div>
                                    <div class="text-center fg-success-s">
                                        <h6 class="card-title">{{ $item->student->first_name }}</h6>
                                    </div>
                                    <div class="text-center">
                                        <span class="font_size_small-s">Enrolled Date {{ date('d M Y', strtotime($item->created_at)) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 py-3">
                            <div class="card shadow ">
                                <div class="card-body py-3">
                                    <div class="">
                                        <div class="row">
                                            <div class="col-12 text-center mt-5 mb-5">
                                                No Record Found
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
            <!-- STUDENTS PROFILES - END -->

            <!-- FILTER TO SEARCH A STUDENT -START-->
            <div class="col-xl-4 col-lg-5 col-md-12 col-sm-12 col-12 py-3">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('student.student-list') }}" method="post" id="" class="" novalidate>
                            <!-- Input Fields Start -->
                            @csrf
                            <div class="row ">
                                <div class="px-4 pb-5 pt-2">
                                    <h4>Filter</h4>
                                </div>
                                <div class="col-12">
                                    <label class="fg_light-s" for="course_type">Course Type</label>
                                    <div class="mb-3">
                                        <select name='course_type' class='form-control form-control-lg custom-input-s'>
                                            <option value="video" @if( isset($data->requestFilters['course_type']) && ('video' == $data->requestFilters['course_type'])) selected="selected" @endif>Video</option>
                                            <option value="online"  @if( isset($data->requestFilters['course_type']) && ('online' == $data->requestFilters['course_type'])) selected="selected" @endif>Online</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row  ">
                                <div class="col-12 mt-3 ">
                                    <label class="fg_light-s" for="course">Course</label>
                                    <div class="mb-3 ">
                                        <input type="text " class="form-control br_27px-s " name="course_title" id="course_title " placeholder="e.g Web Designing" value="{{ isset($data->requestFilters['course_title'])? $data->requestFilters['course_title'] : ''}}" >
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3 ">
                                <div class="col-xl-6 col-lg-12 col-md-6 pb-3">
                                    <label class="custom-label-s" for="startdate">Start Date</label>
                                    <input type="date" class="form-control form-control-lg login_input-s" name="startdate" id="startdate" placeholder="Start Date" />
                                </div>
                                <div class="col-xl-6 col-lg-12 col-md-6  pb-3">
                                    <label class="custom-label-s" for="enddate">End date</label>
                                    <input type="date" class="form-control form-control-lg login_input-s" name="enddate" id="startdate" placeholder="End Date" />
                                </div>
                            </div>
                            <!-- Input Fields End -->

                            <!-- Card Buttons -->
                            <div class="my-3 text-center">
                                <button type="submit" class="btn_success-s px-4 px-md-5 px-lg-4 px-xl-5 border border-white">Search</button>
                            </div>
                            <!-- Card Buttons End -->
                        </form>
                    </div>
                </div>
            </div>
            <!-- FILTER TO SEARCH A STUDENT -END-->
        </div>
        <!-- STUDENTS - END -->

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
